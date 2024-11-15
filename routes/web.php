<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InformationController;

Route::resource('information', InformationController::class);
// routes/api.php
Route::get('/information/{id}', [InformationController::class, 'show']);  // 정보 조회
Route::post('/information', [InformationController::class, 'store']);  // 게시글 등록

