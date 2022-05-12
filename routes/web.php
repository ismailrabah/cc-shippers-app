<?php

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
        'shippers_count' => 00,
        'contact_count' => 00,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(["middleware"=>['auth:sanctum', 'verified']], function () {

    Route::get('shippers', [\App\Http\Controllers\ShipperController::class , 'index'])->name('shippers.index');
    Route::get('shippers/search', [\App\Http\Controllers\ShipperController::class , 'search'])->name('shippers.search');

    Route::get('shippers/create', [\App\Http\Controllers\ShipperController::class , 'create'])->name('shippers.create');
    Route::post('shippers/store', [\App\Http\Controllers\ShipperController::class , 'store'])->name('shippers.store');

    Route::get('shippers/show/{id}', [\App\Http\Controllers\ShipperController::class , 'show'])->name('shippers.show');

    Route::get('shippers/edit/{id}', [\App\Http\Controllers\ShipperController::class , 'edit'])->name('shippers.edit');
    Route::put('shippers/update/{id}', [\App\Http\Controllers\ShipperController::class , 'update'])->name('shippers.update');
    
    Route::delete('shippers/delete/{id}', [\App\Http\Controllers\ShipperController::class , 'destroy'])->name('shippers.delete');

    
    Route::get('contacts/create/{shipper_id}', [\App\Http\Controllers\ContactController::class , 'create'])->name('contacts.create');
    Route::get('contacts/edit/{id}', [\App\Http\Controllers\ContactController::class , 'edit'])->name('contacts.edit');
    Route::put('contacts/toggle_primary/{contact_id}', [\App\Http\Controllers\ContactController::class , 'toggle_primary'])->name('contacts.toggle_primary');

    
    Route::apiResource('/contacts', \App\Http\Controllers\ContactController::class)->parameters(["contacts" => "contact"]);



});


require __DIR__.'/auth.php';
