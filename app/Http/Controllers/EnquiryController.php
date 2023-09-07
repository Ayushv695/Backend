<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Enquiry;
class EnquiryController extends Controller
{
    // _________________________________________________________________ enquiry _______________________________________________
    
    public function showEnquiries(){
        $enquiries = Enquiry::all()->toArray();
        if($enquiries)
        {
            return hresponse(true, $enquiries, "All Available Enquiries !!");
        }
        return hresponse(false, null, "No Enquirie Aailable !!");
    }

    public function updateEnquiryStep(Request $request , string $id){
        $validator = Validator::make($request->all(),[
            'step'=> 'required',
        ]);
        if($validator->fails()){
            $response = $validator->messages()->first();
            return hresponse(false, null, $response);
        }

        $enquiry = Enquiry::find($id);
        if($enquiry)
        {
            $enquiry = $enquiry->first();
            $enquiry->step = $request->step;
            $enquiry->save();
            return hresponse(true, $enquiry, "Enquiry Step Updated !!");
        }
        return hresponse(false, null, "Enquirie Not Found !!");
    }

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

    public function deleteEnquiry(String $id){
        $enquiry = Enquiry::find($id);
        if($enquiry){
            $enquiry->first()->delete();
            return hresponse(true, null, "Enquiry Deleted Succcessfully !!");
        }
        return hresponse(false, null, "Enquiry Not Found !!");
    }
}
