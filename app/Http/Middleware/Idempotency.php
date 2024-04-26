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
     * The cache duration in minutes.
     */
    protected int $cacheDuration = 5;

    /**
     * The request instance.
     */
    protected Request $request;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isIdempotentRequest($request) || empty($idempotenceKey = $this->getIdempotencyKey($request))) {
            return $next($request);
        }

        $response = $next($this->request = $request);

        if ($response->isClientError() || $response->isServerError()) {
            return $response;
        }

        // If the response is already cached, return it.
        if ($this->hasCache()) {
            return response(...$this->getCache())
                ->header($this->idempotenceKey, $idempotenceKey)
                ->header($this->statusKey, 'HIT')
                ->header($this->replayKey, 'true');
        }

        // Store the response in cache.
        $this->setCache([
            'content' => (string) $response->getContent(),
            'status' => (int) $response->getStatusCode(),
            'headers' => (array) $response->headers->all(),
        ]);

        $response->headers->set($this->idempotenceKey, $idempotenceKey);
        $response->headers->set($this->statusKey, 'MISS');

        return $response;
    }

    protected function resolveCacheKey(): string
    {
        return sprintf(
            '%s_%s_%s',
            $this->cachePrefix,
            $this->getIdempotencyKey($this->request),
            $this->request->user()?->id ?: $this->request->ip()
        );
    }

    protected function hasCache(): bool
    {
        return Cache::has($this->resolveCacheKey());
    }

    /**
     * Get the cache value.
     */
    protected function getCache(): array
    {
        return (array) Cache::get($this->resolveCacheKey());
    }

    /**
     * Set the cache value.
     */
    protected function setCache(array $value): void
    {
        Cache::put($this->resolveCacheKey(), $value, now()->addMinutes($this->cacheDuration));
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
    protected function getIdempotencyKey(Request $request): string
    {
        return $request->header($this->idempotenceKey, '');
    }
}
