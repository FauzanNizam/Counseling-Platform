<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('tampilan');
    }

    public function catalog()
    {
        return view('catalog');
    }
}
