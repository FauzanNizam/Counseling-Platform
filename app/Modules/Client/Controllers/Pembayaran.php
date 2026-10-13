<?php

namespace Modules\Client\Controllers;

use App\Controllers\BaseController;
use Modules\Client\Models\KonsultasiModel;
use Modules\Client\Models\PembayaranModel;

class Pembayaran extends BaseController
{
    public function index($idKonsultasi = null)
    {
        $idKlien = (int) session()->get('user_id');

        if ($idKlien < 1 || session()->get('user_role') !== 'client') {
            return redirect()->to(site_url('login'));
        }

        if (!is_numeric($idKonsultasi) || (int) $idKonsultasi < 1) {
            return redirect()->to(site_url('client/jadwal'))
                ->with('error', 'ID konsultasi tidak valid.');
        }

        $konsultasiModel = new KonsultasiModel();
        $pembayaranModel = new PembayaranModel();

        $konsultasi = $konsultasiModel
            ->where('id_konsultasi', (int) $idKonsultasi)
            ->where('id_klien', $idKlien)
            ->first();

        if (!$konsultasi) {
            return redirect()->to(site_url('client/jadwal'))
                ->with('error', 'Data konsultasi tidak ditemukan.');
        }

        if ($konsultasi['status'] !== 'Dikonfirmasi') {
            return redirect()->to(site_url('client/jadwal'))
                ->with('error', 'Pembayaran hanya tersedia untuk konsultasi yang sudah dikonfirmasi.');
        }

        $jumlah = match ($konsultasi['tipe_sesi']) {
            'Online'  => 50000,
            'Pick-up' => 150000,
            default   => null,
        };

        if ($jumlah === null) {
            return redirect()->to(site_url('client/jadwal'))
                ->with('error', 'Tipe sesi konsultasi tidak valid.');
        }

        $pembayaran = $pembayaranModel
            ->where('id_konsultasi', (int) $idKonsultasi)
            ->first();

        return view('Modules\Client\Views\pembayaran\index', [
            'konsultasi' => $konsultasi,
            'pembayaran' => $pembayaran,
            'jumlah'     => $jumlah,
        ]);
    }

    public function proses($idKonsultasi = null)
    {
        $idKlien = (int) session()->get('user_id');

        if ($idKlien < 1 || session()->get('user_role') !== 'client') {
            return redirect()->to(site_url('login'));
        }

        if (!is_numeric($idKonsultasi) || (int) $idKonsultasi < 1) {
            return redirect()->to(site_url('client/jadwal'))
                ->with('error', 'ID konsultasi tidak valid.');
        }

        $idKonsultasi = (int) $idKonsultasi;

        $konsultasiModel = new KonsultasiModel();
        $pembayaranModel = new PembayaranModel();

        $konsultasi = $konsultasiModel
            ->where('id_konsultasi', $idKonsultasi)
            ->where('id_klien', $idKlien)
            ->first();

        if (!$konsultasi || $konsultasi['status'] !== 'Dikonfirmasi') {
            return redirect()->to(site_url('client/jadwal'))
                ->with('error', 'Konsultasi tidak tersedia untuk pembayaran.');
        }

        $jumlah = match ($konsultasi['tipe_sesi']) {
            'Online'  => 50000,
            'Pick-up' => 150000,
            default   => null,
        };

        if ($jumlah === null) {
            return redirect()->to(site_url('client/jadwal'))
                ->with('error', 'Tipe sesi konsultasi tidak valid.');
        }

        $metode = $this->request->getPost('metode');

        $metodeValid = [
            'GoPay',
            'OVO',
            'Transfer Bank',
            'Virtual Account',
        ];

        if (!in_array($metode, $metodeValid, true)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Metode pembayaran tidak valid.');
        }

        $pembayaranLama = $pembayaranModel
            ->where('id_konsultasi', $idKonsultasi)
            ->first();

        if ($pembayaranLama) {
            return redirect()->to(
                site_url('client/pembayaran/' . $idKonsultasi)
            )->with('error', 'Data pembayaran untuk konsultasi ini sudah tersedia.');
        }

        $kodeTransaksi = 'PAY-' . $idKonsultasi . '-' . strtoupper(bin2hex(random_bytes(4)));

        $berhasil = $pembayaranModel->insert([
            'id_konsultasi' => $idKonsultasi,
            'metode'        => $metode,
            'jumlah'        => $jumlah,
            'status_bayar'  => 'Pending',
            'kode_transaksi' => $kodeTransaksi,
        ]);

        if (!$berhasil) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data pembayaran gagal disimpan.');
        }

