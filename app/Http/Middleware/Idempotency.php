<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

final class Idempotency
{
    /**
     * The header key for the idempotency key.
     */
    private string $idempotenceKey = 'X-Idempotency-Key';

    /**
     * The header key for the idempotency status.
     */
    private string $statusKey = 'X-Idempotency-Status';

    /**
     * The header key for the replay status.
     */
    private string $replayKey = 'X-Idempotency-Is-Replay';

    /**
     * The verbs that are considered idempotent.
     *
     * @var array<int, string>
     */
    private array $verbs = ['POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * The cache prefix for the idempotency key.
     */
    private string $cachePrefix = 'idempotency_key_';

    /**
     * The cache duration in minutes.
     */
    private int $cacheDuration = 5;

    /**
     * The request instance.
     */
    private Request $request;

    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
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

    private function resolveCacheKey(): string
    {
        return sprintf(
            '%s_%s_%s',
            $this->cachePrefix,
            $this->getIdempotencyKey($this->request),
            $this->request->user()?->id ?: $this->request->ip()
        );
    }

    private function hasCache(): bool
    {
        return Cache::has($this->resolveCacheKey());
    }

    /**
     * Get the cache value.
     *
     * @return array{content: string, status: int, headers: array<string, list<string|null>>}
     */
    private function getCache(): array
    {
        return Cache::get($this->resolveCacheKey()); // @phpstan-ignore-line
    }

    /**
     * Set the cache value.
     *
     * @param  array{content: string, status: int, headers: array<string, list<string|null>>}  $value
     */
    private function setCache(array $value): void
    {
        Cache::put($this->resolveCacheKey(), $value, now()->addMinutes($this->cacheDuration));
    }

    /**
     * Check if the request is idempotent.
     */
    private function isIdempotentRequest(Request $request): bool
    {
        return in_array($request->method(), $this->verbs, true);
    }

    /**
     * Get the idempotency key from the request.
     */
    private function getIdempotencyKey(Request $request): string
    {
        return $request->header($this->idempotenceKey, '');
    }
}
