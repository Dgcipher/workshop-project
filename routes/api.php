<?php

use App\Enums\PrivacyEnums;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
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

Route::post('/login', [UserController::class, 'login']);

Route::middleware('UserApiAuth')->group(function () {

    Route::prefix('user-management')->group(function () {
        Route::prefix('/users')->middleware('UserAPIAuthorization:' . PrivacyEnums::USERS)->group(function () {
            Route::get('/', [UserController::class, 'search'])->middleware('UserAPIAuthorization:' . PrivacyEnums::USERS . ',read');
            Route::post('/', [UserController::class, 'create'])->middleware('UserAPIAuthorization:' . PrivacyEnums::USERS . ',create');
            Route::prefix('/{id}')->group(function () {
                Route::get('/', [UserController::class, 'read'])->middleware('UserAPIAuthorization:' . PrivacyEnums::USERS . ',read');
                Route::put('/', [UserController::class, 'update'])->middleware('UserAPIAuthorization:' . PrivacyEnums::USERS . ',update');
                Route::delete('/', [UserController::class, 'delete'])->middleware('UserAPIAuthorization:' . PrivacyEnums::USERS . ',delete');
                Route::prefix('/roles/{role_id}')->group(function () {
                    Route::post('/', [UserController::class, 'assignRole'])->middleware('UserAPIAuthorization:' . PrivacyEnums::USERS . ',assign_role');
                    Route::delete('/', [UserController::class, 'unassignRole'])->middleware('UserAPIAuthorization:' . PrivacyEnums::USERS . ',unassign_role');
                });
            });
        });
    });

    Route::prefix('role-management')->group(function () {
        Route::prefix('/roles')->group(function () {
            Route::get('/', [RolesController::class, 'search'])->middleware('UserAPIAuthorization:' . PrivacyEnums::ROLES . ',read');
            Route::post('/', [RolesController::class, 'create'])->middleware('UserAPIAuthorization:' . PrivacyEnums::ROLES . ',create');
            Route::prefix('/{id}')->group(function () {
                Route::get('/', [RolesController::class, 'read'])->middleware('UserAPIAuthorization:' . PrivacyEnums::ROLES . ',read');
                Route::put('/', [RolesController::class, 'update'])->middleware('UserAPIAuthorization:' . PrivacyEnums::ROLES . ',update');
                Route::delete('/', [RolesController::class, 'delete'])->middleware('UserAPIAuthorization:' . PrivacyEnums::ROLES . ',delete');
                Route::prefix('/permissions')->group(function () {
                    Route::get('/', [RolesController::class, 'getPermissions'])->middleware('UserAPIAuthorization:' . PrivacyEnums::ROLES . ',get_permission');
                    Route::post('/assign', [RolesController::class, 'assignPermissions'])->middleware('UserAPIAuthorization:' . PrivacyEnums::ROLES . ',assign_permission');
                    Route::post('/unassign', [RolesController::class, 'unassignPermissions'])->middleware('UserAPIAuthorization:' . PrivacyEnums::ROLES . ',unassign_permission');
                    Route::prefix('/capabilities')->group(function () {
                        Route::put('/{privacy}', [RolesController::class, 'updateCapabilities'])->middleware('UserAPIAuthorization:' . PrivacyEnums::ROLES . ',update_capability');
                        Route::post('/{privacy}', [RolesController::class, 'assignCapabilities'])->middleware('UserAPIAuthorization:' . PrivacyEnums::ROLES . ',assign_capability');
                        Route::delete('/{privacy}', [RolesController::class, 'unassignCapabilities'])->middleware('UserAPIAuthorization:' . PrivacyEnums::ROLES . ',unassign_capability');
                    });
                });
            });
        });
    });

    Route::prefix('category-management')->group(function () {
        Route::prefix('/categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->middleware('UserAPIAuthorization:' . PrivacyEnums::CATEGORIES . ',read');
            Route::post('/', [CategoryController::class, 'create'])->middleware('UserAPIAuthorization:' . PrivacyEnums::CATEGORIES . ',create');
            Route::prefix('/{id}')->group(function () {
                Route::get('/', [CategoryController::class, 'read'])->middleware('UserAPIAuthorization:' . PrivacyEnums::CATEGORIES . ',read');
                Route::put('/', [CategoryController::class, 'update'])->middleware('UserAPIAuthorization:' . PrivacyEnums::CATEGORIES . ',update');
                Route::delete('/', [CategoryController::class, 'delete'])->middleware('UserAPIAuthorization:' . PrivacyEnums::CATEGORIES . ',delete');
            });
        });
    });

    Route::prefix('post-management')->group(function () {
        Route::prefix('/posts')->group(function () {
            Route::get('/', [PostController::class, 'index'])->middleware('UserAPIAuthorization:' . PrivacyEnums::POSTS . ',read');
            Route::post('/', [PostController::class, 'create'])->middleware('UserAPIAuthorization:' . PrivacyEnums::POSTS . ',create');
            Route::prefix('/{id}')->group(function () {
                Route::get('/', [PostController::class, 'read'])->middleware('UserAPIAuthorization:' . PrivacyEnums::POSTS . ',read');
                Route::post('/', [PostController::class, 'update'])->middleware('UserAPIAuthorization:' . PrivacyEnums::POSTS . ',update');
                Route::delete('/', [PostController::class, 'delete'])->middleware('UserAPIAuthorization:' . PrivacyEnums::POSTS . ',delete');
            });
        });
    });
});
