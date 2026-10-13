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

// MVC LAMA
// $routes->get('/', 'Client\Dashboard::index', [
//     'filter' => 'role:client',
// ]);

// HMVC
$routes->get(
    '/',
    '\Modules\Client\Controllers\Dashboard::index',
    ['filter' => 'role:client']
);


// ======================================================
// AUTH - LOGIN, REGISTER, LOGOUT
// ======================================================

// MVC LAMA
// $routes->get('login', 'Auth\Login::index');
// $routes->post('login', 'Auth\Login::authenticate');
// $routes->get('register', 'Auth\Register::index');
// $routes->post('register', 'Auth\Register::store');
// $routes->post('logout', 'Auth\Login::logout');

// HMVC
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

// MVC LAMA
// $routes->get('client', 'Client\Dashboard::index', [
//     'filter' => 'role:client',
// ]);

// HMVC

$routes->group(
    'client',
    [
        'namespace' => 'Modules\Client\Controllers',
        'filter' => 'role:client',
    ],
    static function ($routes) {
        // Dashboard Klien
        $routes->get('/', 'Dashboard::index');

        // Profil Pengguna
        $routes->get('profile', 'Profile::index');
        $routes->post('profile/update', 'Profile::update');

        // Self-Assessment

        // Self-Assessment
        $routes->get('assessment', 'Assessment::index');
        $routes->post('assessment/submit', 'Assessment::submit');
        $routes->get('assessment/new', 'Assessment::create');
        $routes->get('assessment/history', 'Assessment::history');
        $routes->get('assessment/history/(:num)', 'Assessment::detail/$1');

        // Konsultan

        $routes->get('konsultan', 'Konsultan::index');
        $routes->get('konsultan/(:num)', 'Konsultan::detail/$1');

        // Konsultasi
        $routes->get('konsultasi/buat', 'Konsultasi::create');
        $routes->post('konsultasi/simpan', 'Konsultasi::store');
        $routes->get('konsultasi/buat/(:num)', 'Konsultasi::create/$1');

        $routes->get('jadwal', 'Jadwal::index');
        $routes->post('jadwal/batalkan/(:num)', 'Jadwal::batalkan/$1');

        //pembayaran
        $routes->get('pembayaran/(:num)', 'Pembayaran::index/$1');
        $routes->post(
            'pembayaran/proses/(:num)',
            'Pembayaran::proses/$1'
        );
        $routes->post(
            'pembayaran/upload-bukti/(:num)',
            'Pembayaran::uploadBukti/$1'
        );
        // Route Client lainnya ditambahkan di sini.
    }
);



// ======================================================
// ADMIN MODULE
// ======================================================

// MVC LAMA
// $routes->get('admin', 'Admin\Dashboard::index', [
//     'filter' => 'role:admin',
// ]);


// HMVC

$routes->group(
    'admin',
    [
        'namespace' => 'Modules\Admin\Controllers',
        'filter' => 'role:admin',
    ],
    static function ($routes) {

        // Dashboard Admin
        $routes->get('/', 'Dashboard::index');

        // F-21: READ
        $routes->get('users', 'Users::index');

        // F-21: CREATE
        $routes->get('users/create', 'Users::create');
        $routes->post('users/store', 'Users::store');

        // READ DETAIL - Detail Pengguna
        $routes->get('users/(:num)', 'Users::show/$1');

        // UPDATE - Edit Pengguna
        $routes->get('users/(:num)/edit', 'Users::edit/$1');
        $routes->post('users/(:num)/update', 'Users::update/$1');

        $routes->post('users/(:num)/delete', 'Users::delete/$1');

        // =====================================
// F-22: MANAJEMEN KONSULTAN
// =====================================
    
        // Daftar konsultan
        $routes->get('consultants', 'Consultants::index');

        // Tambah konsultan
        $routes->get('consultants/create', 'Consultants::create');
        $routes->post('consultants/store', 'Consultants::store');

        // Detail konsultan
        $routes->get('consultants/(:num)', 'Consultants::show/$1');

        // Edit konsultan
        $routes->get('consultants/(:num)/edit', 'Consultants::edit/$1');
        $routes->post('consultants/(:num)/update', 'Consultants::update/$1');

        // Verifikasi konsultan
        $routes->post('consultants/(:num)/verify', 'Consultants::verify/$1');

        // Hapus konsultan
        $routes->post('consultants/(:num)/delete', 'Consultants::delete/$1');

        $routes->get('consultations', 'Consultations::index');
        $routes->get('consultations/(:num)', 'Consultations::show/$1');

        $routes->get('reports', 'Reports::index');
    }
);




// ======================================================
// PSYCHOLOGIST MODULE
// ======================================================

// MVC LAMA
// $routes->get('psychologist', 'Psychologist\Dashboard::index', [
//     'filter' => 'role:psychologist',
// ]);

// HMVC
$routes->group(
    'psychologist',
    [
        'namespace' => 'Modules\Psychologist\Controllers',
        'filter' => 'role:psychologist',
    ],
    static function ($routes) {
        // Dashboard Psikolog
        $routes->get('/', 'Dashboard::index');

        // Tambahkan route Psikolog lainnya di sini.
    }
);


// ======================================================
// SETUP ROUTES - NONAKTIF
// ======================================================

// Route setup akun dengan URL publik dinonaktifkan.
// Gunakan Seeder atau proses administrasi yang aman.

// $routes->get('setup-admin', 'SetupAdmin::create');
// $routes->get('setup-psychologist', 'SetupPsychologist::create');
