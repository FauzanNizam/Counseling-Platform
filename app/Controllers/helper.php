<?php

    namespace App\Controllers;

    use App\Controllers\BaseController;
    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Psr\Log\LoggerInterface;

    class Users extends BaseController
    {
        public function index()
        {
            helper('form'); // ← taruh di sini

            $usersModel = new \App\Models\UsersModel();
            $data['users'] = $usersModel->findAll();
            return view('users', $data);
        }
    }
