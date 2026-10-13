<?php

namespace Modules\Client\Controllers;

use App\Controllers\BaseController;
use Modules\Client\Models\JadwalPraktikModel;
use Modules\Client\Models\KonsultasiModel;

class Konsultasi extends BaseController
{

    public function create($idProfil = null)
    {
        $jadwalModel = new JadwalPraktikModel();
        $konsultanModel = new \Modules\Client\Models\KonsultanModel();

        $konsultan = null;
        $jadwal = $jadwalModel->getJadwalAktif();

        if ($idProfil !== null) {
            $konsultan = $konsultanModel->getKonsultanById((int) $idProfil);

            if (!$konsultan) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }

            $jadwal = array_values(array_filter($jadwal, function ($item) use ($konsultan) {
                return (int) $item['id_konsultan'] === (int) $konsultan['id_user'];
            }));
        }

        return view('Modules\Client\Views\konsultasi\create', [
            'title' => 'Buat Janji Konseling',
            'jadwal' => $jadwal,
            'konsultan' => $konsultan,
        ]);
    }


    public function store()
    {
        $session = session();

        $idKlien = (int) $session->get('user_id');

        if ($idKlien < 1 || $session->get('user_role') !== 'client') {
            return redirect()->to(site_url('login'));
        }

        $idJadwal = (int) $this->request->getPost('id_jadwal');
        $tanggal  = $this->request->getPost('tanggal');
        $tipeSesi = $this->request->getPost('tipe_sesi');

        $jadwalModel = new JadwalPraktikModel();
        $konsultasiModel = new KonsultasiModel();

        $jadwal = $jadwalModel->getJadwalById($idJadwal);

        if (!$jadwal) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jadwal praktik tidak tersedia.');
        }

        if (!in_array($tipeSesi, ['Online', 'Pick-up'], true)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tipe sesi tidak valid.');
        }

        $tanggalObj = \DateTime::createFromFormat('!Y-m-d', (string) $tanggal);
        $hariTanggal = $tanggalObj
            ? $tanggalObj->format('l')
            : '';

        $hariIndonesia = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        if (
            !$tanggalObj ||
            $tanggalObj->format('Y-m-d') !== $tanggal ||
            $tanggalObj->format('Y-m-d') < date('Y-m-d') ||
            ($hariIndonesia[$hariTanggal] ?? '') !== $jadwal['hari']
        ) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tanggal harus sesuai hari praktik dan tidak boleh di masa lalu.');
        }

        $db = \Config\Database::connect();

        $db->transStart();

        $jadwalTerkunci = $db->query(
            'SELECT * FROM jadwal_praktik WHERE id_jadwal = ? AND is_aktif = 1 FOR UPDATE',
            [$idJadwal]
        )->getRowArray();

        if (!$jadwalTerkunci) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Jadwal tidak lagi tersedia.');
        }


        $idKlienSudahMengajukan = $db->table('konsultasi')
            ->where('id_klien', $idKlien)
            ->where('id_jadwal', $idJadwal)
            ->where('tanggal', $tanggal)
            ->whereNotIn('status', ['Dibatalkan', 'Ditolak'])
            ->countAllResults();

        if ($idKlienSudahMengajukan > 0) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Kamu sudah mengajukan konseling pada jadwal dan tanggal tersebut.');
        }

        $jumlahPesanan = $db->table('konsultasi')
            ->where('id_jadwal', $idJadwal)
            ->where('tanggal', $tanggal)
            ->whereNotIn('status', ['Dibatalkan', 'Ditolak'])
            ->countAllResults();


        if ($jumlahPesanan >= (int) $jadwalTerkunci['kuota']) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Kuota jadwal pada tanggal tersebut sudah penuh.');
        }

        $data = [
            'id_klien'      => $idKlien,
            'id_konsultan'  => (int) $jadwalTerkunci['id_konsultan'],
            'id_jadwal'     => $idJadwal,
            'tipe_sesi'     => $tipeSesi,
            'tanggal'       => $tanggal,
            'waktu_mulai'   => $jadwalTerkunci['jam_mulai'],
            'waktu_selesai' => $jadwalTerkunci['jam_selesai'],
            'durasi_menit'  => 60,
            'status'        => 'Menunggu Konfirmasi',
        ];

        $konsultasiModel->insert($data);

        if ($konsultasiModel->errors()) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Pengajuan belum berhasil disimpan.');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan pengajuan.');
        }

        return redirect()->to(site_url('client/jadwal'))
            ->with('success', 'Pengajuan janji konseling berhasil dikirim.');
    }
}
