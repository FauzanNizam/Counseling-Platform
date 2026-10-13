<?php

namespace Modules\Admin\Controllers;

use App\Controllers\BaseController;
use Modules\Shared\Models\UserModel;
use Modules\Shared\Models\ConsultantProfileModel;

class Consultants extends BaseController
{
    private function authorized(): bool
    {
        return session()->get('isLoggedIn') === true
            && session()->get('user_role') === 'admin'
            && ctype_digit((string) session()->get('user_id'));
    }

    private function deny()
    {
        return redirect()->to(site_url('login'))
            ->with('error', 'Silakan login sebagai administrator.');
    }

    private function validId($id): bool
    {
        return ctype_digit((string) $id) && (int) $id > 0;
    }

    private function nullable($value)
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /**
     * Mengambil akun konsultan beserta profil profesionalnya.
     * LEFT JOIN agar akun lama tanpa profil tetap bisa dibuka.
     */
    private function consultant($id): ?array
    {
        if (!$this->validId($id)) {
            return null;
        }

        $db = db_connect();

        $result = $db->table('users u')
            ->select(
                'u.id_user, u.nama_lengkap, u.email, ' .
                'u.no_telepon, u.alamat, u.role, ' .
                'u.status_akun, u.created_at, ' .
                'p.id_profil, p.no_str, p.spesialisasi, ' .
                'p.pengalaman_tahun, p.status_verifikasi'
            )
            ->join(
                'profil_konsultan p',
                'p.id_user = u.id_user',
                'left'
            )
            ->where('u.id_user', (int) $id)
            ->where('u.role', 'konsultan')
            ->get()
            ->getRowArray();

        return $result ?: null;
    }

    /**
     * F-22 - Daftar Konsultan
     */
    public function index()
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        $keyword = trim(
            (string) $this->request->getGet('keyword')
        );

        $status = (string) (
            $this->request->getGet('status')
            ?? $this->request->getGet('status_akun')
            ?? ''
        );

        $verification = (string) (
            $this->request->getGet('verification')
            ?? $this->request->getGet('status_verifikasi')
            ?? ''
        );

        if (!in_array(
            $status,
            ['aktif', 'nonaktif', 'ditangguhkan'],
            true
        )) {
            $status = '';
        }

        if (!in_array(
            $verification,
            ['pending', 'verified', 'rejected', 'missing'],
            true
        )) {
            $verification = '';
        }

        $db = db_connect();

        $query = $db->table('users u')
            ->select(
                'u.id_user, u.nama_lengkap, u.email, ' .
                'u.status_akun, p.id_profil, ' .
                'p.spesialisasi, p.status_verifikasi'
            )
            ->join(
                'profil_konsultan p',
                'p.id_user = u.id_user',
                'left'
            )
            ->where('u.role', 'konsultan');

        if ($keyword !== '') {
            $query->groupStart()
                ->like('u.nama_lengkap', $keyword)
                ->orLike('u.email', $keyword)
                ->groupEnd();
        }

        if ($status !== '') {
            $query->where('u.status_akun', $status);
        }

        if ($verification === 'missing') {
            $query->where(
                'p.id_profil IS NULL',
                null,
                false
            );
        } elseif ($verification !== '') {
            $query->where(
                'p.status_verifikasi',
                $verification
            );
        }

