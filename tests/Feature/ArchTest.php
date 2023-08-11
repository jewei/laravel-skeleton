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

test('Controllers')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller');

test('Jobs')
    ->expect('App\Jobs')
    ->toImplement('Illuminate\Contracts\Queue\ShouldQueue');

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
    ->expect(['dd', 'dump', 'var_dump', 'print_f'])
    ->not->toBeUsed();
