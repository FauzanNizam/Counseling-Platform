<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Models\UserModel;

class RoleFilter implements FilterInterface
{
    /**
     * Filter dijalankan sebelum controller.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // ==================================================
        // 1. PERIKSA STATUS LOGIN
        // ==================================================

        if (
            $session->get('isLoggedIn') !== true ||
            ! $session->get('user_id')
        ) {
            return redirect()
                ->to(site_url('login'))
                ->with(
                    'login_error',
                    'Silakan masuk terlebih dahulu.'
                );
        }

        // ==================================================
        // 2. AMBIL DATA USER TERBARU DARI DATABASE
        // ==================================================

        $userModel = new UserModel();

        $user = $userModel->find(
            $session->get('user_id')
        );

        // ==================================================
        // 3. PERIKSA KEBERADAAN DAN STATUS AKUN
        // ==================================================

        if (
            ! $user ||
            ($user['status_akun'] ?? null) !== 'aktif'
        ) {
            $session->destroy();

            return redirect()
                ->to(site_url('login'))
                ->with(
                    'login_error',
                    'Akun Anda tidak aktif atau sudah tidak tersedia.'
                );
        }

        // ==================================================
        // 4. KONVERSI ROLE DATABASE KE ROLE APLIKASI
        // ==================================================

        $roleMapping = [
            'administrator' => 'admin',
            'klien'         => 'client',
            'konsultan'     => 'psychologist',
        ];

        $databaseRole = $user['role'] ?? null;

        $actualRole = $roleMapping[$databaseRole] ?? null;

        // ==================================================
        // 5. PERIKSA APAKAH ROLE VALID
        // ==================================================

        if ($actualRole === null) {
            $session->destroy();

            return redirect()
                ->to(site_url('login'))
                ->with(
                    'login_error',
                    'Role akun tidak valid. Silakan hubungi administrator.'
                );
        }

        // ==================================================
        // 6. SINKRONKAN SESSION DENGAN DATABASE
        // ==================================================

        $session->set([
            'user_id'       => $user['id_user'],
            'user_name'     => $user['nama_lengkap'],
            'user_email'    => $user['email'],
            'database_role' => $databaseRole,
            'user_role'     => $actualRole,
            'isLoggedIn'    => true,
        ]);

        // ==================================================
        // 7. AMBIL ROLE YANG DIIZINKAN OLEH ROUTES
        // ==================================================

        // Contoh:
        // role:admin
        // role:client
        // role:psychologist

        $allowedRoles = $arguments ?? [];

        // ==================================================
        // 8. PERIKSA HAK AKSES PENGGUNA
        // ==================================================

        if (
            ! empty($allowedRoles) &&
            ! in_array($actualRole, $allowedRoles, true)
        ) {
            // Pengguna diarahkan ke dashboard role sendiri.
            $dashboardRoutes = [
                'admin'        => 'admin',
                'client'       => 'client',
                'psychologist' => 'psychologist',
            ];

            $dashboard = $dashboardRoutes[$actualRole];

            return redirect()
                ->to(site_url($dashboard))
                ->with(
                    'access_error',
                    'Kamu tidak memiliki izin untuk mengakses halaman tersebut.'
                );
        }

        // ==================================================
        // 9. IZINKAN REQUEST DILANJUTKAN
        // ==================================================

        return null;
    }

    /**
     * Filter dijalankan setelah controller.
     */
    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        // Tidak ada tindakan setelah request.
    }
}
