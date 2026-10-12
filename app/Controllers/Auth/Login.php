<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function authenticate()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('login'))
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim($this->request->getPost('email')));
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->to(site_url('login'))
                ->withInput()
                ->with('login_error', 'Email atau kata sandi salah.');
        }

        session()->regenerate();

        session()->set([
            'user_id'   => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_role'  => $user['role'],
            'isLoggedIn' => true,
        ]);


        switch ($user['role']) {
            case 'admin':
                return redirect()->to(site_url('admin'));

            case 'psychologist':
                return redirect()->to(site_url('psychologist'));

            case 'client':
            default:
                return redirect()->to(site_url('client'));
        }
    }


    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'))
            ->with('success', 'Kamu berhasil keluar dari akun.');
    }
}
