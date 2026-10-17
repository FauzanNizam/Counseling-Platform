<?php

namespace Modules\Client\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use Modules\Client\Models\JadwalPraktikModel;
use Modules\Client\Models\KonsultasiModel;
use Modules\Client\Models\KonsultanModel;

class Konsultasi extends BaseController
{
    /**
     * Menampilkan formulir booking konsultasi.
     */
    public function create($idProfil = null)
    {
        $jadwalModel = new JadwalPraktikModel();
        $konsultanModel = new KonsultanModel();

        $konsultan = null;
        $jadwal = $jadwalModel->getJadwalAktif();

        if ($idProfil !== null) {
            $konsultan = $konsultanModel->getKonsultanById(
                (int) $idProfil
            );

            if (!$konsultan) {
                throw PageNotFoundException::forPageNotFound();
            }

            $jadwal = array_values(array_filter(
                $jadwal,
                static function ($item) use ($konsultan) {
                    return (int) $item['id_konsultan']
                        === (int) $konsultan['id_user'];
                }
            ));
        }

        return view(
            'Modules\Client\Views\konsultasi\create',
            [
                'title' => 'Buat Janji Konseling',
                'jadwal' => $jadwal,
                'konsultan' => $konsultan,
            ]
        );
    }

    /**
     * Menyimpan pengajuan konsultasi.
     */
    public function store()
    {
        // Hanya menerima request POST.
        if (!$this->request->is('post')) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Validasi login dan role Client.
        $session = session();
        $idKlien = (int) $session->get('user_id');

        if (
            $idKlien < 1 ||
            $session->get('user_role') !== 'client' ||
            $session->get('isLoggedIn') !== true
        ) {
            return redirect()->to(site_url('login'));
        }

        // Mengambil data formulir.
        $idJadwal = (int) $this->request->getPost('id_jadwal');
        $tanggal = (string) $this->request->getPost('tanggal');
        $tipeSesi = (string) $this->request->getPost('tipe_sesi');

        $jadwalModel = new JadwalPraktikModel();
        $konsultasiModel = new KonsultasiModel();

        // Memastikan jadwal tersedia.
        $jadwal = $jadwalModel->getJadwalById($idJadwal);

        if (!$jadwal) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Jadwal praktik tidak tersedia.'
                );
        }

        // Validasi tipe konsultasi.
        if (!in_array($tipeSesi, ['Online', 'Pick-up'], true)) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Tipe sesi tidak valid.'
                );
        }

        // Zona waktu WITA.
        $timezone = new \DateTimeZone('Asia/Makassar');

        $today = (
            new \DateTimeImmutable('now', $timezone)
        )->format('Y-m-d');

        $tanggalObj = \DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $tanggal,
            $timezone
        );

        $hariTanggal = $tanggalObj
            ? $tanggalObj->format('l')
            : '';

        $hariIndonesia = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        // Memastikan tanggal sesuai hari praktik.
        if (
            !$tanggalObj ||
            $tanggalObj->format('Y-m-d') !== $tanggal ||
            $tanggal < $today ||
            ($hariIndonesia[$hariTanggal] ?? '') !== $jadwal['hari']
        ) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Tanggal harus sesuai hari praktik dan tidak boleh di masa lalu.'
                );
        }

        /*
         * LOKASI PICK-UP COUNSELING
         *
         * Tidak menggunakan peta maupun koordinat.
         * Cukup alamat lengkap dalam bentuk teks.
         */

        $lokasi = [
            'titik_jemput' => null,
            'titik_tujuan' => null,
        ];

        if ($tipeSesi === 'Pick-up') {
            $titikJemput = trim(
                (string) $this->request->getPost('titik_jemput')
            );

            $titikTujuan = trim(
                (string) $this->request->getPost('titik_tujuan')
            );

            // Alamat wajib diisi.
            if (
                $titikJemput === '' ||
                $titikTujuan === ''
            ) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Titik jemput dan titik tujuan wajib diisi.'
                    );
            }

            // Batasi panjang alamat.
            if (
                mb_strlen($titikJemput) > 500 ||
                mb_strlen($titikTujuan) > 500
            ) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Alamat titik jemput dan tujuan maksimal 500 karakter.'
                    );
            }

            $lokasi['titik_jemput'] = $titikJemput;
            $lokasi['titik_tujuan'] = $titikTujuan;
        }

        /*
         * PENYIMPANAN KONSULTASI
         *
         * Menggunakan transaksi database untuk
         * mencegah bentrok kuota booking.
         */

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Mengunci jadwal selama proses booking.
            $jadwalTerkunci = $db->query(
                'SELECT * FROM jadwal_praktik
                 WHERE id_jadwal = ?
                 AND is_aktif = 1
                 FOR UPDATE',
                [$idJadwal]
            )->getRowArray();

            if (!$jadwalTerkunci) {
                $db->transRollback();

                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Jadwal tidak lagi tersedia.'
                    );
            }

            // Mencegah klien melakukan booking ganda.
            $idKlienSudahMengajukan = $db->table('konsultasi')
                ->where('id_klien', $idKlien)
                ->where('id_jadwal', $idJadwal)
                ->where('tanggal', $tanggal)
                ->whereNotIn(
                    'status',
                    ['Dibatalkan', 'Ditolak']
                )
                ->countAllResults();

            if ($idKlienSudahMengajukan > 0) {
                $db->transRollback();

                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Kamu sudah mengajukan konseling pada jadwal dan tanggal tersebut.'
                    );
            }

            // Menghitung jumlah pemesanan aktif.
            $jumlahPesanan = $db->table('konsultasi')
                ->where('id_jadwal', $idJadwal)
                ->where('tanggal', $tanggal)
                ->whereNotIn(
                    'status',
                    ['Dibatalkan', 'Ditolak']
                )
                ->countAllResults();

            // Memastikan kuota belum penuh.
            if (
                $jumlahPesanan >= (int) $jadwalTerkunci['kuota']
            ) {
                $db->transRollback();

                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Kuota jadwal pada tanggal tersebut sudah penuh.'
                    );
            }

            // Data utama konsultasi.
            $data = [
                'id_klien' => $idKlien,
                'id_konsultan' => (int) $jadwalTerkunci['id_konsultan'],
                'id_jadwal' => $idJadwal,
                'tipe_sesi' => $tipeSesi,
                'tanggal' => $tanggal,
                'waktu_mulai' => $jadwalTerkunci['jam_mulai'],
                'waktu_selesai' => $jadwalTerkunci['jam_selesai'],
                'durasi_menit' => 60,
                'status' => 'Menunggu Konfirmasi',
            ];

            // Menambahkan alamat Pick-up ke data konsultasi.
            $data = array_merge($data, $lokasi);

            // Simpan ke database.
            $insertId = $konsultasiModel->insert($data);

            if ($insertId === false) {
                $db->transRollback();

                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Pengajuan belum berhasil disimpan.'
                    );
            }

            // Commit transaksi.
            if (!$db->transCommit()) {
                throw new \RuntimeException(
                    'Gagal melakukan commit transaksi.'
                );
            }

        } catch (\Throwable $e) {
            $db->transRollback();

            log_message(
                'error',
                'Gagal menyimpan konsultasi: {message}',
                ['message' => $e->getMessage()]
            );

            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menyimpan pengajuan.'
                );
        }

        // Pengajuan berhasil.
        return redirect()
            ->to(site_url('client/jadwal'))
            ->with(
                'success',
                'Pengajuan janji konseling berhasil dikirim.'
            );
    }
}
