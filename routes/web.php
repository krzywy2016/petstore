<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;


Route::get('/', [PetController::class, 'index'])->name('pets.index'); // tabela ze wszystkimi zwierzakami

Route::post('/pets', [PetController::class, 'store'])->name('pets.store'); // dodawanie nowego zwierzaka
Route::get('/pets/{id}', [PetController::class, 'show'])->name('pets.show'); // pobieranie zwierzaka o określonym ID
Route::put('/pets/{id}', [PetController::class, 'update'])->name('pets.update'); // edytowanie zwierzaka o określonym ID
Route::delete('/pets/{id}', [PetController::class, 'destroy'])->name('pets.destroy'); // usuwanie zwierzaka
Route::delete('/removealll', [PetController::class, 'destroyAll'])->name('deleteAllPets'); // usuwanie wszystkich zwierzaków
