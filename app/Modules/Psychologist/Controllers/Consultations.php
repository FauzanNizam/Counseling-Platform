<?php

namespace Modules\Psychologist\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class Consultations extends BaseController
{
    // ==========================================
    // IDENTITAS PSIKOLOG
    // ==========================================

    private function psychologistId(): int
    {
        $session = session();

        if (
            $session->get('isLoggedIn') !== true ||
            $session->get('user_role') !== 'psychologist'
        ) {
            return 0;
        }

        return (int) $session->get('user_id');
    }

    // ==========================================
    // CARI KONSULTASI MILIK PSIKOLOG
    // ==========================================

    private function findConsultation(
        int $id,
        int $psychologistId
    ): ?array {
        $result = db_connect()
            ->table('konsultasi k')
            ->select('
                k.*,
                u.nama_lengkap AS nama_klien,
                u.no_telepon AS no_telepon_klien
            ')
            ->join('users u', 'u.id_user = k.id_klien')
            ->where('k.id_konsultasi', $id)
            ->where('k.id_konsultan', $psychologistId)
            ->get()
            ->getRowArray();

        return $result ?: null;
    }

    // ==========================================
    // DAFTAR KONSULTASI
    // ==========================================

    public function index()
    {
        $psychologistId = $this->psychologistId();

        if ($psychologistId <= 0) {
            return redirect()->to('/login');
        }

        $consultations = db_connect()
            ->table('konsultasi k')
            ->select('
                k.id_konsultasi,
                k.tanggal,
                k.waktu_mulai,
                k.waktu_selesai,
                k.tipe_sesi,
                k.status,
                u.nama_lengkap AS nama_klien
            ')
            ->join('users u', 'u.id_user = k.id_klien')
            ->where('k.id_konsultan', $psychologistId)
            ->orderBy('k.tanggal', 'DESC')
            ->orderBy('k.waktu_mulai', 'ASC')
            ->get()
            ->getResultArray();

        return view(
            'Modules\Psychologist\Views\consultations\index',
            [
                'title' => 'Jadwal Konsultasi - Counseling',
                'consultations' => $consultations,
            ]
        );
    }

    // ==========================================
    // DETAIL KONSULTASI
    // ==========================================

    public function show($id = null)
    {
        $psychologistId = $this->psychologistId();

        if ($psychologistId <= 0) {
            return redirect()->to('/login');
        }

        $consultation = $this->findConsultation(
            (int) $id,
            $psychologistId
        );

        if (!$consultation) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view(
            'Modules\Psychologist\Views\consultations\show',
            [
                'title' => 'Detail Konsultasi - Counseling',
                'consultation' => $consultation,
            ]
        );
    }

    // ==========================================
    // TERIMA KONSULTASI
    // ==========================================

    public function confirm($id = null)
    {
        return $this->changeStatus(
            $id,
            'Menunggu Konfirmasi',
            'Dikonfirmasi',
            'Permintaan konsultasi berhasil diterima.'
        );
    }

    // ==========================================
    // TOLAK KONSULTASI
    // ==========================================

    public function reject($id = null)
    {
        return $this->changeStatus(
            $id,
            'Menunggu Konfirmasi',
            'Dibatalkan',
            'Permintaan konsultasi berhasil ditolak.'
        );
    }

    // ==========================================
    // MULAI SESI
    // ==========================================

    public function start($id = null)
    {
        return $this->changeStatus(
            $id,
            'Dikonfirmasi',
            'Berlangsung',
            'Sesi konsultasi berhasil dimulai.'
        );
    }

    // ==========================================
    // SELESAIKAN SESI
    // ==========================================

    public function finish($id = null)
    {
        return $this->changeStatus(
            $id,
            'Berlangsung',
            'Selesai',
            'Sesi konsultasi berhasil diselesaikan.'
        );
    }

    // ==========================================
    // SIMPAN LINK GOOGLE MEET / ZOOM
    // ==========================================

    public function saveMeeting($id = null)
    {
        $psychologistId = $this->psychologistId();

        if ($psychologistId <= 0) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            throw PageNotFoundException::forPageNotFound();
        }

        $id = (int) $id;

        $consultation = $this->findConsultation(
            $id,
            $psychologistId
        );

        if (!$consultation) {
            throw PageNotFoundException::forPageNotFound();
        }

        $detailUrl = site_url(
            'psychologist/consultations/' . $id
        );

        // Hanya konsultasi online
        if ($consultation['tipe_sesi'] !== 'Online') {
            return redirect()
                ->to($detailUrl)
                ->with(
                    'error',
                    'Link meeting hanya tersedia untuk konsultasi Online.'
                );
        }

        // Tidak boleh mengubah link sesi yang sudah selesai
        if (!in_array(
            $consultation['status'],
            ['Dikonfirmasi', 'Berlangsung'],
            true
        )) {
            return redirect()
                ->to($detailUrl)
                ->with(
                    'error',
                    'Link meeting hanya dapat diatur pada sesi yang telah dikonfirmasi atau sedang berlangsung.'
                );
        }

        $platform = trim(
            (string) $this->request->getPost('platform')
        );

        $meetingUrl = trim(
            (string) $this->request->getPost('link_meeting')
        );

        // Validasi pilihan platform
        $allowedPlatforms = [
            'Google Meet',
            'Zoom',
        ];

        if (!in_array($platform, $allowedPlatforms, true)) {
            return redirect()
                ->to($detailUrl)
                ->with(
                    'error',
                    'Silakan pilih Google Meet atau Zoom.'
                );
        }

        // Validasi panjang link
        if (
            $meetingUrl === '' ||
            strlen($meetingUrl) > 2048 ||
            !filter_var($meetingUrl, FILTER_VALIDATE_URL)
        ) {
            return redirect()
                ->to($detailUrl)
                ->with(
                    'error',
                    'Link meeting tidak valid.'
                );
        }

        $parts = parse_url($meetingUrl);

        $scheme = strtolower($parts['scheme'] ?? '');
        $host = strtolower($parts['host'] ?? '');

        // Wajib HTTPS
        if ($scheme !== 'https') {
            return redirect()
                ->to($detailUrl)
                ->with(
                    'error',
                    'Link meeting harus menggunakan HTTPS.'
                );
        }

        // Hindari link dengan kredensial pada URL
        if (isset($parts['user']) || isset($parts['pass'])) {
            return redirect()
                ->to($detailUrl)
                ->with(
                    'error',
                    'Link meeting tidak valid.'
                );
        }

        // Pastikan domain sesuai platform
        $validHost = false;

        if ($platform === 'Google Meet') {
            $validHost = $host === 'meet.google.com';
        }

        if ($platform === 'Zoom') {
            $validHost = $host === 'zoom.us'
                || str_ends_with($host, '.zoom.us');
        }

        if (!$validHost) {
            return redirect()
                ->to($detailUrl)
                ->with(
                    'error',
                    'Domain link tidak sesuai dengan platform yang dipilih.'
                );
        }

        $db = db_connect();

        $updated = $db->table('konsultasi')
            ->where('id_konsultasi', $id)
            ->where('id_konsultan', $psychologistId)
            ->whereIn('status', [
                'Dikonfirmasi',
                'Berlangsung',
            ])
            ->where('tipe_sesi', 'Online')
            ->update([
                'platform' => $platform,
                'link_meeting' => $meetingUrl,
            ]);

        if (!$updated) {
            return redirect()
                ->to($detailUrl)
                ->with(
                    'error',
                    'Gagal menyimpan link meeting.'
                );
        }

        return redirect()
            ->to($detailUrl)
            ->with(
                'success',
                'Link meeting berhasil disimpan. Link dapat ditampilkan kepada klien melalui halaman konsultasinya.'
            );
    }

    // ==========================================
    // PERUBAHAN STATUS KONSULTASI
    // ==========================================

    private function changeStatus(
        $id,
        string $expectedStatus,
        string $newStatus,
        string $successMessage
    ) {
        $psychologistId = $this->psychologistId();

        if ($psychologistId <= 0) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            throw PageNotFoundException::forPageNotFound();
        }

        $id = (int) $id;

        $consultation = $this->findConsultation(
            $id,
            $psychologistId
        );

        if (!$consultation) {
            throw PageNotFoundException::forPageNotFound();
        }

        $detailUrl = site_url(
            'psychologist/consultations/' . $id
        );

        if ($consultation['status'] !== $expectedStatus) {
            return redirect()
                ->to($detailUrl)
                ->with(
                    'error',
                    'Status konsultasi sudah berubah atau tidak dapat diproses.'
                );
        }

        // Timezone WITA
        $timezone = new \DateTimeZone('Asia/Makassar');

        $now = new \DateTimeImmutable(
            'now',
            $timezone
        );

        $date = (string) ($consultation['tanggal'] ?? '');

        $time = (string) (
            $consultation['waktu_mulai'] ?? '00:00:00'
        );

        if (preg_match('/^\d{2}:\d{2}$/', $time)) {
            $time .= ':00';
        }

        $sessionStart = \DateTimeImmutable::createFromFormat(
            '!Y-m-d H:i:s',
            $date . ' ' . $time,
            $timezone
        );

        if (!$sessionStart) {
            return redirect()
                ->to($detailUrl)
                ->with(
                    'error',
                    'Waktu konsultasi tidak valid.'
                );
        }

        // Terima / Tolak sebelum jadwal dimulai
        if (in_array(
            $newStatus,
            ['Dikonfirmasi', 'Dibatalkan'],
            true
        )) {
            if ($now >= $sessionStart) {
                return redirect()
                    ->to($detailUrl)
                    ->with(
                        'error',
                        'Permintaan yang waktunya sudah dimulai tidak dapat diproses.'
                    );
            }
        }

        // Mulai sesi maksimal 15 menit sebelum jadwal
        if ($newStatus === 'Berlangsung') {
            $earliestStart = $sessionStart->modify(
                '-15 minutes'
            );

            if ($now < $earliestStart) {
                return redirect()
                    ->to($detailUrl)
                    ->with(
                        'error',
                        'Sesi baru dapat dimulai 15 menit sebelum jadwal.'
                    );
            }
        }

        $db = db_connect();

        $updated = $db->table('konsultasi')
            ->where('id_konsultasi', $id)
            ->where('id_konsultan', $psychologistId)
            ->where('status', $expectedStatus)
            ->update([
                'status' => $newStatus,
            ]);

        if (!$updated || $db->affectedRows() !== 1) {
            return redirect()
                ->to($detailUrl)
                ->with(
                    'error',
                    'Gagal memperbarui status. Silakan muat ulang halaman.'
                );
        }

        return redirect()
            ->to($detailUrl)
            ->with('success', $successMessage);
    }
}
