<?php
namespace App\Controllers;

use App\Models\UsersModel;

class Library extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url', 'date']);
    }

    // =======================
    // HALAMAN UTAMA
    // =======================
    public function index()
    {
        $session = session();

        // kalau belum login → ke login
        if (!$session->get('isLogin')) {
            return redirect()->to('/login');
        }

        // cek role
        if ($session->get('role') === 'admin') {
            return view('admin_v');
        } else {
            return view('operator_v');
        }
    }

    // =======================
    // FORM LOGIN
    // =======================
    public function login()
    {
        return view('login_v');
    }

    // =======================
    // PROSES LOGIN
    // =======================
    public function dologin()
    {
        $session = session();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $usersModel = new UsersModel();
        $user = $usersModel->where('username', $username)->first();

        // validasi login + password hash
        if ($user && password_verify($password, $user['password'])) {

            $sessionData = [
                'isLogin'  => true,
                'username' => $user['username'],
                'role'     => $user['role']
            ];

            $session->set($sessionData);

            return redirect()->to('/library');
        }

        // kalau gagal
        $session->setFlashdata('error', 'Username atau password salah');
        return redirect()->to('/login');
    }

    // =======================
    // LOGOUT
    // =======================
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}