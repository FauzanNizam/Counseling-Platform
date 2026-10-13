<?php

namespace Modules\Client\Models;

use CodeIgniter\Model;

class KonsultasiModel extends Model
{
    protected $table            = 'konsultasi';
    protected $primaryKey       = 'id_konsultasi';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_klien',
        'id_konsultan',
        'id_jadwal',
        'tipe_sesi',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_menit',
        'status',
        'titik_jemput',
        'titik_tujuan',
        'link_meeting',
        'room_id',
        'platform',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
