<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        // Always use immutable dates.
        Date::use(CarbonImmutable::class);

        // Strict Model.
        Model::shouldBeStrict(! app()->isProduction());

        // Log a warning if we spend more than a total of 2000ms querying.
        DB::whenQueryingForLongerThan(2000, function (Connection $connection) {
            Log::warning("Database queries exceeded 2 seconds on {$connection->getName()}");
        });

        // Log a warning if we spend more than 1000ms on a single query.
        DB::listen(function (QueryExecuted $query) {
            if ($query->time > 1000) {
                Log::warning('An individual database query exceeded 1 second.', [
                    'sql' => $query->sql,
                ]);
            }
        });

        // Enforce a morph map instead of making it optional.
        // Relation::enforceMorphMap([
        //     'user' => \App\User::class,
        //     'post' => \App\Post::class,
        // ]);
    }
}
