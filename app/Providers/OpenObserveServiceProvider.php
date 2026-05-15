<?php

namespace App\Providers;

use App\Logging\OpenObserve\OpenObserveClient;
use App\Logging\OpenObserve\OpenObserveHandler;
use App\Logging\OpenObserve\OpenObserveLogger;
use App\Logging\OpenObserve\RequestContext;
use App\Logging\OpenObserve\RequestContextProcessor;
use Illuminate\Support\ServiceProvider;
use Monolog\Level;

class OpenObserveServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/openobserve.php', 'openobserve');

        $this->app->singleton(RequestContext::class);

        $this->app->singleton(OpenObserveClient::class, function ($app) {
            $cfg = $app['config']->get('openobserve');

            return new OpenObserveClient(
                url:          (string) ($cfg['url'] ?? ''),
                organization: (string) ($cfg['organization'] ?? 'default'),
                stream:       (string) ($cfg['stream'] ?? 'laravel-logs'),
                email:        $cfg['email'] ?? null,
                password:     $cfg['password'] ?? null,
                timeout:      (int) ($cfg['timeout'] ?? 3),
            );
        });

        $this->app->singleton(OpenObserveHandler::class, function ($app) {
            $levelName = (string) $app['config']->get('openobserve.level', 'debug');
            $level = Level::fromName(ucfirst(strtolower($levelName)));

            $handler = new OpenObserveHandler($app->make(OpenObserveClient::class), $level);
            $handler->pushProcessor(new RequestContextProcessor($app->make(RequestContext::class)));

            return $handler;
        });

        $this->app->singleton(OpenObserveLogger::class, function ($app) {
            return new OpenObserveLogger(
                $app->make(OpenObserveHandler::class),
                $app->make(OpenObserveClient::class),
            );
        });
    }

    public function boot(): void
    {
        if (class_exists(\Laravel\Octane\Events\RequestReceived::class)) {
            $this->app['events']->listen(
                \Laravel\Octane\Events\RequestReceived::class,
                function ($event): void {
                    $event->sandbox->forgetInstance(RequestContext::class);
                },
            );
        }
    }
}
