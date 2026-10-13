<?php

namespace Modules\Client\Models;

use CodeIgniter\Model;

class KonsultanModel extends Model
{
    protected $table            = 'profil_konsultan';
    protected $primaryKey       = 'id_profil';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    public function getKonsultanAktif(): array
    {
        return $this->select(
            'profil_konsultan.*, users.nama_lengkap, users.email, users.no_telepon'
        )
            ->join(
                'users',
                'users.id_user = profil_konsultan.id_user'
            )
            ->where('users.role', 'konsultan')
            ->where('users.status_akun', 'aktif')
            ->where('profil_konsultan.status_verifikasi', 'verified')
            ->orderBy('users.nama_lengkap', 'ASC')
            ->findAll();
    }



    public function detail($id = null)
    {
        if (!is_numeric($id) || (int) $id < 1) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $model = new \Modules\Client\Models\KonsultanModel();

        $konsultan = $model->getKonsultanById((int) $id);

        if (empty($konsultan)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('Modules\Client\Views\konsultan\detail', [
            'title' => 'Profil Konsultan',
            'item'  => $konsultan,
        ]);
    }

    public function getKonsultanById(int $id): ?array
    {
        return $this->select(
            'profil_konsultan.*, users.nama_lengkap, users.email, users.no_telepon'
        )
            ->join(
                'users',
                'users.id_user = profil_konsultan.id_user'
            )
            ->where('profil_konsultan.id_profil', $id)
            ->where('users.role', 'konsultan')
            ->where('users.status_akun', 'aktif')
            ->where('profil_konsultan.status_verifikasi', 'verified')
            ->first();
    }
}
