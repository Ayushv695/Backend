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

// _________________________________Enquiry_____________________________________________

Route::middleware(['auth:sanctum'])->group(function(){

    Route::post('/enquiry/add',[EnquiryController::class, 'addEnquiry']); 

    Route::delete('/enquiry/delete/{id}',[EnquiryController::class, 'deleteEnquiry']);

    Route::get('/all-enquiries',[EnquiryController::class, 'showEnquiries']);

    Route::patch('/enquiry/update/step/{id}',[EnquiryController::class, 'updateEnquiryStep']);

});


// _____________________________ Role____________________________________________________
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/role/add',[RoleController::class, 'addRole']);

    Route::get('/role/view/{id}',[RoleController::class, 'viewRole']);

    Route::delete('/role/delete/{id}',[RoleController::class, 'deleteRole']);
    
    Route::patch('/role/update/{id}',[RoleController::class, 'updateRole']);

    Route::get('/roles-modules-permissions',[RoleController::class, 'rolesModulesPermissions']);
});

// _______________________________ Client _______________________________________________
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

});