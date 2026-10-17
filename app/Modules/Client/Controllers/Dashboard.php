<?php

namespace Modules\Client\Controllers;

use App\Controllers\BaseController;
use Modules\Client\Models\KonsultanModel;
use Modules\Client\Models\RiwayatKonsultasiModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $idKlien = (int) session()->get('user_id');

        $konsultanModel = new KonsultanModel();
        $riwayatModel = new RiwayatKonsultasiModel();

        // Ambil daftar psikolog dari database.
        $konsultan = $konsultanModel->getKonsultanAktif();
        $jumlahKonsultan = count($konsultan);
        // Ambil jadwal konsultasi milik klien.
        $jadwal = $idKlien > 0
            ? $riwayatModel->getByKlien($idKlien)
            : [];

        // Cari jadwal konsultasi terdekat yang belum dibatalkan.
        $jadwalBerikutnya = null;

        foreach ($jadwal as $item) {
            if (
                $item['status'] !== 'Dibatalkan'
                && strtotime($item['tanggal']) >= strtotime(date('Y-m-d'))
            ) {
                $waktuItem = strtotime(
                    $item['tanggal'] . ' ' . $item['waktu_mulai']
                );

                $waktuBerikutnya = $jadwalBerikutnya !== null
                    ? strtotime(
                        $jadwalBerikutnya['tanggal'] . ' ' .
                            $jadwalBerikutnya['waktu_mulai']
                    )
                    : null;

                if (
                    $jadwalBerikutnya === null
                    || $waktuItem < $waktuBerikutnya
                ) {
                    $jadwalBerikutnya = $item;
                }
            }
        }

        return view('Modules\Client\Views\dashboard', [
            'konsultan' => $konsultan,
            'jumlahKonsultan' => $jumlahKonsultan,
            'jadwalBerikutnya' => $jadwalBerikutnya,
        ]);
    }
}
