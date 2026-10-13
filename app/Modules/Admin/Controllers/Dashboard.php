<?php

namespace Modules\Admin\Controllers;

use App\Controllers\BaseController;
use Modules\Shared\Models\UserModel;

class Dashboard extends BaseController
{
    /**
     * Menampilkan Dashboard Administrator.
     */
    public function index()
    {
        $userModel = new UserModel();

        $data = [
            'title' => 'Dashboard Admin - Counseling Platform',

            // ========================================
            // STATISTIK BERDASARKAN ROLE
            // ========================================

            // Total seluruh pengguna
            'totalUsers' => $userModel
                ->countAllResults(),

            // Total klien
            'totalClients' => $userModel
                ->where('role', 'klien')
                ->countAllResults(),

            // Total psikolog / konsultan
            'totalPsychologists' => $userModel
                ->where('role', 'konsultan')
                ->countAllResults(),

            // Total administrator
            'totalAdmins' => $userModel
                ->where('role', 'administrator')
                ->countAllResults(),

            // ========================================
            // STATISTIK BERDASARKAN STATUS AKUN
            // ========================================

            // Total akun aktif
            'totalActive' => $userModel
                ->where('status_akun', 'aktif')
                ->countAllResults(),

            // Total akun nonaktif
            'totalInactive' => $userModel
                ->where('status_akun', 'nonaktif')
                ->countAllResults(),

            // Total akun ditangguhkan
            'totalSuspended' => $userModel
                ->where('status_akun', 'ditangguhkan')
                ->countAllResults(),
        ];

        return view('Modules\Admin\Views\dashboard', $data);
    }
}
