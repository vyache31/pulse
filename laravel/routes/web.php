<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('layouts/public');
})->name('public');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
	Route::resource('workspaces', WorkspaceController::class);
	Route::get('/home', function () {
    	return view('layouts/app');
	})->name('app');
	Route::resource('workspaces.columns', ColumnController::class);
	Route::resource('workspaces.columns.tasks', TaskController::class);
});

require __DIR__.'/auth.php';
