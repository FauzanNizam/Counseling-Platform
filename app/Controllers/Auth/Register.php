<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Register extends BaseController
{
    public function index()
    {
        return view('auth/register');
    }

    public function store()
    {
        $rules = [
            'name' => [
                'label' => 'Nama lengkap',
                'rules' => 'required|min_length[3]|max_length[100]',
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|max_length[150]|is_unique[users.email]',
            ],
            'password' => [
                'label' => 'Kata sandi',
                'rules' => 'required|min_length[8]',
            ],
            'confirmPassword' => [
                'label' => 'Konfirmasi kata sandi',
                'rules' => 'required|matches[password]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        $userModel->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => strtolower(trim($this->request->getPost('email'))),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role'     => 'client',
        ]);

        return redirect()->to(site_url('login'))
            ->with('success', 'Pendaftaran berhasil. Silakan masuk.');
    }
}
