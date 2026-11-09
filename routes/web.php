<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\RepasController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users (Administrateur only)
    Route::middleware('role:administrateur')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Roles
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');

        // Students write operations (Administrateur only)
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{eleve}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{eleve}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{eleve}', [StudentController::class, 'destroy'])->name('students.destroy');
    });

    // Students read operations (Administrateur & Gestionnaire)
    Route::middleware('role:administrateur,gestionnaire')->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{eleve}', [StudentController::class, 'show'])->name('students.show');
    });

    // Menus: management operations (Administrateur & Gestionnaire)
    Route::middleware('role:administrateur,gestionnaire')->group(function () {
        Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
        Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
        Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
        Route::patch('/menus/{menu}/publier', [MenuController::class, 'publish'])->name('menus.publish');
    });

    // Menus: read operations (all authenticated; query-scoped for eleve).
    // Keep the parameter route after the literal /menus/create route above.
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/{menu}', [MenuController::class, 'show'])->name('menus.show');

    // Plats (Administrateur & Gestionnaire)
    Route::middleware('role:administrateur,gestionnaire')->group(function () {
        Route::get('/plats', [PlatController::class, 'index'])->name('plats.index');
        Route::get('/plats/create', [PlatController::class, 'create'])->name('plats.create');
        Route::post('/plats', [PlatController::class, 'store'])->name('plats.store');
        Route::get('/plats/{plat}/edit', [PlatController::class, 'edit'])->name('plats.edit');
        Route::put('/plats/{plat}', [PlatController::class, 'update'])->name('plats.update');
        Route::delete('/plats/{plat}', [PlatController::class, 'destroy'])->name('plats.destroy');
    });

    // Repas (Administrateur & Gestionnaire)
    Route::middleware('role:administrateur,gestionnaire')->group(function () {
        Route::get('/repas', [RepasController::class, 'index'])->name('repas.index');
        Route::get('/repas/{repas}', [RepasController::class, 'show'])->name('repas.show');
        Route::patch('/repas/{repas}/marquer-servi', [RepasController::class, 'markServed'])->name('repas.markServed');
    });

    // Reservations: student actions (Élève only)
    Route::middleware('role:eleve')->group(function () {
        Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
        Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
        Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
        Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    });

    // Reservations: read operations (all authenticated; query-scoped)
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');

    // Reservations: attendance validation (Administrateur & Gestionnaire)
    Route::middleware('role:administrateur,gestionnaire')->group(function () {
        Route::patch('/reservations/valider-presence', [ReservationController::class, 'validateAttendance'])->name('reservations.validateAttendance');
    });

    // Paiements (Administrateur & Gestionnaire)
    Route::middleware('role:administrateur,gestionnaire')->group(function () {
        Route::get('/paiements', [PaiementController::class, 'index'])->name('paiements.index');
        Route::get('/paiements/create', [PaiementController::class, 'create'])->name('paiements.create');
        Route::post('/paiements', [PaiementController::class, 'store'])->name('paiements.store');
        Route::get('/paiements/{paiement}/edit', [PaiementController::class, 'edit'])->name('paiements.edit');
        Route::put('/paiements/{paiement}', [PaiementController::class, 'update'])->name('paiements.update');
    });

    // Statistiques (Administrateur & Gestionnaire)
    Route::middleware('role:administrateur,gestionnaire')->group(function () {
        Route::get('/statistiques', [StatisticsController::class, 'index'])->name('statistiques.index');
    });
});
