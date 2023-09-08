<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Enquiry;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Node\Builder;

class EnquiryController extends Controller
{
    // _________________________________________________________________ enquiry _______________________________________________
    
    public function showAllEnquiries(Request $req){
        $res = [];
        $search = $req->search ? $req->search : "";
        $limit = $req->limit ? $req->limit : 17;
        $status = $req->status ? $req->status : "";
        $step = $req->step ? $req->step : "";
        $enquiryDate = $req->enquiryDate ? $req->enquiryDate : "";

        if(!empty($search) && $status == ""){
            $enquiries = Enquiry::where('name','LIKE',"%".$search."%")->orWhere('clientID','LIKE',"%".$search."%")->paginate($limit);
        }
        else if($status != "" && $search == ""){
            $enquiries = Enquiry::where('status',"=","$status")->orWhere('clientID','LIKE',"%".$search."%")->paginate($limit);
        }
        else if(!empty($search) && !empty($status)){
        //     $enquiries = DB::table('enquiries')
        //     ->where('status', '=', $status)
        //    ->where(function (Builder $query) {
        //        $query->where('name', 'LIKE',"%".$GLOBALS['search']."%")
        //              ->orWhere('clienID', '=', $GLOBALS['status']);
        //    })
        //    ->paginate($limit);
            // $schools = Enquiry::where([
            //     ['status', '=', $status],
            //     ['schoolName', 'LIKE',"%".$search."%"],
            // ])->orWhere('schoolCode','LIKE',"%".$search."%")->paginate($limit);
            // $enquiries = Enquiry::where('status',"=","$status")->where('name','LIKE',"%".$search."%")->paginate($limit);
        }
        else{
            $enquiries = Enquiry::paginate($limit);
        }
        $res['enquiries'] = $enquiries;
        $res['totalRecord'] = $enquiries->count();
        if($enquiries){
    
            return hresponse(true, $enquiries, 'All Schools list !!');
        }
        return hresponse(false, null, 'No Record Found !!');
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

    public function updateEnquiry(Request $request , string $id){
        $validator = Validator::make($request->all(),[
            'step'=> 'required',
            'name'=> 'required',
            'email'=> ['required','email','unique:enquiries,email,'.$id],
            'phone'=>['required','min:11','numeric'],
            'message'=>['required'],
            'address'=>['required'],
            'course'=>['required'],
            'status'=>['required'],
            'enquiryDate'=>['required','date'],
        ]);
        if($validator->fails()){
            $response = $validator->messages()->first();
            return hresponse(false, null, $response);
        }

        $enquiry = Enquiry::find($id);
        if($enquiry)
        {
            $enquiry = $enquiry->first();
            $enquiry->update($request->all());
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
