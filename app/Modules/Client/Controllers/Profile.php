<?php

namespace Modules\Client\Controllers;

use App\Controllers\BaseController;
use Modules\Shared\Models\UserModel;

class Profile extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Pastikan pengguna sudah login
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Pastikan yang mengakses adalah client
        if (session()->get('user_role') !== 'client') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (! $user) {
            session()->destroy();
            return redirect()->to('/login')
                ->with('error', 'Data pengguna tidak ditemukan.');
        }

        return view('Modules\Client\Views\profile\index', [
            'user' => $user,
        ]);
    }


    public function update()
    {
        // Pastikan pengguna sudah login
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Pastikan yang mengakses adalah client
        if (session()->get('user_role') !== 'client') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        $nama = trim((string) $this->request->getPost('nama_lengkap'));
        $email = trim((string) $this->request->getPost('email'));
        $telepon = trim((string) $this->request->getPost('no_telepon'));
        $alamat = trim((string) $this->request->getPost('alamat'));

        // Validasi input
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[150]',
            'no_telepon' => 'permit_empty|max_length[20]',
            'alamat' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        // Pastikan email tidak digunakan pengguna lain
        $existingUser = $this->userModel->findByEmail($email);

        if ($existingUser && (int) $existingUser['id_user'] !== (int) $userId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email sudah digunakan oleh akun lain.');
        }

        // Simpan hanya field profil yang boleh diubah
        $saved = $this->userModel->update($userId, [
            'nama_lengkap' => $nama,
            'email' => $email,
            'no_telepon' => $telepon !== '' ? $telepon : null,
            'alamat' => $alamat !== '' ? $alamat : null,
        ]);

        if (! $saved) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Profil gagal diperbarui. Silakan coba lagi.');
        }

        // Perbarui data session
        session()->set([
            'user_name' => $nama,
            'user_email' => $email,
        ]);

        return redirect()->to('/client/profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
