<?php

use Illuminate\Support\Facades\Route;
use App\Htpp\Controllers\WorspaceController;

Route::get('/', function () {
    return view('layouts/app');
})->name('home');

Route::resource('workspaces', WorkspaceController::class);
