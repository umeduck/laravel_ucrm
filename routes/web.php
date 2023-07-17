<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InertiaTestController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Models\Customer;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::resource('items',ItemController::class)
->middleware(['auth','verified']);

Route::resource('customers',CustomerController::class)
->middleware(['auth','verified']);

Route::resource('purchases',PurchaseController::class)
->middleware(['auth','verified']);

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

route::get('/component-test',function(){
    return Inertia::render('ComponentTest');
});

route::get('/inertia/index',[InertiaTestController::class, 'index'])->name('inertia.index');
route::get('/inertia/create',[InertiaTestController::class, 'create'])->name('inertia.create');
route::post('/inertia',[InertiaTestController::class, 'store'])->name('inertia.store');
route::get('/inertia/show/{id}',[InertiaTestController::class, 'show'])->name('inertia.show');
route::delete('/inertia/{id}',[InertiaTestController::class, 'delete'])->name('inertia.delete');


require __DIR__.'/auth.php';
