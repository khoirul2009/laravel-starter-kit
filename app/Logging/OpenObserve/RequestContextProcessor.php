<?php

namespace App\Logging\OpenObserve;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class RequestContextProcessor implements ProcessorInterface
{
    public function __construct(protected RequestContext $context) {}

    public function __invoke(LogRecord $record): LogRecord
    {
        $base = $this->context->toArray();
        if (empty($base)) {
            return $record;
        }

        $merged = $base + $record->context;

        return $record->with(context: $merged);
    }
}
