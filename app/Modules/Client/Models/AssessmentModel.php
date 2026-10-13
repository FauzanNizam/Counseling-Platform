<?php

namespace Modules\Client\Models;

use CodeIgniter\Model;

class AssessmentModel extends Model
{
    protected $table = 'self_assessments';
    protected $primaryKey = 'id_assessment';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_klien',
        'jenis_assessment',
        'skor',
        'ringkasan',
        'rekomendasi',
        'tanggal_assessment',
    ];

    // Database memiliki created_at,
    // tetapi belum memiliki updated_at.
    protected $useTimestamps = false;

    protected $validationRules = [
        'id_klien' => 'required|is_natural_no_zero',
        'jenis_assessment' => 'required|max_length[50]',
        'skor' => 'required|is_natural',
    ];

    protected $skipValidation = false;

    /**
     * Mengambil riwayat asesmen milik client tertentu.
     */
    public function getByClient(int $clientId): array
    {
        return $this
            ->where('id_klien', $clientId)
            ->orderBy('tanggal_assessment', 'DESC')
            ->findAll();
    }

    /**
     * Mengambil satu hasil asesmen milik client.
     * Pemeriksaan id_klien mencegah client
     * membaca hasil asesmen pengguna lain.
     */
    public function getClientAssessment(
        int $assessmentId,
        int $clientId
    ): ?array {
        return $this
            ->where('id_assessment', $assessmentId)
            ->where('id_klien', $clientId)
            ->first();
    }
}
