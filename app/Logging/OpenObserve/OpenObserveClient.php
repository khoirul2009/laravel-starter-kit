<?php

namespace App\Logging\OpenObserve;

use Illuminate\Support\Facades\Http;
use Throwable;

class OpenObserveClient
{
    public function __construct(
        protected string $url,
        protected string $organization,
        protected string $stream,
        protected ?string $email,
        protected ?string $password,
        protected int $timeout = 3,
    ) {}

    public function send(array $records): bool
    {
        if (empty($records)) {
            return true;
        }

        $endpoint = rtrim($this->url, '/')
            . '/api/' . rawurlencode($this->organization)
            . '/' . rawurlencode($this->stream)
            . '/_json';

        try {
            $response = Http::withBasicAuth((string) $this->email, (string) $this->password)
                ->timeout($this->timeout)
                ->acceptJson()
                ->asJson()
                ->post($endpoint, $records);

            return $response->successful();
        } catch (Throwable $e) {
            return false;
        }
    }
}
