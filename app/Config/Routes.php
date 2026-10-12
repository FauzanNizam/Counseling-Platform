
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

        // Tambahkan route Client lainnya di sini.
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

        // Tambahkan route Admin lainnya di sini.
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
