<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        $data = [
            'totalUsers' => $userModel->countAllResults(),
            'totalPsychologists' => $userModel
                ->where('role', 'psychologist')
                ->countAllResults(),
        ];

        return view('admin/dashboard', $data);
    }
}
