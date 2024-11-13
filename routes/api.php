<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InformationController;

Route::post('/information/store', [InformationController::class, 'store'])->name('information.store');
Route::delete('/information/deleteSelected', [InformationController::class, 'deleteSelected'])->name('information.deleteSelected');
// Route::put('/information/{id}', [InformationController::class, 'update'])->name('information.update');
// // 특정 게시물 정보를 조회하는 GET 라우트 추가
// Route::get('/api/information/{id}', [InformationController::class, 'show'])->name('information.show');