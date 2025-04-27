<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Flag that determines if the application is running in production.
        $production = $this->app->environment('production');

        // Prevent accidental data corruption.
        DB::prohibitDestructiveCommands($production);

        // Automatically eager load relationships and unguard models.
        Model::unguard();
        Model::shouldBeStrict(! $production);
        Model::automaticallyEagerLoadRelationships();

        // Always use immutable dates.
        Date::use(CarbonImmutable::class);

        // Loosen password rules in non-production environments.
        Password::defaults(
            fn () => $production
                ? Password::min(8)->max(255)->uncompromised()
                : null
        );

        // Vite related.
        Vite::macro('image', fn (string $asset) => Vite::asset("resources/images/{$asset}"));
        Vite::useAggressivePrefetching();

        // Convert array to arrow notation.
        // Example:
        // [
        //     'a' => [
        //         'b' => 'c',
        //     ],
        // ]
        // to
        // [
        //     'a->b' => 'c',
        // ]
        Arr::macro('arrow', fn (array $array): array => collect($array)->dot()
            ->keyBy(fn ($value, $key): string => str_replace('.', '->', $key))
            ->toArray());
    }
}
