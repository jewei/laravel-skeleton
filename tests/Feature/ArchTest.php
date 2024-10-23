<?php

declare(strict_types=1);

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
    ->toExtend('App\Http\Controllers\Controller');

test('Commands')
    ->expect('App\Console\Commands')
    ->toExtend(\Illuminate\Console\Command::class)
    ->toHaveMethod('handle');

test('Jobs')
    ->expect('App\Jobs')
    ->toImplement(\Illuminate\Contracts\Queue\ShouldQueue::class)
    ->toHaveMethod('handle');

test('Models')
    ->expect('App\Models')
    ->toExtend(\Illuminate\Database\Eloquent\Model::class);

test('ValueObjects')
    ->expect('App\ValueObjects')
    ->toBeReadonly()
    ->toUseNothing()
    ->toExtendNothing()
    ->toImplementNothing();

test('Not debugging statements are left in our code.')
    ->expect(['dd', 'ddd', 'die', 'dump', 'var_dump', 'print_f', 'sleep'])
    ->toBeUsedInNothing();
