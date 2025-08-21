<?php

use Inertia\Inertia;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('/admin', [MainController::class, 'showRegister'])->name('register.show'); // ← НОВОЕ
Route::post('/register', [MainController::class, 'register'])->name('register');  
Route::post('/projects', [MainController::class, 'store'])->name('projects.store');
Route::delete('/projects/{project}', [MainController::class, 'destroy'])
    ->name('projects.destroy');
Route::put('edit/projects/{project}', [MainController::class, 'editProject'])
    ->name('projects.update');
   