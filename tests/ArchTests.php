<?php

declare(strict_types=1);

arch()
    ->expect('App')
    ->toUseStrictTypes()
    ->toUseStrictEquality()
    ->not->toUse(['die', 'dd', 'dump', 'var_dump', 'exit', 'sleep', 'usleep']);

arch()->preset()->php();
arch()->preset()->laravel();
arch()->preset()->security();

test('Actions')
    ->expect('App\Actions')
    ->toBeInvokable();

test('Concerns')
    ->expect('App\Concerns')
    ->toBeTraits();

test('Contracts')
    ->expect('App\Contracts')
    ->toBeInterfaces();

test('Enums')
    ->expect('App\Enums')
    ->toBeEnums();

test('Exceptions')
    ->expect('App\Exceptions')
    ->toExtend(\Exception::class);

test('Controllers')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller')
    ->toExtend(\App\Http\Controllers\Controller::class);

test('Commands')
    ->expect('App\Console\Commands')
    ->toExtend(\Illuminate\Console\Command::class)
    ->toHaveMethod('handle');

test('Jobs')
    ->expect('App\Jobs')
    ->toImplement(\Illuminate\Contracts\Queue\ShouldQueue::class)
    ->toHaveMethod('handle');

test('ValueObjects')
    ->expect('App\ValueObjects')
    ->toBeReadonly()
    ->toUseNothing()
    ->toExtendNothing()
    ->toImplementNothing();
