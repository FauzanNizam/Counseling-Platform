<?php 
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Api extends ResourceController{
    public function __construct()
    {
        helper(['form', 'url','date']); 
    }
    public function index(){
        $usersModel = new \App\Models\UsersModel();
        return $this->respond($usersModel->findAll());
    }
    public function show($id = null){
        $usersModel = new \App\Models\UsersModel();
        $user = $usersModel->find($id);
        if ($user) {
            return $this->respond($user);
        } else {
            return $this->failNotFound('User not found');
        }
    }
    public function create(){
        $data = $this->request->getPost();
        $usersModel = new \App\Models\UsersModel();
        $usersModel->insert($data);
        return $this->respondCreated($data);
    }
    public function update($id = null){
        $data = $this->request->getRawInput();
        $usersModel = new \App\Models\UsersModel();
        $user = $usersModel->find($id);
        if ($user) { 
            $usersModel->update($id, $data);
            return $this->respond($data);
        } else {
            return $this->failNotFound('User not found');
        }   
    }
    public function delete($id = null){
        $usersModel = new \App\Models\UsersModel();
        $user = $usersModel->find($id);
        if ($user) {
            $usersModel->delete($id);
            return $this->respondDeleted('User deleted');
        } else {
            return $this->failNotFound('User not found');
        }
    }

}