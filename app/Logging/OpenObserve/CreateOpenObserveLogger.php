<?php

namespace App\Logging\OpenObserve;

use Monolog\Level;
use Monolog\Logger;

class CreateOpenObserveLogger
{
    public function __invoke(array $config): Logger
    {
        $client = app(OpenObserveClient::class);

        $level = $config['level'] ?? config('openobserve.level', 'debug');
        $handler = new OpenObserveHandler($client, Level::fromName(ucfirst(strtolower((string) $level))));

        $logger = new Logger($config['name'] ?? 'openobserve');
        $logger->pushHandler($handler);

        return $logger;
    }
}
