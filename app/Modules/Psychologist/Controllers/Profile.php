<?php

namespace Modules\Psychologist\Controllers;

use App\Controllers\BaseController;
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

    /**
     * Memeriksa autentikasi dan otorisasi sesi konsultan.
     */
    private function checkAuth()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        if (session()->get('user_role') !== 'psychologist') {
            return redirect()->to(site_url('login'))
                ->with('error', 'Akses hanya diizinkan untuk konsultan/psikolog.');
        }

        $userId = session()->get('user_id');
        if (! $userId || ! ctype_digit((string) $userId)) {
            session()->destroy();
            return redirect()->to(site_url('login'));
        }

        return null;
    }

    /**
     * Menampilkan halaman profil psikolog/konsultan.
     */
    public function index()
    {
        if ($redirect = $this->checkAuth()) {
            return $redirect;
        }

        $userId = (int) session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (! $user) {
            session()->destroy();
            return redirect()->to(site_url('login'))
                ->with('error', 'Akun tidak ditemukan dalam sistem.');
        }

        $profile = $this->consultantProfileModel->getByUserId($userId);

        if (! $profile) {
            $profile = [
                'id_profil'         => null,
                'id_user'           => $userId,
                'no_str'            => '',
                'spesialisasi'      => '',
                'pengalaman_tahun'  => 0,
                'status_verifikasi' => 'pending',
            ];
        }

        return view('Modules\Psychologist\Views\profile\index', [
            'title'   => 'Profil Psikolog - Counseling Platform',
            'user'    => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Memperbarui informasi personal dan kredensial profesional psikolog.
     */
    public function update()
    {
        if ($redirect = $this->checkAuth()) {
            return $redirect;
        }

        $userId = (int) session()->get('user_id');

        $nama        = trim((string) $this->request->getPost('nama_lengkap'));
        $email       = strtolower(trim((string) $this->request->getPost('email')));
        $telepon     = trim((string) $this->request->getPost('no_telepon'));
        $alamat      = trim((string) $this->request->getPost('alamat'));
        $noStr       = trim((string) $this->request->getPost('no_str'));
        $spesialisasi = trim((string) $this->request->getPost('spesialisasi'));
        $pengalaman  = trim((string) $this->request->getPost('pengalaman_tahun'));

        $rules = [
            'nama_lengkap'     => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email|max_length[150]',
            'no_telepon'       => 'permit_empty|max_length[20]',
            'alamat'           => 'permit_empty',
            'no_str'           => 'permit_empty|max_length[50]',
            'spesialisasi'     => 'permit_empty|max_length[100]',
            'pengalaman_tahun' => 'permit_empty|is_natural',
        ];

        $messages = [
            'nama_lengkap' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
                'max_length' => 'Nama lengkap maksimal 100 karakter.',
            ],
            'email' => [
                'required'    => 'Alamat email wajib diisi.',
                'valid_email' => 'Format email yang dimasukkan tidak valid.',
            ],
            'no_telepon' => [
                'max_length' => 'Nomor telepon maksimal 20 digit.',
            ],
            'no_str' => [
                'max_length' => 'Nomor STR maksimal 50 karakter.',
            ],
            'spesialisasi' => [
                'max_length' => 'Spesialisasi maksimal 100 karakter.',
            ],
            'pengalaman_tahun' => [
                'is_natural' => 'Tahun pengalaman harus berupa angka bulat positif atau 0.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        // Pastikan email tidak dipakai oleh akun lain
        $existing = $this->userModel->findByEmail($email);
        if ($existing && (int) $existing['id_user'] !== $userId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Alamat email sudah digunakan oleh akun lain.');
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $userUpdated = $this->userModel->update($userId, [
                'nama_lengkap' => $nama,
                'email'        => $email,
                'no_telepon'   => $telepon !== '' ? $telepon : null,
                'alamat'       => $alamat !== '' ? $alamat : null,
            ]);

            if ($userUpdated === false) {
                throw new \RuntimeException('Gagal memperbarui data akun.');
            }

            $existingProfile = $this->consultantProfileModel->getByUserId($userId);

            $profileData = [
                'no_str'           => $noStr !== '' ? $noStr : null,
                'spesialisasi'     => $spesialisasi !== '' ? $spesialisasi : null,
                'pengalaman_tahun' => $pengalaman !== '' ? (int) $pengalaman : 0,
            ];

            if ($existingProfile) {
                // Jangan reset status_verifikasi jika sudah ada
                $profileUpdated = $this->consultantProfileModel->update(
                    (int) $existingProfile['id_profil'],
                    $profileData
                );
            } else {
                // Buat baru jika belum ada record di profil_konsultan
                $profileData['id_user'] = $userId;
                $profileData['status_verifikasi'] = 'pending';
                $profileUpdated = $this->consultantProfileModel->insert($profileData);
            }

            if ($profileUpdated === false) {
                throw new \RuntimeException('Gagal menyimpan data kredensial profesional.');
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi database dibatalkan.');
            }

            $db->transCommit();

            // Perbarui data nama dan email di session aktif
            session()->set([
                'user_name'  => $nama,
                'user_email' => $email,
            ]);

            return redirect()->to(site_url('psychologist/profile'))
                ->with('success', 'Profil dan kredensial profesional berhasil disimpan.');

        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan perubahan: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui kata sandi akun konsultan.
     */
    public function updatePassword()
    {
        if ($redirect = $this->checkAuth()) {
            return $redirect;
        }

        $userId = (int) session()->get('user_id');

        $rules = [
            'current_password'     => 'required',
            'new_password'         => 'required|min_length[8]|max_length[72]',
            'new_password_confirm' => 'required|matches[new_password]',
        ];

        $messages = [
            'current_password' => [
                'required' => 'Password saat ini wajib diisi.',
            ],
            'new_password' => [
                'required'   => 'Password baru wajib diisi.',
                'min_length' => 'Password baru minimal 8 karakter.',
                'max_length' => 'Password baru maksimal 72 karakter.',
            ],
            'new_password_confirm' => [
                'required' => 'Konfirmasi password baru wajib diisi.',
                'matches'  => 'Konfirmasi password tidak cocok dengan password baru.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $user = $this->userModel->find($userId);
        if (! $user) {
            session()->destroy();
            return redirect()->to(site_url('login'));
        }

        $currentPassword = (string) $this->request->getPost('current_password');
        $newPassword     = (string) $this->request->getPost('new_password');

        if (! password_verify($currentPassword, $user['password_hash'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Password saat ini yang Anda masukkan salah.');
        }

        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $saved = $this->userModel->update($userId, [
            'password_hash' => $newPasswordHash,
        ]);

        if (! $saved) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui password. Silakan coba lagi.');
        }

        return redirect()->to(site_url('psychologist/profile'))
            ->with('success', 'Password Anda berhasil diperbarui dengan aman.');
    }
}
