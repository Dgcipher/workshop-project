<?php

use App\Enums\PrivacyEnums;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
/*eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYzg5NDM0YzU0NDBjZjBiYjI4ZGYwNTQ2ZjNhMjdkN2RiOGQ4ZGY1NWE5MTdiZDQ5MWE2ODdhNjliZjc4OTI0MjEyN2FkMGFmNzdlNmY0MmEiLCJpYXQiOjE2NzMwOTY1MDQuNTQ1NTQ0LCJuYmYiOjE2NzMwOTY1MDQuNTQ1NTQ2LCJleHAiOjE2NzMxMDAxMDUuNTMxODEsInN1YiI6IjEiLCJzY29wZXMiOltdfQ.O1yrWi0pWyHNkHFJqnCluzmzH40v-F_eveIU34pQ5Cjsr2hFR8ZEGY9Ts4f0Ezt3uNTh0REL268KP6JUDNlWli6T7ehuIdh1sQ38thuEYyuGaq5FHN60aPySGCmeZa1X2BIHa6ZTQHCbgFNdxyq0LxFpm_ev9FGiQxjySCTh5Uwofn5ZHd-vqarmcrgUQ6x5xr8m2HKn_bE3B10hE45I93QkUHPtuGsjw5SqFpnTPaey-8kzNU_lXwpoh6pPAsex3-shw7NK2zSQhXA_bB3n-f-bkwjcwSrj6po18Xi6eVHVYx2dHUZTDa6njpZJeo1MpWk2axq0DEOxaB4GdgMY4oD4JLGoahPc6AFyFQOmbKUBURGucceSO5tT3KH-Pl7Hh3t1QbJ5uMwMm1lMDFt14pKrwpnXOzJ3ZL8g3HS_g37cKGkWvBbSMFwAAuGuybEd-QzYPu0cpOKWwiQgCal-6AoWwa0XLCXeS8vCdWiwNsPfU-5AijzQfbo6oONikbs0EzyFnuIC36XEI9a3tmctBvyg-Z0pwld_h6P_H7Q2OQ12JlEe7HMP98d_BXVfjLF_H8FbZWWeuDkvdmimI9PPipWgfz2DqTlS2c6lG0oxjHPY-GN80SrwXECW65kres9c0H8HSKPWCC3YpImWAQznumNInH-_iLSUKvd6yKHeeNs*/
Route::post('/login', [UserController::class, 'login']);

Route::middleware('UserApiAuth')->group(function () {

    Route::prefix('user-management')->group(function () {
        Route::prefix('/users')->middleware('UserAPIAuthorization:'.PrivacyEnums::USERS)->group(function () {
            Route::get('/', [UserController::class, 'search'])->middleware('UserAPIAuthorization:'.PrivacyEnums::USERS.',read');
            Route::post('/', [UserController::class, 'create'])->middleware('UserAPIAuthorization:'.PrivacyEnums::USERS.',create');
            Route::prefix('/{id}')->group(function () {
                Route::get('/', [UserController::class, 'read'])->middleware('UserAPIAuthorization:'.PrivacyEnums::USERS.',read');
                Route::put('/', [UserController::class, 'update'])->middleware('UserAPIAuthorization:'.PrivacyEnums::USERS.',update');
                Route::delete('/', [UserController::class, 'delete'])->middleware('UserAPIAuthorization:'.PrivacyEnums::USERS.',delete');
                Route::prefix('/roles/{role_id}')->group(function () {
                    Route::post('/', [UserController::class, 'assignRole'])->middleware('UserAPIAuthorization:'.PrivacyEnums::USERS.',assign_role');
                    Route::delete('/', [UserController::class, 'unassignRole'])->middleware('UserAPIAuthorization:'.PrivacyEnums::USERS.',unassign_role');
                });
            });
        });
    });

    Route::prefix('role-management')->group(function () {
        Route::prefix('/roles')->group(function () {
            Route::get('/', [RolesController::class, 'search'])->middleware('UserAPIAuthorization:'.PrivacyEnums::ROLES.',read');
            Route::post('/', [RolesController::class, 'create'])->middleware('UserAPIAuthorization:'.PrivacyEnums::ROLES.',create');
            Route::prefix('/{id}')->group(function () {
                Route::get('/', [RolesController::class, 'read'])->middleware('UserAPIAuthorization:'.PrivacyEnums::ROLES.',read');
                Route::put('/', [RolesController::class, 'update'])->middleware('UserAPIAuthorization:'.PrivacyEnums::ROLES.',update');
                Route::delete('/', [RolesController::class, 'delete'])->middleware('UserAPIAuthorization:'.PrivacyEnums::ROLES.',delete');
                Route::prefix('/permissions')->group(function () {
                    Route::get('/', [RolesController::class, 'getPermissions'])->middleware('UserAPIAuthorization:'.PrivacyEnums::ROLES.',get_permission');
                    Route::post('/assign', [RolesController::class, 'assignPermissions'])->middleware('UserAPIAuthorization:'.PrivacyEnums::ROLES.',assign_permission');
                    Route::post('/unassign', [RolesController::class, 'unassignPermissions'])->middleware('UserAPIAuthorization:'.PrivacyEnums::ROLES.',unassign_permission');
                    Route::prefix('/capabilities')->group(function () {
                        Route::put('/{privacy}',[RolesController::class,'updateCapabilities'])->middleware('UserAPIAuthorization:'.PrivacyEnums::ROLES.',update_capability');
                        Route::post('/{privacy}',[RolesController::class,'assignCapabilities'])->middleware('UserAPIAuthorization:'.PrivacyEnums::ROLES.',assign_capability');
                        Route::delete('/{privacy}',[RolesController::class,'unassignCapabilities'])->middleware('UserAPIAuthorization:'.PrivacyEnums::ROLES.',unassign_capability');
                    });
                });
            });
        });
    });
    Route::prefix('post-management')->group(function (){
        Route::prefix('/posts')->group(function (){
            Route::get('/',[\App\Http\Controllers\PostController::class, 'index'])->middleware('UserAPIAuthorization:'.PrivacyEnums::POSTS.',read');
            Route::post('/',[\App\Http\Controllers\PostController::class, 'store'])->middleware('UserAPIAuthorization:'.PrivacyEnums::POSTS.',create');

        });
    });
});
