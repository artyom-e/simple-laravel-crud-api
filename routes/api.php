<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use App\Http\Middleware\EnsureGuest;
use App\Http\Middleware\TaskRelatesToList;
use Illuminate\Support\Facades\Route;

Route::middleware(EnsureGuest::class)->group(function () {
    Route::post('sign-in', [AuthController::class, 'signIn']);
    Route::post('sign-up', [AuthController::class, 'signUp']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('sign-out', [AuthController::class, 'signOut']);

    Route::prefix('lists')->controller(TaskListController::class)->group(function () {
        Route::post('', 'store');
        Route::get('', 'index');
        Route::get('{list}', 'show')->can('view', 'list');
        Route::put('{list}', 'update')->can('update', 'list');
        Route::delete('{list}', 'destroy')->can('delete', 'list');

        Route::prefix('{list}/tasks')->controller(TaskController::class)
            ->middleware(TaskRelatesToList::class)
            ->group(function () {
                Route::post('', 'store')->can('storeTask', 'list');
                Route::get('', 'index')->can('viewTasks', 'list');
                Route::get('{task}', 'show')->can('view', 'task');
                Route::put('{task}', 'update')->can('update', 'task');
                Route::delete('{task}', 'destroy')->can('delete', 'task');
            });
    });
});
