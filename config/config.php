<?php

return [
    'namespace' => 'modules',

    'tenant-namespace' => 'tenants',

    'central-namespace' => 'central',

    'stubs' => [
        'path' => base_path('vendor/escapepixel/laravel-ca-modules/src/Commands/stubs'),
        'files' => [
            'routes/api' => 'V1/routes/api.php',
        ],
    ],

    'paths' => [
        'migration' => base_path('database/migrations'),

        'generator' => [
            'controller'            => 'V1/Http/Controllers/Apis',
            'request'               => 'V1/Http/Requests',
            'resource'              => 'V1/Http/Resources',
            'routes'                => 'V1/routes',
            'migration'             => 'V1/database/migrations',
            'entity'                => 'V1/Core/Entity',
            'usecase'               => 'V1/Core/UseCase',
            'driver'                => 'V1/Core/Driver',
            'controller-handler'    => 'V1/Core/Controller',
            'provider'              => 'V1/Providers',

            'feature'               => 'V1/tests/Feature',
            'unit'                  => 'V1/tests/Unit',
        ],

    ],
];
