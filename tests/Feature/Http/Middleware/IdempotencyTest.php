<?php

use App\Http\Middleware\Idempotency;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    // Create a dummy response.
    $this->dummyResponse = new Response('dummy response', 200, ['X-Custom-Header' => 'dummy-header']);

    // Define the callback that wants to use idempotency.
    $this->idempotenceCallback = function () {
        return (new Idempotency())->handle(
            tap(
                Request::create('/dummy-test-route', 'POST'),
                fn (Request $request) => $request->headers->set('X-Idempotency-Key', 'dummy-key')
            ),
            fn () => $this->dummyResponse
        );
    };

    // Define the callback that does not want to use idempotency.
    $this->normalCallback = function () {
        return (new Idempotency())->handle(
            Request::create('/dummy-test-route', 'GET'),
            fn () => $this->dummyResponse
        );
    };
});

test('idempotency middleware handles idempotent request', function () {
    // Call the handle method of the middleware
    $response = call_user_func($this->idempotenceCallback);

    // Assert that the response is the same as the dummy response.
    expect($response->status())->toBe(200);
    expect($response->content())->toBe('dummy response');

    // Assert that the response headers are set correctly.
    expect($response->headers->get('X-Idempotency-Key'))->toBe('dummy-key');
    expect($response->headers->get('X-Idempotency-Status'))->toBe('MISS');
    expect($response->headers->get('X-Idempotency-Is-Replay'))->toBeNull();
    expect($response->headers->get('X-Custom-Header'))->toBe('dummy-header');

    // Assert that the response is stored in cache
    expect(Cache::has('idempotency_key__dummy-key_127.0.0.1'))->toBeTrue();
});

test('idempotency middleware handles non-idempotent request', function () {
    // Call the handle method of the middleware
    $response = call_user_func($this->normalCallback);

    // Assert that the response is the same as the dummy response
    expect($response)->toBe($this->dummyResponse);

    // Assert that the response headers are not set
    expect($response->headers->has('X-Idempotency-Key'))->toBeFalse();
    expect($response->headers->has('X-Idempotency-Status'))->toBeFalse();
    expect($response->headers->has('X-Idempotency-Is-Replay'))->toBeFalse();

    // Assert that the response is not stored in cache
    expect(Cache::has('idempotency_key__dummy-key_127.0.0.1'))->toBeFalse();
});

test('idempotency middleware handles replay request', function () {
    // Call the handle method of the middleware.
    call_user_func($this->idempotenceCallback);

    // Assert that the response is stored in cache.
    expect(Cache::has('idempotency_key__dummy-key_127.0.0.1'))->toBeTrue();

    $response = call_user_func($this->idempotenceCallback);

    // Assert that the response is the same as the cached response.
    expect($response->status())->toBe(200);
    expect($response->content())->toBe('dummy response');
    expect($response->headers->get('X-Idempotency-Key'))->toBe('dummy-key');
    expect($response->headers->get('X-Idempotency-Status'))->toBe('HIT');
    expect($response->headers->get('X-Idempotency-Is-Replay'))->toBe('true');
    expect($response->headers->get('X-Custom-Header'))->toBe('dummy-header');

    // Assert that the response is still stored in cache.
    expect(Cache::has($cacheKey = 'idempotency_key__dummy-key_127.0.0.1'))->toBeTrue();
});
