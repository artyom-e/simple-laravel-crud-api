<?php

declare(strict_types=1);

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use App\Http\Middleware\TaskRelatesToList;
use Illuminate\Support\Facades\Route;

Route::resource('lists', TaskListController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy']);

Route::resource('lists.tasks', TaskController::class, ['middleware' => [TaskRelatesToList::class]])
    ->only(['index', 'show', 'store', 'update', 'destroy']);
