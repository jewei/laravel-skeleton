<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
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
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
    )
    ->withDeadCodeLevel(42)
    ->withSkip([
        StringClassNameToClassConstantRector::class,
        DisallowedEmptyRuleFixerRector::class,
        StaticArrowFunctionRector::class,
        StaticClosureRector::class,
    ])
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
    ])
    ->withSets([
        LaravelSetList::LARAVEL_110,
    ]);