        $consultants = $query
            ->orderBy('u.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view(
            'Modules\Admin\Views\consultants\index',
            [
                'title' => 'Manajemen Konsultan - Counseling Platform',
                'consultants' => $consultants,
                'keyword' => $keyword,
                'selectedStatus' => $status,
                'selectedVerification' => $verification,
                'statusFilter' => $status,
                'verificationFilter' => $verification,
            ]
        );
    }

    /**
     * F-22 - Form Tambah Konsultan
     */
    public function create()
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        return view(
            'Modules\Admin\Views\consultants\create',
            [
                'title' => 'Tambah Konsultan - Counseling Platform',
            ]
        );
    }

    /**
     * F-22 - Simpan Konsultan Baru
     */
    public function store()
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        $input = $this->request->getPost();

        $input['nama_lengkap'] = trim(
            (string) ($input['nama_lengkap'] ?? '')
        );

        $input['email'] = strtolower(
            trim((string) ($input['email'] ?? ''))
        );

        $input['no_str'] = trim(
            (string) ($input['no_str'] ?? '')
        );

        $input['spesialisasi'] = trim(
            (string) ($input['spesialisasi'] ?? '')
        );

        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'password' => 'required|min_length[8]|max_length[72]',
            'password_confirm' => 'required|matches[password]',
            'no_telepon' => 'permit_empty|max_length[20]',
            'alamat' => 'permit_empty',
            'no_str' => 'permit_empty|max_length[50]',
            'spesialisasi' => 'permit_empty|max_length[100]',
            'pengalaman_tahun' => 'permit_empty|is_natural',
        ];

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'errors',
                    $this->validator->getErrors()
                );
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $users = new UserModel();

            $id = $users->insert([
                'nama_lengkap' => $input['nama_lengkap'],
                'email' => $input['email'],
                'password_hash' => password_hash(
                    (string) $input['password'],
                    PASSWORD_DEFAULT
                ),
                'no_telepon' => $this->nullable(
                    $input['no_telepon'] ?? null
                ),
                'alamat' => $this->nullable(
                    $input['alamat'] ?? null
                ),
                'role' => 'konsultan',
                'status_akun' => 'nonaktif',
            ], true);

            if (!$id) {
                throw new \RuntimeException(
                    'Gagal menyimpan akun konsultan.'
                );
            }

            $profiles = new ConsultantProfileModel();

            $saved = $profiles->insert([
                'id_user' => (int) $id,
                'no_str' => $this->nullable(
                    $input['no_str'] ?? null
                ),
                'spesialisasi' => $this->nullable(
                    $input['spesialisasi'] ?? null
                ),
                'pengalaman_tahun' => $this->nullable(
                    $input['pengalaman_tahun'] ?? null
                ),
                'status_verifikasi' => 'pending',
            ]);

            if (!$saved) {
                throw new \RuntimeException(
                    'Gagal menyimpan profil konsultan.'
                );
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException(
                    'Transaksi database gagal.'
                );
            }

            $db->transCommit();

            return redirect()
                ->to(site_url('admin/consultants'))
                ->with(
                    'success',
                    'Akun konsultan berhasil dibuat. ' .
                    'Status akun Nonaktif dan verifikasi Pending.'
                );

        } catch (\Throwable $e) {
            $db->transRollback();

            log_message(
                'error',
                'Gagal membuat konsultan: {message}',
                ['message' => $e->getMessage()]
            );

            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal membuat konsultan. ' .
                    'Periksa data dan coba lagi.'
                );
        }
    }

    /**
     * F-22 - Detail Konsultan
     */
    public function show($id)
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        $consultant = $this->consultant($id);

        if (!$consultant) {
            return redirect()
                ->to(site_url('admin/consultants'))
                ->with(
                    'error',
                    'Konsultan tidak ditemukan.'
                );
        }

        return view(
            'Modules\Admin\Views\consultants\show',
            [
                'title' => 'Detail Konsultan - Counseling Platform',
                'consultant' => $consultant,
            ]
        );
    }

    /**
     * F-22 - Form Edit Konsultan
     */
    public function edit($id)
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        $consultant = $this->consultant($id);

        if (!$consultant) {
            return redirect()
                ->to(site_url('admin/consultants'))
                ->with(
                    'error',
                    'Konsultan tidak ditemukan.'
                );
        }

        return view(
            'Modules\Admin\Views\consultants\edit',
            [
                'title' => 'Edit Konsultan - Counseling Platform',
                'consultant' => $consultant,
            ]
        );
    }

    /**
     * F-22 - Update Konsultan
     *
     * Profil yang belum ada akan dibuat otomatis
     * dengan status verifikasi Pending.
     */
    public function update($id)
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        $consultant = $this->consultant($id);

        if (!$consultant) {
            return redirect()
                ->to(site_url('admin/consultants'))
                ->with(
                    'error',
                    'Konsultan tidak ditemukan.'
                );
        }

        $input = $this->request->getPost();

        $input['nama_lengkap'] = trim(
            (string) ($input['nama_lengkap'] ?? '')
        );

        $input['email'] = strtolower(
            trim((string) ($input['email'] ?? ''))
        );

        $input['no_str'] = trim(
            (string) ($input['no_str'] ?? '')
        );

        $input['spesialisasi'] = trim(
            (string) ($input['spesialisasi'] ?? '')
        );

        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[150]|is_unique[users.email,id_user,' . (int) $id . ']',
            'no_telepon' => 'permit_empty|max_length[20]',
            'alamat' => 'permit_empty',
            'no_str' => 'permit_empty|max_length[50]',
            'spesialisasi' => 'permit_empty|max_length[100]',
            'pengalaman_tahun' => 'permit_empty|is_natural',
            'status_akun' => 'required|in_list[aktif,nonaktif,ditangguhkan]',
        ];

        $password = (string) (
            $input['password'] ?? ''
        );

        $passwordConfirm = (string) (
            $input['password_confirm'] ?? ''
        );

        if (
            $password !== ''
            || $passwordConfirm !== ''
        ) {
            $rules['password'] =
                'required|min_length[8]|max_length[72]';

            $rules['password_confirm'] =
                'required|matches[password]';
        }

        if (!$this->validateData($input, $rules)) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'errors',
                    $this->validator->getErrors()
                );
        }

        // Hanya konsultan Verified yang boleh Aktif.
        if (
            $input['status_akun'] === 'aktif'
            && ($consultant['status_verifikasi'] ?? null)
                !== 'verified'
        ) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Verifikasi konsultan terlebih dahulu ' .
                    'sebelum mengaktifkan akun.'
                );
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $userData = [
                'nama_lengkap' => $input['nama_lengkap'],
                'email' => $input['email'],
                'no_telepon' => $this->nullable(
                    $input['no_telepon'] ?? null
                ),
                'alamat' => $this->nullable(
                    $input['alamat'] ?? null
                ),
                'status_akun' => $input['status_akun'],
            ];

            if ($password !== '') {
                $userData['password_hash'] = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );
            }

            $users = new UserModel();

            if (
                $users->update(
                    (int) $id,
                    $userData
                ) === false
            ) {
                throw new \RuntimeException(
                    'Gagal memperbarui akun konsultan.'
                );
            }

            $profileData = [
                'no_str' => $this->nullable(
                    $input['no_str'] ?? null
                ),
                'spesialisasi' => $this->nullable(
                    $input['spesialisasi'] ?? null
                ),
                'pengalaman_tahun' => $this->nullable(
                    $input['pengalaman_tahun'] ?? null
                ),
            ];

            $profiles = new ConsultantProfileModel();

            if ($consultant['id_profil'] === null) {
                // Buat profil untuk akun konsultan lama.
                $profileData['id_user'] = (int) $id;
                $profileData['status_verifikasi'] =
                    'pending';

                if (!$profiles->insert($profileData)) {
                    throw new \RuntimeException(
                        'Gagal membuat profil konsultan.'
                    );
                }

            } else {
                // Perbarui profil yang sudah ada.
                if (
                    $profiles->update(
                        (int) $consultant['id_profil'],
                        $profileData
                    ) === false
                ) {
                    throw new \RuntimeException(
                        'Gagal memperbarui profil konsultan.'
                    );
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException(
                    'Transaksi database gagal.'
                );
            }

            $db->transCommit();

            return redirect()
                ->to(site_url('admin/consultants'))
                ->with(
                    'success',
                    'Data konsultan berhasil diperbarui.'
                );

        } catch (\Throwable $e) {
            $db->transRollback();

            log_message(
                'error',
                'Gagal edit konsultan: {message}',
                ['message' => $e->getMessage()]
            );

            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal memperbarui konsultan. ' .
                    'Silakan coba lagi.'
                );
        }
    }

    /**
     * F-22 - Verifikasi Manual
     *
     * Setelah berhasil, Admin diarahkan kembali
     * ke halaman Manajemen Konsultan.
     */
    public function verify($id)
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        $consultant = $this->consultant($id);

        if (
            !$consultant
            || $consultant['id_profil'] === null
        ) {
            return redirect()
                ->to(site_url('admin/consultants'))
                ->with(
                    'error',
                    'Profil konsultan tidak ditemukan. ' .
                    'Lengkapi profil melalui halaman Edit.'
                );
        }

        $decision = (string) (
            $this->request->getPost('decision')
        );

        if (!in_array(
            $decision,
            ['verified', 'rejected', 'pending'],
            true
        )) {
            return redirect()
                ->to(site_url('admin/consultants'))
                ->with(
                    'error',
                    'Pilihan verifikasi tidak valid.'
                );
        }

        // Data profesional harus lengkap sebelum Verified.
        if (
            $decision === 'verified'
            && (
                empty($consultant['no_str'])
                || empty($consultant['spesialisasi'])
                || $consultant['pengalaman_tahun'] === null
            )
        ) {
            return redirect()
                ->to(
                    site_url(
                        'admin/consultants/' . (int) $id
                    )
                )
                ->with(
                    'error',
                    'Lengkapi nomor STR, spesialisasi, ' .
                    'dan pengalaman sebelum verifikasi.'
                );
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $profiles = new ConsultantProfileModel();

            if (
                $profiles->update(
                    (int) $consultant['id_profil'],
                    [
                        'status_verifikasi' => $decision,
                    ]
                ) === false
            ) {
                throw new \RuntimeException(
                    'Gagal mengubah status verifikasi.'
                );
            }

            // Pending dan Rejected wajib Nonaktif.
            if ($decision !== 'verified') {
                $users = new UserModel();

                if (
                    $users->update(
                        (int) $id,
                        ['status_akun' => 'nonaktif']
                    ) === false
                ) {
                    throw new \RuntimeException(
                        'Gagal menonaktifkan akun konsultan.'
                    );
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException(
                    'Transaksi database gagal.'
                );
            }

            $db->transCommit();

            // ======================================
            // PERBAIKAN REDIRECT SETELAH VERIFIKASI
            // ======================================
            return redirect()
                ->to(site_url('admin/consultants'))
                ->with(
                    'success',
                    'Status verifikasi konsultan ' .
                    $consultant['nama_lengkap'] .
                    ' berhasil diubah menjadi ' .
                    $decision . '.'
                );

        } catch (\Throwable $e) {
            $db->transRollback();

            log_message(
                'error',
                'Gagal verifikasi konsultan: {message}',
                ['message' => $e->getMessage()]
            );

            return redirect()
                ->to(site_url('admin/consultants'))
                ->with(
                    'error',
                    'Gagal mengubah status verifikasi.'
                );
        }
    }

    /**
     * F-22 - Hapus Konsultan
     *
     * Akun dengan jadwal atau riwayat konsultasi
     * tidak boleh dihapus permanen.
     */
    public function delete($id)
    {
        if (!$this->authorized()) {
            return $this->deny();
        }

        $consultant = $this->consultant($id);

        if (!$consultant) {
            return redirect()
                ->to(site_url('admin/consultants'))
                ->with(
                    'error',
                    'Konsultan tidak ditemukan.'
                );
        }

        $db = db_connect();

        try {
            $hasSchedule = $db
                ->table('jadwal_praktik')
                ->where('id_konsultan', (int) $id)
                ->countAllResults() > 0;

            $hasConsultation = $db
                ->table('konsultasi')
                ->where('id_konsultan', (int) $id)
                ->countAllResults() > 0;

            if ($hasSchedule || $hasConsultation) {
                return redirect()
                    ->to(site_url('admin/consultants'))
                    ->with(
                        'error',
                        'Konsultan memiliki jadwal atau ' .
                        'riwayat layanan. Nonaktifkan akun, ' .
                        'jangan hapus permanen.'
                    );
            }

            $users = new UserModel();

            if (
                $users->delete(
                    (int) $id,
                    true
                ) === false
            ) {
                throw new \RuntimeException(
                    'Gagal menghapus akun konsultan.'
                );
            }

            return redirect()
                ->to(site_url('admin/consultants'))
                ->with(
                    'success',
                    'Akun konsultan berhasil dihapus permanen.'
                );

        } catch (\Throwable $e) {
            log_message(
                'error',
                'Gagal hapus konsultan: {message}',
                ['message' => $e->getMessage()]
            );

            return redirect()
                ->to(site_url('admin/consultants'))
                ->with(
                    'error',
                    'Akun konsultan tidak dapat dihapus.'
                );
        }
    }
}
