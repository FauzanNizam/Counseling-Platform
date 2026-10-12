<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Pastikan pengguna sudah login.
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'))
                ->with('login_error', 'Silakan masuk terlebih dahulu.');
        }

        // Ambil role yang diizinkan dari route.
        $allowedRoles = $arguments ?? [];

        // Periksa role pengguna.
        $userRole = session()->get('user_role');

        if (! in_array($userRole, $allowedRoles, true)) {
            return redirect()->to(site_url('client'))
                ->with('access_error', 'Kamu tidak memiliki akses ke halaman tersebut.');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        // Tidak ada tindakan setelah request.
    }
}
