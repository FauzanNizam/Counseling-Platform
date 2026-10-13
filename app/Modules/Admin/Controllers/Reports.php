<?php

namespace Modules\Admin\Controllers;

use App\Controllers\BaseController;

class Reports extends BaseController
{
    /**
     * Memastikan pengguna adalah Administrator.
     */
    private function authorized(): bool
    {
        return session()->get('isLoggedIn') === true
            && session()->get('user_role') === 'admin'
            && ctype_digit((string) session()->get('user_id'));
    }

    /**
     * Halaman laporan Administrator.
     */
    public function index()
    {
        if (!$this->authorized()) {
            return redirect()
                ->to(site_url('login'))
                ->with(
                    'error',
                    'Silakan login sebagai administrator.'
                );
        }

        $db = db_connect();

        // ========================================
        // STATISTIK KONSULTASI
        // ========================================

        $totalConsultations = $db
            ->table('konsultasi')
            ->countAllResults();

        $completedConsultations = $db
            ->table('konsultasi')
            ->where('status', 'Selesai')
            ->countAllResults();

        $cancelledConsultations = $db
            ->table('konsultasi')
            ->where('status', 'Dibatalkan')
            ->countAllResults();

        $onlineConsultations = $db
            ->table('konsultasi')
            ->where('tipe_sesi', 'Online')
            ->countAllResults();

        $pickupConsultations = $db
            ->table('konsultasi')
            ->where('tipe_sesi', 'Pick-up')
            ->countAllResults();

        // ========================================
        // STATISTIK PEMBAYARAN
        // ========================================

        $totalTransactions = $db
            ->table('pembayaran')
            ->countAllResults();

        $successfulTransactions = $db
            ->table('pembayaran')
            ->where('status_bayar', 'Sukses')
            ->countAllResults();

        $pendingTransactions = $db
            ->table('pembayaran')
            ->where('status_bayar', 'Pending')
            ->countAllResults();

        $failedTransactions = $db
            ->table('pembayaran')
            ->where('status_bayar', 'Gagal')
            ->countAllResults();

        // Total nominal pembayaran sukses.
        // COALESCE menjaga hasil tetap 0 jika kosong.
        $revenueResult = $db
            ->table('pembayaran')
            ->select(
                'COALESCE(SUM(jumlah), 0) AS total',
                false
            )
            ->where('status_bayar', 'Sukses')
            ->get()
            ->getRowArray();

        $totalRevenue = (float) (
            $revenueResult['total'] ?? 0
        );

        // ========================================
        // REKAP STATUS KONSULTASI
        // ========================================

        $consultationSummary = $db
            ->table('konsultasi')
            ->select(
                'status, COUNT(*) AS total',
                false
            )
            ->groupBy('status')
            ->orderBy('status', 'ASC')
            ->get()
            ->getResultArray();

        // ========================================
        // REKAP JENIS KONSULTASI
        // ========================================

        $sessionSummary = $db
            ->table('konsultasi')
            ->select(
                'tipe_sesi, COUNT(*) AS total',
                false
            )
            ->groupBy('tipe_sesi')
            ->orderBy('tipe_sesi', 'ASC')
            ->get()
            ->getResultArray();

        // ========================================
        // REKAP STATUS PEMBAYARAN
        // ========================================

        $paymentSummary = $db
            ->table('pembayaran')
            ->select(
                'status_bayar, COUNT(*) AS total',
                false
            )
            ->groupBy('status_bayar')
            ->orderBy('status_bayar', 'ASC')
            ->get()
            ->getResultArray();

        // ========================================
        // DATA UNTUK VIEW
        // ========================================

        return view(
            'Modules\Admin\Views\reports\index',
            [
                'title' => 'Laporan - Counseling Platform',

                'totalConsultations' =>
                    $totalConsultations,

                'completedConsultations' =>
                    $completedConsultations,

                'cancelledConsultations' =>
                    $cancelledConsultations,

                'onlineConsultations' =>
                    $onlineConsultations,

                'pickupConsultations' =>
                    $pickupConsultations,

                'totalTransactions' =>
                    $totalTransactions,

                'successfulTransactions' =>
                    $successfulTransactions,

                'pendingTransactions' =>
                    $pendingTransactions,

                'failedTransactions' =>
                    $failedTransactions,

                'totalRevenue' =>
                    $totalRevenue,

                'consultationSummary' =>
                    $consultationSummary,

                'sessionSummary' =>
                    $sessionSummary,

                'paymentSummary' =>
                    $paymentSummary,
            ]
        );
    }
}
