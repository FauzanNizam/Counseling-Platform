<?php

namespace Modules\Shared\Models;

use CodeIgniter\Model;

class ConsultantProfileModel extends Model
{
    protected $table = 'profil_konsultan';
    protected $primaryKey = 'id_profil';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_user', 'no_str', 'spesialisasi', 'pengalaman_tahun', 'status_verifikasi',
    ];
    protected $useTimestamps = false;
}
