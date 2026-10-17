<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Counseling Platform
 * Routing menggunakan arsitektur HMVC.
 */

// ======================================================
// HALAMAN UTAMA
// ======================================================

$routes->get(
    '/',
    '\Modules\Client\Controllers\Dashboard::index',
    ['filter' => 'role:client']
);


// ======================================================
// AUTH - LOGIN, REGISTER, LOGOUT
// ======================================================

$routes->get(
    'login',
    '\Modules\Auth\Controllers\Login::index'
);

$routes->post(
    'login',
    '\Modules\Auth\Controllers\Login::authenticate'
);

$routes->get(
    'register',
    '\Modules\Auth\Controllers\Register::index'
);

$routes->post(
    'register',
    '\Modules\Auth\Controllers\Register::store'
);

$routes->post(
    'logout',
    '\Modules\Auth\Controllers\Login::logout'
);


// ======================================================
// CLIENT MODULE
// ======================================================

$routes->group(
    'client',
    [
        'namespace' => 'Modules\Client\Controllers',
        'filter' => 'role:client',
    ],
    static function ($routes) {

        // ==========================================
        // DASHBOARD KLIEN
        // ==========================================
    
        $routes->get('/', 'Dashboard::index');


        // ==========================================
        // PROFIL KLIEN
        // ==========================================
    
        $routes->get('profile', 'Profile::index');

        $routes->post(
            'profile/update',
            'Profile::update'
        );


        // ==========================================
        // SELF-ASSESSMENT
        // ==========================================
    
        $routes->get(
            'assessment',
            'Assessment::index'
        );

        $routes->post(
            'assessment/submit',
            'Assessment::submit'
        );

        $routes->get(
            'assessment/new',
            'Assessment::create'
        );

        $routes->get(
            'assessment/history',
            'Assessment::history'
        );

        $routes->get(
            'assessment/history/(:num)',
            'Assessment::detail/$1'
        );


        // ==========================================
        // KONSULTAN
        // ==========================================
    
        $routes->get(
            'konsultan',
            'Konsultan::index'
        );

        $routes->get(
            'konsultan/(:num)',
            'Konsultan::detail/$1'
        );


        // ==========================================
        // KONSULTASI
        // ==========================================
    
        $routes->get(
            'konsultasi/buat',
            'Konsultasi::create'
        );

        $routes->post(
            'konsultasi/simpan',
            'Konsultasi::store'
        );

        $routes->get(
            'konsultasi/buat/(:num)',
            'Konsultasi::create/$1'
        );


        // ==========================================
        // JADWAL KLIEN
        // ==========================================
    
        $routes->get(
            'jadwal',
            'Jadwal::index'
        );

        $routes->post(
            'jadwal/batalkan/(:num)',
            'Jadwal::batalkan/$1'
        );


        // ==========================================
        // PEMBAYARAN
        // ==========================================
    
        $routes->get(
            'pembayaran/(:num)',
            'Pembayaran::index/$1'
        );

        $routes->post(
            'pembayaran/proses/(:num)',
            'Pembayaran::proses/$1'
        );

        $routes->post(
            'pembayaran/upload-bukti/(:num)',
            'Pembayaran::uploadBukti/$1'
        );

    }
);


// ======================================================
// ADMIN MODULE
// ======================================================

