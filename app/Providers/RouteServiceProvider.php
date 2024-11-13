<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * 루트 파일을 로드하기 위한 경로 설정
     *
     * @return void
     */
    public function boot()
    {
        // 네임스페이스를 자동으로 등록하려면 아래와 같이 작성
        Route::namespace('App\Http\Controllers')->group(base_path('routes/web.php'));

        
    }
}
