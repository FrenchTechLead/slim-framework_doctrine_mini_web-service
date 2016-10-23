<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => true, // Allow the web server to send the content-length header
        'determineRouteBeforeAppMiddleware' => true,

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        "public_routes"=>[
            "/",
            "/v1/authentificate",
            "/user_space",
            "/superuser_space",
            "/bootstrap_css",
            "/bootstrap_theme_css",
            "/bootstrap_js",
            "/jquery",
            "/jquery_cookie",
            "/myScripts",
            "/sweetAlert_css",
            "/sweetAlert_js"

        ], // these routes don't need Authentification

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        'doctrine' => [
            'meta' => [
                'entity_path' => [
                    __DIR__ . '/App/Entities'
                ],
                'auto_generate_proxies' => true,
                'proxy_dir' =>  __DIR__.'/../cache/proxies',
                'cache' => null,
            ],
            'connection' => [
                'driver' => 'pdo_sqlite',
                'path' => __DIR__.'/../db/db.sqlite3'
            ]
        ],
    ],
];
