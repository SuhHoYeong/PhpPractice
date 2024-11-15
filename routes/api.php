<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InformationController;

Route::post('/information/store', [InformationController::class, 'store'])->name('information.store');
Route::delete('/information/deleteSelected', [InformationController::class, 'deleteSelected'])->name('information.deleteSelected');
Route::get('/information/{id}/edit', [InformationController::class, 'edit'])->name('information.edit');
Route::put('/information/{id}', [InformationController::class, 'update'])->name('information.update');