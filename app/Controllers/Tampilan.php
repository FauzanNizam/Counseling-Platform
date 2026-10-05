<?php

namespace App\Controllers;

class Tampilan extends BaseController
{
    public function index(): string
    {
        return view('tampilan');
    }
}