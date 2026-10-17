<?php

namespace Modules\Psychologist\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class Clients extends BaseController
{
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

    public function index()
    {
        $psychologistId = $this->psychologistId();

        if ($psychologistId <= 0) {
            return redirect()->to('/login');
        }

        $clients = db_connect()
            ->table('konsultasi k')
            ->select('
                u.id_user,
                u.nama_lengkap,
                u.email,
                COUNT(k.id_konsultasi) AS total_sesi,
                MAX(k.tanggal) AS tanggal_terakhir
            ')
            ->join('users u', 'u.id_user = k.id_klien')
            ->where('k.id_konsultan', $psychologistId)
            ->whereIn('k.status', [
                'Dikonfirmasi',
                'Berlangsung',
                'Selesai'
            ])
            ->groupBy([
                'u.id_user',
                'u.nama_lengkap',
                'u.email'
            ])
            ->orderBy('tanggal_terakhir', 'DESC')
            ->get()
            ->getResultArray();

        return view(
            'Modules\Psychologist\Views\clients\index',
            [
                'title' => 'Daftar Klien - Counseling',
                'clients' => $clients
            ]
        );
    }

    public function show($id = null)
    {
        $psychologistId = $this->psychologistId();

        if ($psychologistId <= 0) {
            return redirect()->to('/login');
        }

        $clientId = (int) $id;

        $client = db_connect()
            ->table('users u')
            ->select('
                u.id_user,
                u.nama_lengkap,
                u.email
            ')
            ->join(
                'konsultasi k',
                'k.id_klien = u.id_user'
            )
            ->where('u.id_user', $clientId)
            ->where('k.id_konsultan', $psychologistId)
            ->whereIn('k.status', [
                'Dikonfirmasi',
                'Berlangsung',
                'Selesai'
            ])
            ->groupBy([
                'u.id_user',
                'u.nama_lengkap',
                'u.email'
            ])
            ->get()
            ->getRowArray();

        if (!$client) {
            throw PageNotFoundException::forPageNotFound();
        }

        $consultations = db_connect()
            ->table('konsultasi')
            ->select('
                id_konsultasi,
                tanggal,
                waktu_mulai,
                tipe_sesi,
                status
            ')
            ->where('id_klien', $clientId)
            ->where('id_konsultan', $psychologistId)
            ->whereIn('status', [
                'Dikonfirmasi',
                'Berlangsung',
                'Selesai'
            ])
            ->orderBy('tanggal', 'DESC')
            ->orderBy('waktu_mulai', 'DESC')
            ->get()
            ->getResultArray();

        return view(
            'Modules\Psychologist\Views\clients\show',
            [
                'title' => 'Detail Klien - Counseling',
                'client' => $client,
                'consultations' => $consultations
            ]
        );
    }
}
