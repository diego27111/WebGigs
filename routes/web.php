<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ListingController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ListingController::class, 'index'])->name('listings.index');

Route::get('/new', [ListingController::class, 'create'])->name('listings.create');

Route::post('/new', [ListingController::class, 'store'])->name('listings.store');


Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    return view('dashboard', [
        'listings' => $request->user()->listings
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/{listing}', [ListingController::class, 'show'])->name('listings.show');

Route::get('/{listing}/apply', [ListingController::class, 'apply'])->name('listings.apply');

Route::get('/{listing}/edit', [ListingController::class, 'edit'])->name('listing.edit')->middleware('auth');

Route::put('{listing}', [ListingController::class, 'update'])->name('listing.update')->middleware('auth');

// Create Delete Function

Route::delete('{listing}', [ListingController::class, 'destroy'])->name('listing.destroy')->middleware('auth');