<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Enquiry;
class EnquiryController extends Controller
{
    // _________________________________________________________________ enquiry _______________________________________________
    public function addEnquiry(Request $request){
        $validator = Validator::make($request->all(), [
            'clientID'=>'required',
            'email'=>['required','email','unique:enquiries,email'],
            'phone'=>['required','min:11','numeric'],
            'name'=>['required'],
            'message'=>['required'],
            'enquiryDate'=>['required','date',],
            'address'=>['required'],
            'course'=>['required'],
        ]);
    
        if($validator->fails()){
            $response = $validator->messages()->first();
            return hresponse(false, null, $response);
        }

        $enquiry =  Enquiry::create([
            'name' => $request->name,
            'email'=> $request->email,
            'clientID'=> $request->clientID,
            'phone'=> $request->phone,
            'address'=> $request->address,
            'message'=> $request->message,
            'enquiryDate'=> $request->enquiryDate,
            'course'=> $request->course,
        ]);

        if($enquiry){
            return hresponse(true, $enquiry, "Enquiry Registerd Succcessfully !!");
        }
        return hresponse(false, null, "Enquiry Not Added !!");
    }
}
