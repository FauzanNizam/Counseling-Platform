<?php

namespace Modules\Client\Models;

use CodeIgniter\Model;

class JadwalPraktikModel extends Model
{
    protected $table            = 'jadwal_praktik';
    protected $primaryKey       = 'id_jadwal';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    public function getJadwalAktif(): array
    {
        return $this->select(
                'jadwal_praktik.*, users.nama_lengkap'
            )
            ->join(
                'users',
                'users.id_user = jadwal_praktik.id_konsultan'
            )
            ->where('jadwal_praktik.is_aktif', 1)
            ->where('users.role', 'konsultan')
            ->where('users.status_akun', 'aktif')
            ->orderBy('jadwal_praktik.hari', 'ASC')
            ->orderBy('jadwal_praktik.jam_mulai', 'ASC')
            ->findAll();
    }

    public function getJadwalById(int $id): ?array
    {
        return $this->where('id_jadwal', $id)
            ->where('is_aktif', 1)
            ->first();
    }
}
