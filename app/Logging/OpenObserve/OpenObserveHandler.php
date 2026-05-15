<?php

namespace App\Logging\OpenObserve;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

class OpenObserveHandler extends AbstractProcessingHandler
{
    public function __construct(
        protected OpenObserveClient $client,
        int|string|Level $level = Level::Debug,
        bool $bubble = true,
    ) {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $row = [
            'timestamp' => $record->datetime->format('Y-m-d H:i:s.u'),
            'level'     => strtoupper($record->level->getName()),
            'message'   => $record->message,
            'channel'   => $record->channel,
            'service'   => config('openobserve.service', 'Laravel'),
            'env'       => (string) config('app.env'),
            'context'   => $record->context,
            'extra'     => $record->extra ?: [],
        ];

        $duration = app(RequestContext::class)->durationMs();
        if ($duration !== null) {
            $row['duration_ms'] = $duration;
        }

        $this->client->send([$row]);
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new JsonFormatter();
    }
}
