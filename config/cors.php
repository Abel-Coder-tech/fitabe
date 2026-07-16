<?php

return [
    'paths' => ['api/*', 'vote/*', 'webhook/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [env('APP_URL', 'http://localhost')],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
