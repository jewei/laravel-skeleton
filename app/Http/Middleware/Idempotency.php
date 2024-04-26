<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class Idempotency
{
    /**
     * The header key for the idempotency key.
     */
    protected string $idempotenceKey = 'X-Idempotency-Key';

    /**
     * The header key for the idempotency status.
     */
    protected string $statusKey = 'X-Idempotency-Status';

    /**
     * The header key for the replay status.
     */
    protected string $replayKey = 'X-Idempotency-Is-Replay';

    /**
     * The verbs that are considered idempotent.
     */
    protected array $verbs = ['POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * The cache prefix for the idempotency key.
     */
    protected string $cachePrefix = 'idempotency_key_';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isIdempotentRequest($request) || is_null($idempotenceKey = $this->getIdempotencyKey($request))) {
            return $next($request);
        }

        $response = $next($request);

        if ($response->isClientError() || $response->isServerError()) {
            return $response;
        }

        $cacheKey = sprintf("{$this->cachePrefix}_%s_{$idempotenceKey}", $request->user()?->id ?: $request->ip());

        // If the response is already cached, return it.
        if (Cache::has($cacheKey)) {
            $cache = Cache::get($cacheKey);

            return response($cache['content'], $cache['status'], $cache['headers'])
                ->header($this->idempotenceKey, $idempotenceKey)
                ->header($this->statusKey, 'HIT')
                ->header($this->replayKey, 'true');
        }

        // Store the response in cache.
        Cache::put($cacheKey, [
            'content' => $response->getContent(),
            'status' => $response->getStatusCode(),
            'headers' => $response->headers->all(),
        ], now()->addMinutes(5));

        $response->headers->set($this->idempotenceKey, $idempotenceKey);
        $response->headers->set($this->statusKey, 'MISS');

        return $response;
    }

    /**
     * Check if the request is idempotent.
     */
    protected function isIdempotentRequest(Request $request): bool
    {
        return in_array($request->method(), $this->verbs);
    }

    /**
     * Get the idempotency key from the request.
     */
    protected function getIdempotencyKey(Request $request): ?string
    {
        return $request->header($this->idempotenceKey);
    }
}
