<?php

namespace Modules\Auth\Controllers;

use App\Controllers\BaseController;
use Modules\Shared\Models\UserModel;

class Register extends BaseController
{
    public function index()
    {
        return view('Modules\Auth\Views\register');
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

        $data = [
            'nama_lengkap' => trim($this->request->getPost('name')),
            'email' => strtolower(trim($this->request->getPost('email'))),
            'password_hash' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role' => 'klien',
            'status_akun' => 'aktif',
        ];

        try {
            $result = $userModel->insert($data);

            if ($result === false) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $userModel->errors());
            }
        } catch (\Throwable $e) {
            log_message('error', 'Registrasi gagal: {message}', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'register' => 'Pendaftaran gagal. Silakan coba lagi.',
                ]);
        }

        return redirect()->to(site_url('login'))
            ->with('success', 'Pendaftaran berhasil. Silakan masuk.');
    }
}