        return redirect()->to(
            site_url('client/pembayaran/' . $idKonsultasi)
        )->with('success', 'Data pembayaran berhasil dibuat. Silakan lanjutkan pembayaran sesuai instruksi.');
    }

    public function uploadBukti($idKonsultasi = null)
    {
        $idKlien = (int) session()->get('user_id');

        if ($idKlien < 1 || session()->get('user_role') !== 'client') {
            return redirect()->to(site_url('login'));
        }

        if (!is_numeric($idKonsultasi) || (int) $idKonsultasi < 1) {
            return redirect()->to(site_url('client/jadwal'))
                ->with('error', 'ID konsultasi tidak valid.');
        }

        $idKonsultasi = (int) $idKonsultasi;

        $konsultasiModel = new KonsultasiModel();
        $pembayaranModel = new PembayaranModel();

        $konsultasi = $konsultasiModel
            ->where('id_konsultasi', $idKonsultasi)
            ->where('id_klien', $idKlien)
            ->first();

        if (!$konsultasi || $konsultasi['status'] !== 'Dikonfirmasi') {
            return redirect()->to(site_url('client/jadwal'))
                ->with('error', 'Konsultasi tidak tersedia.');
        }

        $pembayaran = $pembayaranModel
            ->where('id_konsultasi', $idKonsultasi)
            ->first();

        if (!$pembayaran || $pembayaran['status_bayar'] !== 'Pending') {
            return redirect()->to(
                site_url('client/pembayaran/' . $idKonsultasi)
            )->with('error', 'Pembayaran tidak tersedia untuk unggah bukti.');
        }

        $file = $this->request->getFile('bukti_bayar');

        if (!$file || !$file->isValid()) {
            return redirect()->back()
                ->with('error', 'Pilih file bukti pembayaran yang valid.');
        }

        $validasi = [
            'bukti_bayar' => [
                'label' => 'Bukti pembayaran',
                'rules' => [
                    'uploaded[bukti_bayar]',
                    'max_size[bukti_bayar,2048]',
                    'is_image[bukti_bayar]',
                    'mime_in[bukti_bayar,image/jpg,image/jpeg,image/png,image/webp]',
                ],
            ],
        ];

        if (!$this->validate($validasi)) {
            return redirect()->back()
                ->with('error', $this->validator->getError('bukti_bayar'));
        }

        $folder = WRITEPATH . 'uploads/bukti_pembayaran';

        if (!is_dir($folder) && !mkdir($folder, 0750, true)) {
            return redirect()->back()
                ->with('error', 'Folder penyimpanan bukti tidak dapat dibuat.');
        }

        $namaFile = $file->getRandomName();

        if (!$file->move($folder, $namaFile)) {
            return redirect()->back()
                ->with('error', 'Bukti pembayaran gagal diunggah.');
        }

        $berhasil = $pembayaranModel->update(
            $pembayaran['id_pembayaran'],
            ['bukti_bayar' => $namaFile]
        );

        if (!$berhasil) {
            if (is_file($folder . DIRECTORY_SEPARATOR . $namaFile)) {
                unlink($folder . DIRECTORY_SEPARATOR . $namaFile);
            }

            return redirect()->back()
                ->with('error', 'Data bukti pembayaran gagal disimpan.');
        }

        return redirect()->to(
            site_url('client/pembayaran/' . $idKonsultasi)
        )->with(
            'success',
            'Bukti pembayaran berhasil diunggah. Status tetap Pending sampai diverifikasi admin.'
        );
    }
}
