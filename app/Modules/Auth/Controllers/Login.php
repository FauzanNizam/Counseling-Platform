<?php

namespace Modules\Auth\Controllers;

use App\Controllers\BaseController;
use Modules\Shared\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn') === true) {
            return $this->redirectByRole(
                session()->get('user_role')
            );
        }

        return view('Modules\Auth\Views\login');
    }

    public function authenticate()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
            'role' => 'required|in_list[client,psychologist,admin]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->to(site_url('login'))
                ->withInput()
                ->with(
                    'error',
                    'Silakan isi email, password, dan pilih role dengan benar.'
                );
        }

        $email = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');
        $selectedRole = (string) $this->request->getPost('role');

        $userModel = new UserModel();

        $user = $userModel
            ->where('email', $email)
            ->first();

        if (
            ! $user ||
            ! password_verify($password, $user['password_hash'])
        ) {
            return redirect()
                ->to(site_url('login'))
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        if ($user['status_akun'] !== 'aktif') {
            return redirect()
                ->to(site_url('login'))
                ->with(
                    'error',
                    'Akun Anda tidak aktif. Silakan hubungi administrator.'
                );
        }

        // Role asli tetap diambil dari database.
        $roleMapping = [
            'klien' => 'client',
            'konsultan' => 'psychologist',
            'administrator' => 'admin',
        ];

        $actualRole = $roleMapping[$user['role']] ?? null;

        // Pilihan pengguna harus cocok dengan role di database.
        if ($actualRole === null || $selectedRole !== $actualRole) {
            return redirect()
                ->to(site_url('login'))
                ->withInput()
                ->with(
                    'error',
                    'Role yang dipilih tidak sesuai dengan akun Anda.'
                );
        }

        // Buat session baru setelah autentikasi berhasil.
        session()->regenerate(true);

        session()->set([
            'user_id' => $user['id_user'],
            'user_name' => $user['nama_lengkap'],
            'user_email' => $user['email'],
            'database_role' => $user['role'],
            'user_role' => $actualRole,
            'isLoggedIn' => true,
        ]);

        return $this->redirectByRole($actualRole);
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'));
    }

    private function redirectByRole(?string $role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->to(site_url('admin'));

            case 'psychologist':
                return redirect()->to(site_url('psychologist'));

            case 'client':
                return redirect()->to(site_url('client'));

            default:
                session()->destroy();

                return redirect()->to(site_url('login'));
        }
    }
}
