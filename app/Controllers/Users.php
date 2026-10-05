<?php

namespace App\Controllers;


use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Users extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);


        $this->helpers = ['form', 'url'];
    }

    public function index(): string
    {
        $usersModel = new \App\Models\UsersModel();

        $data_parsing = [
            'users' => $usersModel->findAll()
        ];

        return view('user_v', [
            'users' => $usersModel->findAll()
        ]);
    }

    public function login(): string
    {
        $data = [];
        if (session()->has('error')) {
            $data['error'] = session()->getFlashdata('error');
        }
        return view('login_v', $data);
    }
   

    public function postlogin()
    {
        $usersModel = new \App\Models\UsersModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');


        $user = $usersModel->where('username', $username)->first();

        if ($user) {

            if ($user['password'] === $password) {

                return redirect()->to(base_url('home'));
            } else {

                return redirect()->to(base_url('login'))->with('error', 'Username atau password salah');
            }
        } else {

            return redirect()->to(base_url('login'))->with('error', 'Username atau password salah');
        }
    }
}
