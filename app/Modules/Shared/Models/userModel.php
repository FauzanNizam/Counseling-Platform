<?php

namespace Modules\Shared\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    // ==================================================
    // KONFIGURASI TABEL
    // ==================================================

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    // ==================================================
    // FIELD YANG DIIZINKAN
    // ==================================================

    protected $allowedFields = [
        'nama_lengkap',
        'email',
        'password_hash',
        'no_telepon',
        'alamat',
        'role',
        'status_akun',
    ];

    // ==================================================
    // TIMESTAMPS
    // ==================================================

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // ==================================================
    // VALIDASI DATA
    // ==================================================

    protected $validationRules = [
        'nama_lengkap' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|max_length[150]',
        'role' => 'permit_empty|in_list[klien,konsultan,administrator]',
        'status_akun' => 'permit_empty|in_list[aktif,nonaktif,ditangguhkan]',
    ];

    protected $validationMessages = [
        'nama_lengkap' => [
            'required' => 'Nama lengkap wajib diisi.',
            'min_length' => 'Nama lengkap minimal 3 karakter.',
        ],
        'email' => [
            'required' => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
        ],
        'role' => [
            'in_list' => 'Role pengguna tidak valid.',
        ],
        'status_akun' => [
            'in_list' => 'Status akun tidak valid.',
        ],
    ];

    protected $skipValidation = false;

    // ==================================================
    // CARI PENGGUNA BERDASARKAN EMAIL
    // ==================================================

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    // ==================================================
    // AMBIL ROLE APLIKASI
    // ==================================================

    public function getApplicationRole(string $databaseRole): ?string
    {
        $roleMapping = [
            'administrator' => 'admin',
            'klien' => 'client',
            'konsultan' => 'psychologist',
        ];

        return $roleMapping[$databaseRole] ?? null;
    }

    // ==================================================
    // HITUNG TOTAL PENGGUNA
    // ==================================================

    public function countUsers(): int
    {
        return $this->countAllResults();
    }

    // ==================================================
    // HITUNG TOTAL KLIEN
    // ==================================================

    public function countClients(): int
    {
        return $this
            ->where('role', 'klien')
            ->countAllResults();
    }

    // ==================================================
    // HITUNG TOTAL PSIKOLOG
    // ==================================================

    public function countPsychologists(): int
    {
        return $this
            ->where('role', 'konsultan')
            ->countAllResults();
    }

    // ==================================================
    // HITUNG TOTAL ADMIN
    // ==================================================

    public function countAdmins(): int
    {
        return $this
            ->where('role', 'administrator')
            ->countAllResults();
    }
}
