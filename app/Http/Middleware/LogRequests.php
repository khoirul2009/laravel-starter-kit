<?php

namespace App\Http\Middleware;

use App\Facades\OpenObserve;
use App\Logging\OpenObserve\RequestContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    public function __construct(protected RequestContext $context) {}

    public function handle(Request $request, Closure $next): Response
    {
        $requestId = $request->header('X-Request-Id') ?: (string) Str::uuid();

        $this->context->set(
            requestId: $requestId,
            userId:    $request->user()?->id,
            ip:        $request->ip(),
            method:    $request->method(),
            url:       $request->fullUrl(),
            startedAt: microtime(true),
        );

        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Request-Id', $requestId);

        return $response;
    }

    public function terminate(Request $request, Response $response): void
    {
        OpenObserve::info('http_request', [
            'status'        => $response->getStatusCode(),
            'response_size' => strlen((string) $response->getContent()),
        ]);
    }
}
