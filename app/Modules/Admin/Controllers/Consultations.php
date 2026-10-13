<?php

namespace Modules\Admin\Controllers;

use App\Controllers\BaseController;

class Consultations extends BaseController
{
    /**
     * Memastikan hanya Administrator yang dapat mengakses.
     */
    private function authorized(): bool
    {
        return session()->get('isLoggedIn') === true
            && session()->get('user_role') === 'admin'
            && ctype_digit((string) session()->get('user_id'));
    }

    private function deny()
    {
        return redirect()->to(site_url('login'))
            ->with(
                'error',
                'Silakan login sebagai administrator.'
            );
    }

    /**
     * Pemantauan Konsultasi
     * Hanya membaca data, tanpa mengubah database.
     */
    public function index()
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        $db = db_connect();

        $keyword = trim((string) $this->request->getGet('keyword'));
        $type = trim((string) $this->request->getGet('type'));
        $status = trim((string) $this->request->getGet('status'));

        // Jenis sesi yang tersedia dalam database.
        $allowedTypes = ['Online', 'Pick-up'];

        if (!in_array($type, $allowedTypes, true)) {
            $type = '';
        }

        // Mengambil pilihan status langsung dari database
        // agar tidak menebak nilai ENUM yang digunakan tim.
        $statusOptions = $db->table('konsultasi')
            ->select('status')
            ->distinct()
            ->where('status IS NOT NULL', null, false)
            ->orderBy('status', 'ASC')
            ->get()
            ->getResultArray();

        $allowedStatuses = array_column($statusOptions, 'status');

        if (!in_array($status, $allowedStatuses, true)) {
            $status = '';
        }

        // Statistik seluruh konsultasi.
        $totalConsultations = $db->table('konsultasi')
            ->countAllResults();

        $onlineConsultations = $db->table('konsultasi')
            ->where('tipe_sesi', 'Online')
            ->countAllResults();

        $pickupConsultations = $db->table('konsultasi')
            ->where('tipe_sesi', 'Pick-up')
            ->countAllResults();

        // Rekap jumlah konsultasi berdasarkan status.
        $statusSummary = $db->table('konsultasi')
            ->select('status, COUNT(*) AS total', false)
            ->groupBy('status')
            ->orderBy('status', 'ASC')
            ->get()
            ->getResultArray();

        // Daftar konsultasi beserta nama klien dan psikolog.
        $query = $db->table('konsultasi k')
            ->select(
                'k.*, ' .
                'klien.nama_lengkap AS nama_klien, ' .
                'psikolog.nama_lengkap AS nama_psikolog'
            )
            ->join(
                'users klien',
                'klien.id_user = k.id_klien',
                'left'
            )
            ->join(
                'users psikolog',
                'psikolog.id_user = k.id_konsultan',
                'left'
            );

        if ($keyword !== '') {
            $query->groupStart()
                ->like('klien.nama_lengkap', $keyword)
                ->orLike('psikolog.nama_lengkap', $keyword)
                ->groupEnd();
        }

        if ($type !== '') {
            $query->where('k.tipe_sesi', $type);
        }

        if ($status !== '') {
            $query->where('k.status', $status);
        }

        $consultations = $query
            ->orderBy('k.id_konsultasi', 'DESC')
            ->get()
            ->getResultArray();

        return view(
            'Modules\Admin\Views\consultations\index',
            [
                'title' => 'Pemantauan Konsultasi - Counseling Platform',
                'consultations' => $consultations,
                'keyword' => $keyword,
                'selectedType' => $type,
                'selectedStatus' => $status,
                'statusOptions' => $statusOptions,
                'statusSummary' => $statusSummary,
                'totalConsultations' => $totalConsultations,
                'onlineConsultations' => $onlineConsultations,
                'pickupConsultations' => $pickupConsultations,
            ]
        );
    }

    /**
     * Detail administratif konsultasi.
     *
     * Tidak mengambil isi pesan maupun catatan konseling.
     */
    public function show($id)
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        if (!ctype_digit((string) $id) || (int) $id <= 0) {
            return redirect()
                ->to(site_url('admin/consultations'))
                ->with('error', 'ID konsultasi tidak valid.');
        }

        $db = db_connect();

        $consultation = $db->table('konsultasi k')
            ->select(
                'k.*, ' .
                'klien.nama_lengkap AS nama_klien, ' .
                'psikolog.nama_lengkap AS nama_psikolog'
            )
            ->join(
                'users klien',
                'klien.id_user = k.id_klien',
                'left'
            )
            ->join(
                'users psikolog',
                'psikolog.id_user = k.id_konsultan',
                'left'
            )
            ->where('k.id_konsultasi', (int) $id)
            ->get()
            ->getRowArray();

        if (!$consultation) {
            return redirect()
                ->to(site_url('admin/consultations'))
                ->with('error', 'Data konsultasi tidak ditemukan.');
        }

        return view(
            'Modules\Admin\Views\consultations\show',
            [
                'title' => 'Detail Konsultasi - Counseling Platform',
                'consultation' => $consultation,
            ]
        );
    }
}
