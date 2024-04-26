<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap',
        __DIR__.'/config',
        __DIR__.'/public',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withSkipPath(__DIR__.'/bootstrap/cache')
    ->withPhpSets()
    ->withPreparedSets(
        codeQuality: true,
        codingStyle: true,
        privatization: true,
        earlyReturn: true,
    )
    ->withDeadCodeLevel(42)
    ->withTypeCoverageLevel(37)
    // ->withImportNames()
    ->withSkip([
        StringClassNameToClassConstantRector::class,
        DisallowedEmptyRuleFixerRector::class,
        StaticArrowFunctionRector::class,
        StaticClosureRector::class,
    ])
    ->withRules([
        //
    ])
    ->withSets([
        LaravelSetList::LARAVEL_110,
        LaravelSetList::ARRAY_STR_FUNCTIONS_TO_STATIC_CALL,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_ARRAY_STR_FUNCTION_TO_STATIC_CALL,
        LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
        LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
    ]);
