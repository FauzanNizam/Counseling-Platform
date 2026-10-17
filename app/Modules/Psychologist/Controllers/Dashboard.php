<?php

namespace Modules\Psychologist\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();

        if (
            !$session->get('isLoggedIn') ||
            $session->get('user_role') !== 'psychologist'
        ) {
            return redirect()->to('/login');
        }

        $psychologistId = (int) $session->get('user_id');

        if ($psychologistId <= 0) {
            return redirect()->to('/login');
        }

        $db = db_connect();
        $today = date('Y-m-d');

        // Total konsultasi psikolog
        $totalKonsultasi = $db->table('konsultasi')
            ->where('id_konsultan', $psychologistId)
            ->countAllResults();

        // Konsultasi hari ini, kecuali dibatalkan
        $jadwalHariIni = $db->table('konsultasi')
            ->where('id_konsultan', $psychologistId)
            ->where('tanggal', $today)
            ->where('status !=', 'Dibatalkan')
            ->countAllResults();

        // Permintaan yang menunggu persetujuan
        $menungguKonfirmasi = $db->table('konsultasi')
            ->where('id_konsultan', $psychologistId)
            ->where('status', 'Menunggu Konfirmasi')
            ->countAllResults();

        // Daftar konsultasi mendatang
        $jadwalMendatang = $db->table('konsultasi k')
            ->select('
                k.id_konsultasi,
                k.tanggal,
                k.waktu_mulai,
                k.tipe_sesi,
                k.status,
                u.nama_lengkap AS nama_klien
            ')
            ->join('users u', 'u.id_user = k.id_klien')
            ->where('k.id_konsultan', $psychologistId)
            ->where('k.tanggal >=', $today)
            ->whereIn('k.status', [
                'Menunggu Konfirmasi',
                'Dikonfirmasi',
                'Berlangsung'
            ])
            ->orderBy('k.tanggal', 'ASC')
            ->orderBy('k.waktu_mulai', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return view('Modules\Psychologist\Views\dashboard', [
            'totalKonsultasi'    => $totalKonsultasi,
            'jadwalHariIni'      => $jadwalHariIni,
            'menungguKonfirmasi' => $menungguKonfirmasi,
            'jadwalMendatang'    => $jadwalMendatang,
        ]);
    }
}
