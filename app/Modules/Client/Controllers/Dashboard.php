<?php

namespace Modules\Client\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('Modules\Client\Views\dashboard');
    }
}
