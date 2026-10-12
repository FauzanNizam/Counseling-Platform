<?php

namespace Modules\Psychologist\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('Modules\Psychologist\Views\dashboard');
    }
}