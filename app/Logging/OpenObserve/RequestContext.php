<?php

namespace App\Logging\OpenObserve;

class RequestContext
{
    public ?string $requestId = null;
    public ?int $userId = null;
    public ?string $ip = null;
    public ?string $method = null;
    public ?string $url = null;
    public ?float $startedAt = null;

    public function set(
        ?string $requestId = null,
        ?int $userId = null,
        ?string $ip = null,
        ?string $method = null,
        ?string $url = null,
        ?float $startedAt = null,
    ): void {
        $this->requestId = $requestId;
        $this->userId = $userId;
        $this->ip = $ip;
        $this->method = $method;
        $this->url = $url;
        $this->startedAt = $startedAt;
    }

    public function durationMs(): ?int
    {
        if ($this->startedAt === null) {
            return null;
        }
        return (int) round((microtime(true) - $this->startedAt) * 1000);
    }

    public function toArray(): array
    {
        return array_filter([
            'request_id' => $this->requestId,
            'user_id'    => $this->userId,
            'ip'         => $this->ip,
            'method'     => $this->method,
            'url'        => $this->url,
        ], fn ($v) => $v !== null);
    }
}
