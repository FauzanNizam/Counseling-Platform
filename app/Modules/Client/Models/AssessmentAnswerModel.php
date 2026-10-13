<?php

namespace Modules\Client\Models;

use CodeIgniter\Model;

class AssessmentAnswerModel extends Model
{
    protected $table = 'self_assessment_answers';
    protected $primaryKey = 'id_answer';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_assessment',
        'nomor_pertanyaan',
        'pertanyaan',
        'jawaban',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'id_assessment' => 'required|is_natural_no_zero',
        'nomor_pertanyaan' => 'required|is_natural_no_zero',
        'pertanyaan' => 'required',
        'jawaban' => 'required|is_natural',
    ];

    protected $skipValidation = false;

    /**
     * Mengambil semua jawaban dari satu asesmen.
     */
    public function getByAssessment(int $assessmentId): array
    {
        return $this
            ->where('id_assessment', $assessmentId)
            ->orderBy('nomor_pertanyaan', 'ASC')
            ->findAll();
    }
}
