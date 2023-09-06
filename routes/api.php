<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\PasswordResetController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// Route::get('/student',function(){
//     return "Laravel API";
// });

Route::post('/register',[UserController::class,'register']);

Route::post('/login',[UserController::class,'login']);

Route::post('/admin/login',[AdminController::class,'login']);

Route::post('/client/enquiry/add',[EnquiryController::class, 'addEnquiry']); 


Route::middleware(['auth:sanctum'])->group(function(){

    // Route::get('/students/{id}',[StudentController::class, 'show']);

    // Route::post('/students',[StudentController::class, 'store']);

    Route::post('/logout',[UserController::class, 'logout']);

    Route::get('/loggeduser',[UserController::class, 'loggedUser']);

    Route::post('/changepassword',[UserController::class, 'changePassword']);

    Route::get('/show-all-clients',[UserController::class, 'showAllClients']);

    Route::put('/client/{id}',[UserController::class, 'updateClient']);

    Route::delete('/client/{id}',[UserController::class, 'deleteClient']);

    Route::patch('/client/status/upate/{id}',[UserController::class, 'clientStatusUpdate']);

    Route::post('/role/add',[RoleController::class, 'addRole']);

    Route::get('/role/view/{id}',[RoleController::class, 'viewRole']);

    Route::delete('/role/delete/{id}',[RoleController::class, 'deleteRole']);
    
    Route::patch('/role/update/{id}',[RoleController::class, 'updateRole']);

    Route::get('/permissions',[RoleController::class, 'permissions']);

    Route::get('/rolemodules',[RoleController::class, 'rolesModules']);

});