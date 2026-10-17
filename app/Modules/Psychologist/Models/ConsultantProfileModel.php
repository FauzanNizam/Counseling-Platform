<?php

namespace Modules\Shared\Models;

use CodeIgniter\Model;

class ConsultantProfileModel extends Model
{
    // ==========================================
    // KONFIGURASI TABEL
    // ==========================================

    protected $table = 'profil_konsultan';

    protected $primaryKey = 'id_profil';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $protectFields = true;

    // ==========================================
    // KOLOM YANG BOLEH DISIMPAN
    // ==========================================

    protected $allowedFields = [
        'id_user',
        'no_str',
        'spesialisasi',
        'pengalaman_tahun',
        'status_verifikasi',
    ];

    // ==========================================
    // TIMESTAMP
    // ==========================================

    protected $useTimestamps = false;

    // ==========================================
    // VALIDASI
    // ==========================================

    protected $skipValidation = false;

    protected $validationRules = [
        'id_user' => 'permit_empty|is_natural_no_zero',
        'no_str' => 'permit_empty|max_length[50]',
        'spesialisasi' => 'permit_empty|max_length[100]',
        'pengalaman_tahun' => 'permit_empty|is_natural',
    ];

    // ==========================================
    // AMBIL PROFIL BERDASARKAN ID USER
    // ==========================================

    public function getByUserId(int $userId): ?array
    {
        return $this
            ->where('id_user', $userId)
            ->first();
    }
}
