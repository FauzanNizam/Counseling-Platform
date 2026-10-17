<?php

namespace Modules\Psychologist\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use Modules\Shared\Models\UserModel;
use Modules\Shared\Models\ConsultantProfileModel;

class Profile extends BaseController
{
    protected UserModel $userModel;
    protected ConsultantProfileModel $consultantProfileModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->consultantProfileModel = new ConsultantProfileModel();
    }

    // ==========================================
    // AUTENTIKASI PSIKOLOG
    // ==========================================

    private function checkAuth()
    {
        $session = session();

        if ($session->get('isLoggedIn') !== true) {
            return redirect()->to(site_url('login'));
        }

        if ($session->get('user_role') !== 'psychologist') {
            return redirect()
                ->to(site_url('login'))
                ->with('error', 'Akses hanya diizinkan untuk psikolog.');
        }

        $userId = $session->get('user_id');

        if (
            !$userId ||
            !ctype_digit((string) $userId) ||
            (int) $userId <= 0
        ) {
            $session->destroy();

            return redirect()->to(site_url('login'));
        }

        return null;
    }

    // ==========================================
    // TAMPILKAN PROFIL
    // ==========================================

    public function index()
    {
        if ($redirect = $this->checkAuth()) {
            return $redirect;
        }

        $userId = (int) session()->get('user_id');

        $user = $this->userModel->find($userId);

        if (!$user) {
            session()->destroy();

            return redirect()
                ->to(site_url('login'))
                ->with('error', 'Akun tidak ditemukan dalam sistem.');
        }

        // PERBAIKAN:
        // Tidak lagi menggunakan getByUserId()
        $profile = $this->consultantProfileModel
            ->where('id_user', $userId)
            ->first();

        if (!$profile) {
            $profile = [
                'id_profil' => null,
                'id_user' => $userId,
                'no_str' => '',
                'spesialisasi' => '',
                'pengalaman_tahun' => 0,
                'status_verifikasi' => 'pending',
            ];
        }

        return view(
            'Modules\Psychologist\Views\profile\index',
            [
                'title' => 'Profil Psikolog - Counseling Platform',
                'user' => $user,
                'profile' => $profile,
            ]
        );
    }

    // ==========================================
    // UPDATE PROFIL PSIKOLOG
    // ==========================================

    public function update()
    {
        if ($redirect = $this->checkAuth()) {
            return $redirect;
        }

        if (!$this->request->is('post')) {
            throw PageNotFoundException::forPageNotFound();
        }

        $userId = (int) session()->get('user_id');

        // ======================================
        // AMBIL INPUT
        // ======================================

        $nama = trim(
            (string) $this->request->getPost('nama_lengkap')
        );

        $email = strtolower(trim(
            (string) $this->request->getPost('email')
        ));

        $telepon = trim(
            (string) $this->request->getPost('no_telepon')
        );

        $alamat = trim(
            (string) $this->request->getPost('alamat')
        );

        $noStr = trim(
            (string) $this->request->getPost('no_str')
        );

        $spesialisasi = trim(
            (string) $this->request->getPost('spesialisasi')
        );

        $pengalaman = trim(
            (string) $this->request->getPost('pengalaman_tahun')
        );

        // ======================================
        // VALIDASI INPUT
        // ======================================

        $rules = [
            'nama_lengkap' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama lengkap wajib diisi.',
                    'min_length' => 'Nama lengkap minimal 3 karakter.',
                    'max_length' => 'Nama lengkap maksimal 100 karakter.',
                ],
            ],

            'email' => [
                'rules' => 'required|valid_email|max_length[150]',
                'errors' => [
                    'required' => 'Alamat email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'max_length' => 'Email maksimal 150 karakter.',
                ],
            ],

            'no_telepon' => [
                'rules' => 'permit_empty|max_length[20]',
                'errors' => [
                    'max_length' => 'Nomor telepon maksimal 20 karakter.',
                ],
            ],

            'alamat' => [
                'rules' => 'permit_empty',
            ],

            'no_str' => [
                'rules' => 'permit_empty|max_length[50]',
                'errors' => [
                    'max_length' => 'Nomor STR maksimal 50 karakter.',
                ],
            ],

            'spesialisasi' => [
                'rules' => 'permit_empty|max_length[100]',
                'errors' => [
                    'max_length' => 'Spesialisasi maksimal 100 karakter.',
                ],
            ],

            'pengalaman_tahun' => [
                'rules' => 'permit_empty|is_natural',
                'errors' => [
                    'is_natural' => 'Pengalaman harus berupa angka bulat positif atau 0.',
                ],
            ],
        ];

        $input = [
            'nama_lengkap' => $nama,
            'email' => $email,
            'no_telepon' => $telepon,
            'alamat' => $alamat,
            'no_str' => $noStr,
            'spesialisasi' => $spesialisasi,
            'pengalaman_tahun' => $pengalaman,
        ];

        if (!$this->validateData($input, $rules)) {
            return redirect()
                ->to(site_url('psychologist/profile'))
                ->withInput()
                ->with(
                    'error',
                    implode(' ', $this->validator->getErrors())
                );
        }

        // ======================================
        // CEK EMAIL DUPLIKAT
        // ======================================

        // Menggunakan query bawaan agar tidak
        // bergantung pada method custom findByEmail()
        $existingUser = $this->userModel
            ->where('email', $email)
            ->first();

        if (
            $existingUser &&
            (int) $existingUser['id_user'] !== $userId
        ) {
            return redirect()
                ->to(site_url('psychologist/profile'))
                ->withInput()
                ->with(
                    'error',
                    'Alamat email sudah digunakan oleh akun lain.'
                );
        }

        // ======================================
        // TRANSAKSI DATABASE
        // ======================================

        $db = db_connect();
        $db->transBegin();

        try {
            // ==================================
            // UPDATE INFORMASI AKUN
            // ==================================

            $userUpdated = $this->userModel->update(
                $userId,
                [
                    'nama_lengkap' => $nama,
                    'email' => $email,
                    'no_telepon' => $telepon !== ''
                        ? $telepon
                        : null,
                    'alamat' => $alamat !== ''
                        ? $alamat
                        : null,
                ]
            );

            if ($userUpdated === false) {
                throw new \RuntimeException(
                    'Gagal memperbarui data akun.'
                );
            }

            // ==================================
            // UPDATE INFORMASI PROFESIONAL
            // ==================================

            // PERBAIKAN:
            // Tidak lagi menggunakan getByUserId()
            $existingProfile = $this->consultantProfileModel
                ->where('id_user', $userId)
                ->first();

            $profileData = [
                'no_str' => $noStr !== ''
                    ? $noStr
                    : null,

                'spesialisasi' => $spesialisasi !== ''
                    ? $spesialisasi
                    : null,

                'pengalaman_tahun' => $pengalaman !== ''
                    ? (int) $pengalaman
                    : 0,
            ];

            if ($existingProfile) {
                // Update tanpa mengubah status verifikasi
                $profileUpdated = $this->consultantProfileModel
                    ->update(
                        (int) $existingProfile['id_profil'],
                        $profileData
                    );
            } else {
                // Buat profil jika belum tersedia
                $profileData['id_user'] = $userId;
                $profileData['status_verifikasi'] = 'pending';

                $profileUpdated = $this->consultantProfileModel
                    ->insert($profileData);
            }

            if ($profileUpdated === false) {
                throw new \RuntimeException(
                    'Gagal menyimpan data profesional.'
                );
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException(
                    'Transaksi database gagal.'
                );
            }

            $db->transCommit();

            // ==================================
            // PERBARUI SESSION
            // ==================================

            session()->set([
                'user_name' => $nama,
                'user_email' => $email,
            ]);

            return redirect()
                ->to(site_url('psychologist/profile'))
                ->with(
                    'success',
                    'Profil dan informasi profesional berhasil diperbarui.'
                );

        } catch (\Throwable $e) {
            $db->transRollback();

            log_message(
                'error',
                'Gagal memperbarui profil psikolog ID '
                . $userId . ': ' . $e->getMessage()
            );

            return redirect()
                ->to(site_url('psychologist/profile'))
                ->withInput()
                ->with(
                    'error',
                    'Gagal menyimpan perubahan profil. Silakan coba lagi.'
                );
        }
    }

    // ==========================================
    // UPDATE PASSWORD
    // ==========================================

    public function updatePassword()
    {
        if ($redirect = $this->checkAuth()) {
            return $redirect;
        }

        if (!$this->request->is('post')) {
            throw PageNotFoundException::forPageNotFound();
        }

        $userId = (int) session()->get('user_id');

        // ======================================
        // VALIDASI PASSWORD
        // ======================================

        $rules = [
            'current_password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password saat ini wajib diisi.',
                ],
            ],

            'new_password' => [
                'rules' => 'required|min_length[8]|max_length[72]',
                'errors' => [
                    'required' => 'Password baru wajib diisi.',
                    'min_length' => 'Password baru minimal 8 karakter.',
                    'max_length' => 'Password baru maksimal 72 karakter.',
                ],
            ],

            'new_password_confirm' => [
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches' => 'Konfirmasi password tidak cocok.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->to(site_url('psychologist/profile'))
                ->with(
                    'error',
                    implode(' ', $this->validator->getErrors())
                );
        }

        // ======================================
        // AMBIL DATA USER
        // ======================================

        $user = $this->userModel->find($userId);

        if (!$user) {
            session()->destroy();

            return redirect()->to(site_url('login'));
        }

        $currentPassword = (string) $this->request
            ->getPost('current_password');

        $newPassword = (string) $this->request
            ->getPost('new_password');

        $passwordHash = (string) (
            $user['password_hash'] ?? ''
        );

        // ======================================
        // VERIFIKASI PASSWORD LAMA
        // ======================================

        if (!password_verify($currentPassword, $passwordHash)) {
            return redirect()
                ->to(site_url('psychologist/profile'))
                ->with(
                    'error',
                    'Password saat ini salah.'
                );
        }

        // ======================================
        // CEGAH PASSWORD SAMA
        // ======================================

        if (password_verify($newPassword, $passwordHash)) {
            return redirect()
                ->to(site_url('psychologist/profile'))
                ->with(
                    'error',
                    'Password baru harus berbeda dari password lama.'
                );
        }

        // ======================================
        // HASH PASSWORD BARU
        // ======================================

        $newPasswordHash = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        // ======================================
        // SIMPAN PASSWORD
        // ======================================

        try {
            $saved = $this->userModel->update(
                $userId,
                [
                    'password_hash' => $newPasswordHash,
                ]
            );

            if ($saved === false) {
                throw new \RuntimeException(
                    'Gagal memperbarui password.'
                );
            }

            return redirect()
                ->to(site_url('psychologist/profile'))
                ->with(
                    'success',
                    'Password berhasil diperbarui.'
                );

        } catch (\Throwable $e) {
            log_message(
                'error',
                'Gagal memperbarui password psikolog ID '
                . $userId . ': ' . $e->getMessage()
            );

            return redirect()
                ->to(site_url('psychologist/profile'))
                ->with(
                    'error',
                    'Gagal memperbarui password. Silakan coba lagi.'
                );
        }
    }
}
