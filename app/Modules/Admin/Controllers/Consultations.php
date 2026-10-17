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

        $payment = $db->table('pembayaran')
            ->where('id_konsultasi', (int) $id)
            ->get()
            ->getRowArray();


        return view(
            'Modules\Admin\Views\consultations\show',
            [
                'title' => 'Detail Konsultasi - Counseling Platform',
                'consultation' => $consultation,
                'payment' => $payment,
            ]
        );
    }

    /**
     * Menyetujui bukti pembayaran yang masih Pending.
     */
    public function verifyPayment($id)
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        if (!ctype_digit((string) $id) || (int) $id <= 0) {
            return redirect()->to(site_url('admin/consultations'))
                ->with('error', 'ID konsultasi tidak valid.');
        }

        $db = db_connect();
        $id = (int) $id;

        $pembayaran = $db->table('pembayaran')
            ->where('id_konsultasi', $id)
            ->get()
            ->getRowArray();

        if (!$pembayaran || $pembayaran['status_bayar'] !== 'Pending') {
            return redirect()->to(site_url('admin/consultations/' . $id))
                ->with('error', 'Pembayaran tidak ditemukan atau sudah diproses.');
        }

        if (empty($pembayaran['bukti_bayar'])) {
            return redirect()->to(site_url('admin/consultations/' . $id))
                ->with('error', 'Bukti pembayaran belum diunggah.');
        }


        $updated = $db->table('pembayaran')
            ->where('id_pembayaran', $pembayaran['id_pembayaran'])
            ->where('status_bayar', 'Pending')
            ->update([
                'status_bayar' => 'Sukses',
                'tanggal_bayar' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        if (!$updated || $db->affectedRows() !== 1) {
            return redirect()->to(site_url('admin/consultations/' . $id))
                ->with('error', 'Verifikasi gagal. Silakan periksa status pembayaran.');
        }

        return redirect()->to(site_url('admin/consultations/' . $id))
            ->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Menandai pembayaran Pending sebagai Gagal.
     */
    public function rejectPayment($id)
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        if (!ctype_digit((string) $id) || (int) $id <= 0) {
            return redirect()->to(site_url('admin/consultations'))
                ->with('error', 'ID konsultasi tidak valid.');
        }

        $db = db_connect();
        $id = (int) $id;

        $pembayaran = $db->table('pembayaran')
            ->where('id_konsultasi', $id)
            ->get()
            ->getRowArray();

        if (!$pembayaran || $pembayaran['status_bayar'] !== 'Pending') {
            return redirect()->to(site_url('admin/consultations/' . $id))
                ->with('error', 'Pembayaran tidak ditemukan atau sudah diproses.');
        }

        if (empty($pembayaran['bukti_bayar'])) {
            return redirect()->to(site_url('admin/consultations/' . $id))
                ->with('error', 'Bukti pembayaran belum diunggah.');
        }


        $updated = $db->table('pembayaran')
            ->where('id_pembayaran', $pembayaran['id_pembayaran'])
            ->where('status_bayar', 'Pending')
            ->update([
                'status_bayar' => 'Gagal',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        if (!$updated || $db->affectedRows() !== 1) {
            return redirect()->to(site_url('admin/consultations/' . $id))
                ->with('error', 'Penolakan gagal. Silakan periksa status pembayaran.');
        }

        return redirect()->to(site_url('admin/consultations/' . $id))
            ->with('success', 'Pembayaran ditandai gagal.');
    }

    /**
     * Menampilkan bukti pembayaran khusus administrator.
     */
    public function paymentProof($id)
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        if (!ctype_digit((string) $id) || (int) $id <= 0) {
            return redirect()->to(site_url('admin/consultations'))
                ->with('error', 'ID konsultasi tidak valid.');
        }

        $db = db_connect();

        $payment = $db->table('pembayaran')
            ->where('id_konsultasi', (int) $id)
            ->get()
            ->getRowArray();

        if (!$payment || empty($payment['bukti_bayar'])) {
            return redirect()->to(site_url('admin/consultations/' . (int) $id))
                ->with('error', 'Bukti pembayaran tidak ditemukan.');
        }

        $folder = WRITEPATH . 'uploads/bukti_pembayaran';
        $filename = basename($payment['bukti_bayar']);
        $path = $folder . DIRECTORY_SEPARATOR . $filename;

        if (!is_file($path)) {
            return redirect()->to(site_url('admin/consultations/' . (int) $id))
                ->with('error', 'File bukti pembayaran tidak ditemukan.');
        }

        $mime = mime_content_type($path);

        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        if (!in_array($mime, $allowedMimeTypes, true)) {
            return redirect()->to(site_url('admin/consultations/' . (int) $id))
                ->with('error', 'Format bukti pembayaran tidak didukung.');
        }


        $imageContent = file_get_contents($path);

        if ($imageContent === false) {
            return redirect()->to(site_url('admin/consultations/' . (int) $id))
                ->with('error', 'Bukti pembayaran gagal dibaca.');
        }

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'private, no-store')
            ->setBody($imageContent);
    }
}
