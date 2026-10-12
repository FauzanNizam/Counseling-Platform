<?php

namespace Modules\Admin\Controllers;

use App\Controllers\BaseController;
use Modules\Shared\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        $data = [
            'totalUsers' => $userModel->countAllResults(),

            'totalPsychologists' => $userModel
                ->where('role', 'konsultan')
                ->countAllResults(),
        ];

        return view('Modules\Admin\Views\dashboard', $data);
    }
}
