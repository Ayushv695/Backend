<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>['required','email'],
            'password'=>['required']
        ]);

        if($validator->fails()){
            $response = $validator->messages()->first();
            return hresponse(false, null, $response);
        }

        $admin = Admin::where('email',$request->email)->first();
        if($admin){
            if(Hash::check($request->password, $admin->password) && $admin->status == "Active"){
                $token = $admin->createToken('mytoken')->plainTextToken;
                $admin->token=$token;
                $admin->isSuperAdmin= $admin->role == 'SuperAdmin' ? true : false;
    
                return hresponse(true, $admin, 'Login Successful');
            }
            $message = $admin->status == "Active" ? 'Wrong Password' : "Your status is not active please contact with SuperAdmin";
    
            return hresponse(false, null, $message);
        }
        return hresponse(false, null, "Admin Not Found !!");
    }
}
