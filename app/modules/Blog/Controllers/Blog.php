<?php

namespace Modules\Auth\Blog\Controllers;

use App\Controllers\BaseController;

class Blog extends BaseController
{
    public function index()
    {
        return view('Modules\Blog\Views\index');
    }
}