<?php

use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\RestController;
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


Route::post('auth/login', [JWTController::class, 'login']);
Route::post('logout', [JWTController::class, 'logout']);
Route::post('register', [JWTController::class, 'register']);





// Route::group(['middleware' => 'jwt'], function () {
//     Route::any('/{controller?}/{methods?}/{folder?}', function ($controller = null, $method = null, $folder = null) {
//         $string_link_to = '\\';
//         $namespaceController = empty($folder)  ? "App\Http\Controllers\API" : "App\Http\Controllers\API${string_link_to}" . ucwords($folder);
//         $uppercase_controller = ucwords($controller) . 'Controller';
//         $classController = "${namespaceController}${string_link_to}" . $uppercase_controller;
//         if (empty($method)) {
//             $method = 'index';
//         }
//         if (class_exists($classController)) {
//             $class = new $classController;
//             if (method_exists($classController, $method)) {

//                 return $class->{$method}($classController);
//             } else {
//                 //page not exist
//                 return response()->json(['statusCode' => 404, 'success' => false, 'message' => 'API not found2']);
//             }
//         } else {
//             //  page not exist
//             return response()->json(['statusCode' => 404, 'success' => false, 'message' => 'API not found 1']);
//         }
//     });
// });




$role_department = 'department';


Route::group(['middleware' => 'jwt'], function () use ($role_department) {
    Route::get('me', [UserController::class, 'user']);
    Route::prefix('users')->group(function () use ($role_department) {
        Route::get('/', [UserController::class, 'getAll'])->middleware($role_department);
    });

    Route::prefix('departments')->group(function () use ($role_department) {
        Route::get('', [DepartmentController::class, 'index'])->middleware($role_department);
        Route::get('/{id}', [DepartmentController::class, 'byId'])->middleware($role_department);
        Route::post('create', [DepartmentController::class, 'create'])->middleware($role_department);
        Route::put('update', [DepartmentController::class, 'update'])->middleware($role_department);
        Route::delete('delete/{id}', [DepartmentController::class, 'delete'])->middleware($role_department);
    });
});
