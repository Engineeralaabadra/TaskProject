<?php

use App\Http\Controllers\API\Admin\RoleController;
use App\Http\Controllers\API\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('clear-cache',function(){
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('route-cache',function(){
    Artisan::call('route:clear');
    return "Cache is routed";
});
Route::get('config-cache',function(){
    Artisan::call('config:clear');
    return "Cache is configed";
});
Route::get('migrate',function(){
    Artisan::call('migrate', ["--force" => true ]);
});

             ############################admin routes######################################

// Route::group(['middleware'=>'auth:sanctum'],function(){
    Route::group(['prefix'=>'admin'],function(){
        Route::group(['prefix'=>'users'],function(){
            Route::get('index', [UserController::class,'index']);
            Route::post('store', [UserController::class,'store']);
            Route::post('update/{id}', [UserController::class,'update']);
            Route::get('show/{id}', [UserController::class,'show']);
            Route::get('destroy/{id}', [UserController::class,'delete']);
        });
        Route::group(['prefix'=>'roles'],function(){
            Route::get('index', [RoleController::class,'index']);
            Route::post('store', [RoleController::class,'store']);
            Route::post('update/{id}', [RoleController::class,'update']);
            Route::get('show/{id}', [RoleController::class,'show']);
            Route::get('destroy/{id}', [RoleController::class,'delete']);
        });
    });
// });

// require __DIR__.'/user.php';
