<?php

namespace Modules\Client\Models;

use CodeIgniter\Model;

class RiwayatKonsultasiModel extends Model
{
    protected $table = 'konsultasi';
    protected $primaryKey = 'id_konsultasi';
    protected $returnType = 'array';

    public function getByKlien(int $idKlien): array
    {
        return $this->select(
            'konsultasi.*, 
 users.nama_lengkap AS nama_konsultan, 
 users.no_telepon AS telepon_konsultan,
 jadwal_praktik.hari, 
 jadwal_praktik.jam_mulai, 
 jadwal_praktik.jam_selesai'
        )
            ->join(
                'users',
                'users.id_user = konsultasi.id_konsultan'
            )
            ->join(
                'jadwal_praktik',
                'jadwal_praktik.id_jadwal = konsultasi.id_jadwal'
            )
            ->where('konsultasi.id_klien', $idKlien)
            ->orderBy('konsultasi.tanggal', 'DESC')
            ->orderBy('konsultasi.waktu_mulai', 'DESC')
            ->findAll();
    }
}
