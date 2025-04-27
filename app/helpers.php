<?php

declare(strict_types=1);

if (! function_exists('current_user')) {
    /**
     * Get the currently authenticated user, or null if not authenticated.
     */
    function current_user(): ?\App\Models\User
    {
        // Use Laravel's Auth facade to retrieve the current user.
        // The once() helper ensures the user is only retrieved once per request.
        return once(static fn () => \Illuminate\Support\Facades\Auth::user());
    }
}

if (! function_exists('type')) {
    /**
     * Create a new type instance.
     *
     * @template TVariable
     *
     * @param  TVariable  $variable
     * @return \PinkaryProject\TypeGuard\Type<TVariable>
     */
    function type(mixed $variable): \PinkaryProject\TypeGuard\Type
    {
        return new \PinkaryProject\TypeGuard\Type($variable);
    }
}
