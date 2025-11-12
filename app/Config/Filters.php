<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use App\Filters\AuthFilter;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'     => CSRF::class,
        'toolbar'  => DebugToolbar::class,
        'honeypot' => Honeypot::class,
        'auth'     => AuthFilter::class,
    ];

    public $globals = [
        'before' => [
            'csrf',
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public $methods = [];

    public $filters = [
        'auth' => [
            'before' => [
                'dashboard',
                'dashboard/*',
                'employees',
                'users',
                'add-employee',
                'register'
            ]
        ]
    ];
}