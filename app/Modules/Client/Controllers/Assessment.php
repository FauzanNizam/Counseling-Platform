<?php

namespace Modules\Client\Controllers;

use App\Controllers\BaseController;
use Modules\Client\Models\AssessmentModel;
use Modules\Client\Models\AssessmentAnswerModel;

class Assessment extends BaseController
{
    protected AssessmentModel $assessmentModel;
    protected AssessmentAnswerModel $answerModel;

    public function __construct()
    {
        $this->assessmentModel = new AssessmentModel();
        $this->answerModel = new AssessmentAnswerModel();
    }

    /**
     * Menampilkan halaman self-assessment.
     */

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (session()->get('user_role') !== 'client') {
            return redirect()->to('/login');
        }

        $clientId = (int) session()->get('user_id');

        // Jika sudah pernah mengisi, arahkan ke halaman riwayat.
        $assessments = $this->assessmentModel->getByClient($clientId);

        if (!empty($assessments)) {
            return redirect()->to('/client/assessment/history');
        }

        // Jika belum pernah mengisi, tampilkan kuesioner.
        $questions = [
            'Dalam 7 hari terakhir, seberapa sering kamu merasa stres?',
            'Dalam 7 hari terakhir, seberapa sering kamu merasa sedih atau kurang bersemangat?',
            'Dalam 7 hari terakhir, seberapa sering kamu merasa khawatir berlebihan?',
            'Dalam 7 hari terakhir, seberapa sering kamu mengalami kesulitan tidur?',
            'Dalam 7 hari terakhir, seberapa sering kamu merasa lelah secara emosional?',
            'Dalam 7 hari terakhir, seberapa sering kamu kesulitan berkonsentrasi?',
            'Dalam 7 hari terakhir, seberapa sering kamu merasa kewalahan menghadapi aktivitas?',
            'Dalam 7 hari terakhir, seberapa sering kamu merasa sulit menikmati kegiatan yang biasanya disukai?',
        ];

