<?php

namespace App\Logging\OpenObserve;

use Monolog\Logger;

class OpenObserveLogger
{
    protected Logger $logger;

    public function __construct(
        protected OpenObserveHandler $handler,
        protected OpenObserveClient $client,
        string $channelName = 'openobserve',
    ) {
        $this->logger = new Logger($channelName);
        $this->logger->pushHandler($handler);
    }

    public function emergency(string $message, array $context = []): void { $this->log('emergency', $message, $context); }
    public function alert(string $message, array $context = []): void     { $this->log('alert', $message, $context); }
    public function critical(string $message, array $context = []): void  { $this->log('critical', $message, $context); }
    public function error(string $message, array $context = []): void     { $this->log('error', $message, $context); }
    public function warning(string $message, array $context = []): void   { $this->log('warning', $message, $context); }
    public function notice(string $message, array $context = []): void    { $this->log('notice', $message, $context); }
    public function info(string $message, array $context = []): void      { $this->log('info', $message, $context); }
    public function debug(string $message, array $context = []): void     { $this->log('debug', $message, $context); }

    public function log(string $level, string $message, array $context = []): void
    {
        if (! $this->enabled()) {
            return;
        }
        $this->logger->{$level}($message, $context);
    }

    public function raw(array $row): bool
    {
        if (! $this->enabled()) {
            return false;
        }
        return $this->client->send([$row]);
    }

    public function monolog(): Logger
    {
        return $this->logger;
    }

    protected function enabled(): bool
    {
        return (bool) config('openobserve.enabled', false);
    }
}
