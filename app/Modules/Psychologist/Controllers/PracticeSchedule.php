<?php

namespace Modules\Psychologist\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;

class PracticeSchedule extends BaseController
{
    private function psychologistId(): ?int
    {
        if (
            !session()->get('isLoggedIn') ||
            session()->get('user_role') !== 'psychologist'
        ) {
            return null;
        }

        $id = (int) session()->get('user_id');

        return $id > 0 ? $id : null;
    }

    private function scheduleTable()
    {
        return db_connect()->table('jadwal_praktik');
    }

    private function validDays(): array
    {
        return [
            'Senin', 'Selasa', 'Rabu',
            'Kamis', 'Jumat', 'Sabtu', 'Minggu'
        ];
    }

    private function normalizeTime(string $time): ?string
    {
        if (!preg_match(
            '/^([01]\d|2[0-3]):[0-5]\d$/',
            $time
        )) {
            return null;
        }

        return $time . ':00';
    }

    public function index()
    {
        $psychologistId = $this->psychologistId();

        if (!$psychologistId) {
            return redirect()->to(site_url('login'));
        }

        $schedules = $this->scheduleTable()
            ->where('id_konsultan', $psychologistId)
            ->orderBy(
                "FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')",
                '',
                false
            )
            ->orderBy('jam_mulai', 'ASC')
            ->get()
            ->getResultArray();

        return view(
            'Modules\Psychologist\Views\practice_schedule\index',
            [
                'title' => 'Jadwal Praktik',
                'schedules' => $schedules
            ]
        );
    }

    private function validateSchedule(): array
    {
        $hari = trim((string) $this->request->getPost('hari'));
        $jamMulai = $this->normalizeTime(
            trim((string) $this->request->getPost('jam_mulai'))
        );
        $jamSelesai = $this->normalizeTime(
            trim((string) $this->request->getPost('jam_selesai'))
        );

        $kuota = filter_var(
            $this->request->getPost('kuota'),
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1, 'max_range' => 100]]
        );

        if (!in_array($hari, $this->validDays(), true)) {
            return [null, 'Hari praktik tidak valid.'];
        }

        if (!$jamMulai || !$jamSelesai) {
            return [null, 'Format jam harus HH:MM.'];
        }

        if ($jamMulai >= $jamSelesai) {
            return [null, 'Jam selesai harus setelah jam mulai.'];
        }

        if ($kuota === false) {
            return [null, 'Kuota harus antara 1 sampai 100.'];
        }

        return [[
            'hari' => $hari,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'kuota' => $kuota
        ], null];
    }

    private function hasOverlap(
        int $psychologistId,
        array $data,
        ?int $excludeId = null
    ): bool {
        $builder = $this->scheduleTable()
            ->where('id_konsultan', $psychologistId)
            ->where('hari', $data['hari'])
            ->where('jam_mulai <', $data['jam_selesai'])
            ->where('jam_selesai >', $data['jam_mulai']);

        if ($excludeId !== null) {
            $builder->where('id_jadwal !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    private function findOwned(int $id, int $psychologistId): ?array
    {
        return $this->scheduleTable()
            ->where('id_jadwal', $id)
            ->where('id_konsultan', $psychologistId)
            ->get()
            ->getRowArray();
    }

    private function hasFutureBookings(
        int $scheduleId,
        int $psychologistId
    ): bool {
        return db_connect()->table('konsultasi')
            ->where('id_jadwal', $scheduleId)
            ->where('id_konsultan', $psychologistId)
            ->whereIn('status', [
                'Menunggu Konfirmasi',
                'Dikonfirmasi',
                'Berlangsung'
            ])
            ->groupStart()
                ->where('tanggal >=', date('Y-m-d'))
            ->groupEnd()
            ->countAllResults() > 0;
    }

    public function store(): RedirectResponse
    {
        $psychologistId = $this->psychologistId();

        if (!$psychologistId) {
            return redirect()->to(site_url('login'));
        }

        if (!$this->request->is('post')) {
            return redirect()->to(site_url('psychologist/practice-schedule'));
        }

        [$data, $error] = $this->validateSchedule();

        if ($error) {
            return redirect()->back()
                ->withInput()
                ->with('error', $error);
        }

        if ($this->hasOverlap($psychologistId, $data)) {
            return redirect()->back()
                ->with('error', 'Jadwal bertabrakan dengan jadwal praktik lain.');
        }

        $data['id_konsultan'] = $psychologistId;
        $data['is_aktif'] = 1;

        $this->scheduleTable()->insert($data);

        return redirect()->to(
            site_url('psychologist/practice-schedule')
        )->with('success', 'Jadwal praktik berhasil ditambahkan.');
    }

    public function update($id = null): RedirectResponse
    {
        $psychologistId = $this->psychologistId();
        $id = (int) $id;

        if (!$psychologistId) {
            return redirect()->to(site_url('login'));
        }

        if (!$this->request->is('post')) {
            return redirect()->to(site_url('psychologist/practice-schedule'));
        }

        $schedule = $this->findOwned($id, $psychologistId);

        if (!$schedule) {
            return redirect()->back()
                ->with('error', 'Jadwal tidak ditemukan.');
        }

        if ($this->hasFutureBookings($id, $psychologistId)) {
            return redirect()->back()->with(
                'error',
                'Jadwal memiliki konsultasi aktif atau mendatang. ' .
                'Perubahan jam/hari harus menunggu penanganan janji tersebut.'
            );
        }

        [$data, $error] = $this->validateSchedule();

        if ($error) {
            return redirect()->back()->with('error', $error);
        }

        if ($this->hasOverlap($psychologistId, $data, $id)) {
            return redirect()->back()
                ->with('error', 'Jadwal bertabrakan dengan jadwal praktik lain.');
        }

        $this->scheduleTable()
            ->where('id_jadwal', $id)
            ->where('id_konsultan', $psychologistId)
            ->update($data);

        return redirect()->to(
            site_url('psychologist/practice-schedule')
        )->with('success', 'Jadwal praktik berhasil diperbarui.');
    }

    public function toggle($id = null): RedirectResponse
    {
        $psychologistId = $this->psychologistId();
        $id = (int) $id;

        if (!$psychologistId) {
            return redirect()->to(site_url('login'));
        }

        if (!$this->request->is('post')) {
            return redirect()->to(site_url('psychologist/practice-schedule'));
        }

        $schedule = $this->findOwned($id, $psychologistId);

        if (!$schedule) {
            return redirect()->back()
                ->with('error', 'Jadwal tidak ditemukan.');
        }

        $newStatus = (int) $schedule['is_aktif'] === 1 ? 0 : 1;

        // Menonaktifkan jadwal tidak membatalkan konsultasi.
        // Janji yang sudah ada tetap harus ditangani.
        $this->scheduleTable()
            ->where('id_jadwal', $id)
            ->where('id_konsultan', $psychologistId)
            ->update(['is_aktif' => $newStatus]);

        $message = $newStatus === 1
            ? 'Jadwal praktik berhasil diaktifkan.'
            : 'Jadwal praktik berhasil dinonaktifkan. ' .
              'Konsultasi yang sudah terjadwal tetap berlaku.';

        return redirect()->to(
            site_url('psychologist/practice-schedule')
        )->with('success', $message);
    }
}
