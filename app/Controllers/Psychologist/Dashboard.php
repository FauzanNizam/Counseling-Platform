<?php

namespace App\Controllers\Psychologist;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('psychologist/dashboard');
    }
}