        return view('Modules\Client\Views\assessment\index', [
            'questions' => $questions,
        ]);
    }



    /**
     * Memproses dan menyimpan hasil self-assessment.
     */
    public function submit()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (session()->get('user_role') !== 'client') {
            return redirect()->to('/login');
        }

        $questions = [
            1 => 'Dalam 7 hari terakhir, seberapa sering kamu merasa stres?',
            2 => 'Dalam 7 hari terakhir, seberapa sering kamu merasa sedih atau kurang bersemangat?',
            3 => 'Dalam 7 hari terakhir, seberapa sering kamu merasa khawatir berlebihan?',
            4 => 'Dalam 7 hari terakhir, seberapa sering kamu mengalami kesulitan tidur?',
            5 => 'Dalam 7 hari terakhir, seberapa sering kamu merasa lelah secara emosional?',
            6 => 'Dalam 7 hari terakhir, seberapa sering kamu kesulitan berkonsentrasi?',
            7 => 'Dalam 7 hari terakhir, seberapa sering kamu merasa kewalahan menghadapi aktivitas?',
            8 => 'Dalam 7 hari terakhir, seberapa sering kamu merasa sulit menikmati kegiatan yang biasanya disukai?',
        ];

        $answers = $this->request->getPost('answers');

        if (!is_array($answers) || count($answers) !== count($questions)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Harap jawab semua pertanyaan.');
        }

        $score = 0;

        foreach ($questions as $number => $question) {
            if (
                !array_key_exists($number, $answers)
                || !in_array((string) $answers[$number], ['0', '1', '2', '3'], true)
            ) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Jawaban tidak valid. Silakan periksa kembali.');
            }

            $score += (int) $answers[$number];
        }

        // Ringkasan refleksi diri, bukan diagnosis klinis.
        if ($score <= 6) {
            $summary = 'Frekuensi keluhan yang dilaporkan relatif rendah.';
            $recommendation = 'Pertahankan kebiasaan yang mendukung kesejahteraan diri dan evaluasi kondisi secara berkala.';
        } elseif ($score <= 13) {
            $summary = 'Beberapa keluhan cukup sering dirasakan.';
            $recommendation = 'Coba kenali pemicu stres, jaga waktu istirahat, dan bicarakan perasaanmu dengan orang tepercaya jika diperlukan.';
        } elseif ($score <= 20) {
            $summary = 'Keluhan cukup sering muncul dalam beberapa aspek.';
            $recommendation = 'Pertimbangkan untuk berbicara dengan psikolog atau konselor agar kamu bisa mengeksplorasi kondisi dan kebutuhanmu.';
        } else {
            $summary = 'Keluhan sering muncul pada beberapa aspek.';
            $recommendation = 'Pertimbangkan untuk mencari dukungan dari psikolog atau konselor. Jika kamu merasa tidak aman atau kesulitan menjalani aktivitas, cari bantuan profesional sesegera mungkin.';
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $assessmentId = $this->assessmentModel->insert([
                'id_klien' => session()->get('user_id'),
                'jenis_assessment' => 'kesejahteraan_diri',
                'skor' => $score,
                'ringkasan' => $summary,
                'rekomendasi' => $recommendation,
                'tanggal_assessment' => date('Y-m-d H:i:s'),
            ]);

            if ($assessmentId === false) {
                throw new \RuntimeException('Gagal menyimpan hasil assessment.');
            }

            foreach ($questions as $number => $question) {
                $saved = $this->answerModel->insert([
                    'id_assessment' => $assessmentId,
                    'nomor_pertanyaan' => $number,
                    'pertanyaan' => $question,
                    'jawaban' => (int) $answers[$number],
                ]);

                if ($saved === false) {
                    throw new \RuntimeException('Gagal menyimpan jawaban assessment.');
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi database gagal.');
            }

            $db->transCommit();

            return redirect()->to('/client/assessment')
                ->with('success', 'Assessment berhasil disimpan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Self-assessment gagal disimpan: {message}', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Assessment gagal disimpan. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan riwayat self-assessment milik klien.
     */
    public function history()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (session()->get('user_role') !== 'client') {
            return redirect()->to('/login');
        }

        $clientId = (int) session()->get('user_id');

        $assessments = $this->assessmentModel
            ->getByClient($clientId);

        return view('Modules\Client\Views\assessment\history', [
            'assessments' => $assessments,
        ]);
    }

    /**
     * Menampilkan kuesioner untuk assessment baru.
     */
    public function create()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (session()->get('user_role') !== 'client') {
            return redirect()->to('/login');
        }

        $questions = [
            'Dalam 7 hari terakhir, seberapa sering kamu merasa stres?',
            'Dalam 7 hari terakhir, seberapa sering kamu merasa sedih atau kurang bersemangat?',
            'Dalam 7 hari terakhir, seberapa sering kamu merasa khawatir berlebihan?',
            'Dalam 7 hari terakhir, seberapa sering kamu mengalami kesulitan tidur?',
            'Dalam 7 hari terakhir, seberapa sering kamu merasa lelah secara emosional?',
            'Dalam 7 hari terakhir, seberapa sering kamu kesulitan berkonsentrasi?',
            'Dalam 7 hari terakhir, seberapa sering kamu merasa kewalahan menghadapi aktivitas?',
            'Dalam 7 hari terakhir, seberapa sering kamu merasa sulit menikmati kegiatan yang biasanya disukai?',
        ];

        return view('Modules\Client\Views\assessment\index', [
            'questions' => $questions,
        ]);
    }

    /**
     * Menampilkan detail hasil assessment beserta jawaban.
     */
    public function detail($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (session()->get('user_role') !== 'client') {
            return redirect()->to('/login');
        }

        if (!is_numeric($id) || (int) $id < 1) {
            return redirect()->to('/client/assessment/history')
                ->with('error', 'ID assessment tidak valid.');
        }

        $clientId = (int) session()->get('user_id');
        $assessmentId = (int) $id;

        // Pastikan assessment memang milik klien yang sedang login.
        $assessment = $this->assessmentModel
            ->getClientAssessment($assessmentId, $clientId);

        if (!$assessment) {
            return redirect()->to('/client/assessment/history')
                ->with('error', 'Data assessment tidak ditemukan.');
        }

        $answers = $this->answerModel
            ->getByAssessment($assessmentId);

        return view('Modules\Client\Views\assessment\detail', [
            'assessment' => $assessment,
            'answers' => $answers,
        ]);
    }
}
