<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'username';
    protected $returnType = 'array';
    protected $allowedFields = ['username', 'password', 'nama', 'role'];
}