$routes->group(
    'admin',
    [
        'namespace' => 'Modules\Admin\Controllers',
        'filter' => 'role:admin',
    ],
    static function ($routes) {

        // ==========================================
        // DASHBOARD ADMIN
        // ==========================================
    
        $routes->get('/', 'Dashboard::index');


        // ==========================================
        // F-21: MANAJEMEN PENGGUNA
        // ==========================================
    
        // READ
        $routes->get('users', 'Users::index');

        // CREATE
        $routes->get(
            'users/create',
            'Users::create'
        );

        $routes->post(
            'users/store',
            'Users::store'
        );

        // DETAIL
        $routes->get(
            'users/(:num)',
            'Users::show/$1'
        );

        // EDIT
        $routes->get(
            'users/(:num)/edit',
            'Users::edit/$1'
        );

        // UPDATE
        $routes->post(
            'users/(:num)/update',
            'Users::update/$1'
        );

        // DELETE
        $routes->post(
            'users/(:num)/delete',
            'Users::delete/$1'
        );


        // ==========================================
        // F-22: MANAJEMEN KONSULTAN
        // ==========================================
    
        // READ
        $routes->get(
            'consultants',
            'Consultants::index'
        );

        // CREATE
        $routes->get(
            'consultants/create',
            'Consultants::create'
        );

        $routes->post(
            'consultants/store',
            'Consultants::store'
        );

        // DETAIL
        $routes->get(
            'consultants/(:num)',
            'Consultants::show/$1'
        );

        // EDIT
        $routes->get(
            'consultants/(:num)/edit',
            'Consultants::edit/$1'
        );

        // UPDATE
        $routes->post(
            'consultants/(:num)/update',
            'Consultants::update/$1'
        );

        // VERIFIKASI
        $routes->post(
            'consultants/(:num)/verify',
            'Consultants::verify/$1'
        );

        // DELETE
        $routes->post(
            'consultants/(:num)/delete',
            'Consultants::delete/$1'
        );


        // ==========================================
        // MANAJEMEN KONSULTASI
        // ==========================================
    
        $routes->get(
            'consultations',
            'Consultations::index'
        );

        $routes->get(
            'consultations/(:num)',
            'Consultations::show/$1'
        );


        // ==========================================
        // VERIFIKASI PEMBAYARAN
        // ==========================================
    
        $routes->post(
            'consultations/(:num)/payment/verify',
            'Consultations::verifyPayment/$1'
        );

        $routes->post(
            'consultations/(:num)/payment/reject',
            'Consultations::rejectPayment/$1'
        );

        $routes->get(
            'consultations/(:num)/payment/proof',
            'Consultations::paymentProof/$1'
        );


        // ==========================================
        // LAPORAN ADMIN
        // ==========================================
    
        $routes->get(
            'reports',
            'Reports::index'
        );

    }
);


// ======================================================
// PSYCHOLOGIST MODULE
// ======================================================

$routes->group(
    'psychologist',
    [
        'namespace' => 'Modules\Psychologist\Controllers',
        'filter' => 'role:psychologist',
    ],
    static function ($routes) {

        // ==========================================
        // DASHBOARD PSIKOLOG
        // ==========================================
    
        $routes->get(
            '/',
            'Dashboard::index'
        );


        // ==========================================
        // JADWAL KONSULTASI
        // ==========================================
    
        // Daftar konsultasi
        $routes->get(
            'consultations',
            'Consultations::index'
        );

        // Menerima konsultasi
        $routes->post(
            'consultations/(:num)/confirm',
            'Consultations::confirm/$1'
        );

        // Menolak konsultasi
        $routes->post(
            'consultations/(:num)/reject',
            'Consultations::reject/$1'
        );


        // Detail konsultasi
        $routes->get(
            'consultations/(:num)',
            'Consultations::show/$1'
        );

        // Mulai sesi konsultasi
        $routes->post(
            'consultations/(:num)/start',
            'Consultations::start/$1'
        );

        // Selesaikan sesi konsultasi
        $routes->post(
            'consultations/(:num)/finish',
            'Consultations::finish/$1'
        );

        //JADWAL PRAKTEK PSIKOLOG
    
        $routes->get(
            'practice-schedule',
            'PracticeSchedule::index'
        );

        $routes->post(
            'practice-schedule/store',
            'PracticeSchedule::store'
        );

        $routes->post(
            'practice-schedule/(:num)/update',
            'PracticeSchedule::update/$1'
        );

        $routes->post(
            'practice-schedule/(:num)/toggle',
            'PracticeSchedule::toggle/$1'
        );

        // Tambah link meeting untuk konsultasi
        $routes->post(
            'consultations/(:num)/meeting',
            'Consultations::saveMeeting/$1'
        );

        // ==========================================
        // PROFIL PSIKOLOG
        // ==========================================
    
        // Menampilkan profil
        $routes->get(
            'profile',
            'Profile::index'
        );

        // Memperbarui profil
        $routes->post(
            'profile/update',
            'Profile::update'
        );

        // Mengubah password
        $routes->post(
            'profile/password',
            'Profile::updatePassword'
        );

        // ==========================================
        // FITUR SELANJUTNYA
        // ==========================================
    
        // Klien dan Pesan akan ditambahkan
        // setelah controller masing-masing dibuat.
    
    }
);


// ======================================================
// SETUP ROUTES - NONAKTIF
// ======================================================

// Route setup akun melalui URL publik dinonaktifkan.
// Gunakan Seeder atau proses administrasi yang aman.

// $routes->get('setup-admin', 'SetupAdmin::create');
// $routes->get('setup-psychologist', 'SetupPsychologist::create');
