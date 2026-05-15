<?php

return [
    'enabled'      => filter_var(env('OPENOBSERVE_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
    'url'          => env('OPENOBSERVE_URL', 'http://localhost:5080'),
    'organization' => env('OPENOBSERVE_ORGANIZATION', 'default'),
    'stream'       => env('OPENOBSERVE_STREAM', 'laravel-logs'),
    'email'        => env('OPENOBSERVE_EMAIL'),
    'password'     => env('OPENOBSERVE_PASSWORD'),
    'service'      => env('OPENOBSERVE_SERVICE', env('APP_NAME', 'Laravel')),
    'level'        => env('OPENOBSERVE_LOG_LEVEL', 'debug'),
    'timeout'      => (int) env('OPENOBSERVE_TIMEOUT', 3),
];
