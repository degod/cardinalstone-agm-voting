<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AgmController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShareholderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/companies/{id}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');

        Route::get('/agms', [AgmController::class, 'index'])->name('agms.index');
        Route::post('/agms', [AgmController::class, 'store'])->name('agms.store');
        Route::get('/agms/{id}', [AgmController::class, 'view'])->name('agms.view');
        Route::get('/agms/{id}/edit', [AgmController::class, 'edit'])->name('agms.edit');
        Route::put('/agms/{id}', [AgmController::class, 'update'])->name('agms.update');
        Route::delete('/agms/{id}', [AgmController::class, 'destroy'])->name('agms.destroy');

        Route::get('/shareholders', [ShareholderController::class, 'index'])->name('shareholders.index');
        Route::post('/shareholders', [ShareholderController::class, 'store'])->name('shareholders.store');
        Route::get('/shareholders/{id}/edit', [ShareholderController::class, 'edit'])->name('shareholders.edit');
        Route::put('/shareholders/{id}', [ShareholderController::class, 'update'])->name('shareholders.update');
        Route::delete('/shareholders/{id}', [ShareholderController::class, 'destroy'])->name('shareholders.destroy');

        Route::get('/agendas', [AgendaController::class, 'index'])->name('agendas.index');
        Route::post('/agendas', [AgendaController::class, 'store'])->name('agendas.store');
        Route::get('/agendas/{id}', [AgendaController::class, 'view'])->name('agendas.view');
        Route::get('/agendas/{id}/edit', [AgendaController::class, 'edit'])->name('agendas.edit');
        Route::put('/agendas/{id}', [AgendaController::class, 'update'])->name('agendas.update');
        Route::delete('/agendas/{id}', [AgendaController::class, 'destroy'])->name('agendas.destroy');

        Route::get('/votes', [VoteController::class, 'index'])->name('votes.index');
        Route::delete('/votes/{id}', [VoteController::class, 'destroy'])->name('votes.destroy');
    });

    Route::get('/vote', [VoteController::class, 'vote'])->name('vote');
    Route::post('/votes', [VoteController::class, 'store'])->name('votes.store');
});
