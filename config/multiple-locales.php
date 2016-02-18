<?php

return [

    /**
     * Paths to the necessary files and directories
     */
    'paths' => [
        'config'   => [
            'app' => config_path('app.php'),
        ],
        'project'  => [
            'RouteServiceProvider' => app_path('Providers/RouteServiceProvider.php'),
            'LanguageMiddleware'   => app_path('Http/Middleware/Language.php'),
            'Kernel'               => app_path('Http/Kernel.php'),
        ],
    ],
];