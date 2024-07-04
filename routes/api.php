<?php

use App\Http\Controllers\Api\RolesPermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group(['namespace' => 'Api'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('notification', 'AuthController@notification');


    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    ######### Roles & Permissison##########

    Route::post('/addPermission', [RolesPermissionController::class, 'addPermission']);
    Route::post('/updatePermission/{id}', [RolesPermissionController::class, 'updatePermission']);
    Route::post('/addRole', [RolesPermissionController::class, 'addRole']);
    Route::post('/updateRole/{id}', [RolesPermissionController::class, 'updateRole']);
});
