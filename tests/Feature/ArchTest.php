<?php

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
    ->toExtend('Exception');

test('Controllers')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller')
    ->toExtend('App\Http\Controllers\Controller');

test('Commands')
    ->expect('App\Console\Commands')
    ->toExtend('Illuminate\Console\Command')
    ->toHaveMethod('handle');

test('Jobs')
    ->expect('App\Jobs')
    ->toImplement('Illuminate\Contracts\Queue\ShouldQueue')
    ->toHaveMethod('handle');

test('Models')
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model');

test('ValueObjects')
    ->expect('App\ValueObjects')
    ->toBeReadonly()
    ->toUseNothing()
    ->toExtendNothing()
    ->toImplementNothing();

test('Not debugging statements are left in our code.')
    ->expect(['dd', 'ddd', 'die', 'dump', 'var_dump', 'print_f', 'sleep'])
    ->toBeUsedInNothing();
