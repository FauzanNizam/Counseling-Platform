<?php

namespace Modules\Client\Controllers;

use App\Controllers\BaseController;
use Modules\Client\Models\RiwayatKonsultasiModel;

class Jadwal extends BaseController
{
    public function index()
    {
        $session = session();
        $idKlien = (int) $session->get('user_id');

        if ($idKlien < 1 || $session->get('user_role') !== 'client') {
            return redirect()->to(site_url('login'));
        }

        $model = new RiwayatKonsultasiModel();

        return view('Modules\Client\Views\jadwal\index', [
            'title' => 'Jadwal Saya',
            'konsultasi' => $model->getByKlien($idKlien),
        ]);
    }

    public function batalkan($id = null)
    {
        $session = session();
        $idKlien = (int) $session->get('user_id');

        if ($idKlien < 1 || $session->get('user_role') !== 'client') {
            return redirect()->to(site_url('login'));
        }

        if (!is_numeric($id) || (int) $id < 1) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $db = \Config\Database::connect();

        $pengajuan = $db->table('konsultasi')
            ->where('id_konsultasi', (int) $id)
            ->where('id_klien', $idKlien)
            ->get()
            ->getRowArray();

        if (!$pengajuan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($pengajuan['status'] !== 'Menunggu Konfirmasi') {
            return redirect()->to(site_url('client/jadwal'))
                ->with('error', 'Pengajuan ini tidak dapat dibatalkan karena statusnya sudah berubah.');
        }

        $db->table('konsultasi')
            ->where('id_konsultasi', (int) $id)
            ->where('id_klien', $idKlien)
            ->where('status', 'Menunggu Konfirmasi')
            ->update(['status' => 'Dibatalkan']);

        if ($db->affectedRows() !== 1) {
            return redirect()->to(site_url('client/jadwal'))
                ->with('error', 'Pembatalan gagal. Silakan muat ulang halaman dan coba lagi.');
        }

        return redirect()->to(site_url('client/jadwal'))
            ->with('success', 'Pengajuan konseling berhasil dibatalkan.');
    }
}
