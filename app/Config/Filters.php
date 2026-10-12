<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

use App\Filters\AuthFilter;
use App\Filters\RoleFilter;

class Filters extends BaseFilters
{
    /**
     * ==================================================
     * FILTER ALIASES
     * ==================================================
     *
     * Daftar nama filter yang dapat digunakan
     * pada Routes.php atau konfigurasi lainnya.
     *
     * Filter role digunakan untuk:
     * - Administrator
     * - Klien
     * - Psikolog
     */

    public array $aliases = [
        // Filter bawaan CodeIgniter 4
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,

        // Filter autentikasi Counseling Platform
        'auth' => AuthFilter::class,

        // Filter pembatasan akses berdasarkan role
        'role' => RoleFilter::class,
    ];

    /**
     * ==================================================
     * REQUIRED FILTERS
     * ==================================================
     *
     * Filter khusus yang dibutuhkan oleh framework.
     * Konfigurasi bawaan tetap dipertahankan.
     */

    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],

        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    /**
     * ==================================================
     * GLOBAL FILTERS
     * ==================================================
     *
     * Filter yang dijalankan pada setiap request.
     *
     * CSRF diaktifkan untuk melindungi form POST,
     * termasuk login, register, dan logout.
     *
     * Auth dan role tidak dijalankan secara global
     * karena halaman login dan register harus
     * dapat diakses tanpa login.
     */

    public array $globals = [
        'before' => [
            'csrf' => [
                'except' => [],
            ],

            // 'honeypot',
            // 'invalidchars',
        ],

        'after' => [
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * ==================================================
     * HTTP METHOD FILTERS
     * ==================================================
     *
     * Filter berdasarkan metode HTTP.
     *
     * Kosong karena CSRF sudah diaktifkan
     * secara global.
     */

    public array $methods = [];

    /**
     * ==================================================
     * URI FILTERS
     * ==================================================
     *
     * Filter khusus berdasarkan pola URL.
     *
     * Untuk saat ini pembatasan akses role
     * diatur langsung melalui Routes.php.
     *
     * Contoh:
     *
     * ['filter' => 'role:admin']
     * ['filter' => 'role:client']
     * ['filter' => 'role:psychologist']
     */

    public array $filters = [];
}
