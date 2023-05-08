<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'filterAdmin' => \App\Filters\FilterAdmin::class,
        'filterKaryawan' => \App\Filters\FilterKaryawan::class,
        'filterPembelian' => \App\Filters\FilterPembelian::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            //'csrf',
            // 'invalidchars',
            'filterAdmin'=>[
                'except'=>['auth/*','auth','/']
            ],'filterKaryawan'=>[
                'except'=>['auth/*','auth','/']
            ],'filterPembelian'=>[
                'except'=>['auth/*','auth','/']
            ]
        ],
        'after' => [
            'filterAdmin'=>[
                'except' => [
                    'login', 'login/*',
                    'main', 'main/*',
                    'lokasi', 'lokasi/*',
                    'barang', 'barang/*',
                    'barangkeluar', 'barangkeluar/*',
                    'barangmasuk', 'barangmasuk/*',
                    'barangpermintaan', 'barangpermintaan/*',
                    'kategori', 'kategori/*',
                    'laporan', 'laporan/*',
                    'pelanggan', 'pelanggan/*',
                    'satuan', 'satuan/*',
                    'utility', 'utility/*',
                    'users', 'users/*',
                ]
            ],
            'filterKaryawan'=>[
                'except' => [
                    'login', 'login/*',
                    'main', 'main/*',
                    'barangkeluar', 'barangkeluar/*',
                    'barangmasuk', 'barangmasuk/*',
                    'barangpermintaan', 'barangpermintaan/*',
                    'utility', 'utility/*',
                ]
            ],
            'filterPembelian'=>[
                'except' => [
                    'login', 'login/*',
                    'main', 'main/*',
                    'barangkeluar', 'barangkeluar/*',
                    'barangmasuk', 'barangmasuk/*',
                    'barangpermintaan', 'barangpermintaan/*',
                    'laporan', 'laporan/*',
                    'utility', 'utility/*',
                    'users', 'users/*',
                ]
            ],
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don’t expect could bypass the filter.
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     */
    public array $filters = [];
}
