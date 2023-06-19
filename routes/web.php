<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InertiaTestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

route::get('/inertia-test',function(){
    return Inertia::render('InertiaTest');
});

route::get('/inertia/index',[InertiaTestController::class, 'index'])->name('inertia.index');
route::get('/inertia/create',[InertiaTestController::class, 'create'])->name('inertia.create');
route::post('/inertia',[InertiaTestController::class, 'store'])->name('inertia.store');
route::get('/inertia/show/{id}',[InertiaTestController::class, 'show'])->name('inertia.show');


require __DIR__.'/auth.php';
