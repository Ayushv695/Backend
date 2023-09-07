<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Node\Builder;

class SchoolController extends Controller
{
    public function addSchool(Request $request){
        $validator = Validator::make($request->all(), [
             'schoolName'=>'required',
             'email'=>['required','email','unique:schools,email'],
             'schoolCode'=>['required','numeric'],
             'phoneNo'=>['required','min:11','numeric'],
             'address'=>['required'],
             'state'=>['required'],
             'country'=>['required'],
             'city'=>['required'],
             'affiliatedTo'=>['required'],
             'establishedYear'=>['required','numeric'],
         ]);
     
         if($validator->fails()){
             $response = $validator->messages()->first();
             return hresponse(false, null, $response);
         }
        
         $user =  School::create([
             'schoolName' => $request->schoolName,
             'email'=> $request->email,
             'phoneNo'=> $request->phoneNo,
             'address'=> $request->address,
             'schoolCode'=> $request->schoolCode,
             'state'=> $request->state,
             'country'=> $request->country,
             'city'=> $request->city,
             'affiliatedTo'=> $request->affiliatedTo,
             'establishedYear'=> $request->establishedYear,
         ]);
 
         // $token = $User->createToken('mytoken')->plainTextToken;
             
         return hresponse(true, $user, "Registration Succcessful !!");
     }

    public function deleteSchool(string $id){
        $school = School::find($id);
        if($school){
            $school->delete();
            return hresponse(true, null, 'School Deleted Successfully !!');
        }
        return hresponse(false, null, 'School Not Found !!');
    }

    public function updateSchool(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'schoolName'=>'required',
            'email'=> ['required','email','unique:schools,email,'.$id],
            'schoolCode'=>['required','numeric'],
            'phoneNo'=>['required','min:11','numeric'],
            'address'=>['required'],
            'state'=>['required'],
            'country'=>['required'],
            'city'=>['required'],
            'status'=>['required'],
            'affiliatedTo'=>['required'],
            'establishedYear'=>['required','numeric'],
        ]);
    
        if($validator->fails()){
            $response = $validator->messages()->first();
            return hresponse(false, null, $response);
        }

        $school = School::find($id);
        
        if($school){
            $school->first();
            $school->update($request->all());
            return hresponse(true, $school, 'School updated Successful !!');
        }
        else{
            return hresponse(false, null, 'School Not Found !!');
        }
    }

    public function showAllSchools(Request $req){
        $res = [];
        $search = $req->search ? $req->search : "";
        $limit = $req->limit ? $req->limit : 17;
        $status = $req->status ? $req->status : "";
     
        if(!empty($search) && $status == ""){
            $schools = School::where('schoolName','LIKE',"%".$search."%")->orWhere('schoolCode','LIKE',"%".$search."%")->paginate($limit);
        }
        else if($status != "" && $search == ""){
            $schools = School::where('status',"=","$status")->orWhere('schoolCode','LIKE',"%".$search."%")->paginate($limit);
        }
        
        else if(!empty($search) && !empty($status)){
        //     $users = DB::table('schools')
        //     ->where('status', '=', $status)
        //    ->where(function (Builder $query) {
        //        $query->where('schoolName', 'LIKE',"%".$req->search."%")
        //              ->orWhere('schoolCode', '=', $req->search);
        //    })
        //    ->paginate($limit);

            // $schools = School::where([
            //     ['status', '=', $status],
            //     ['schoolName', 'LIKE',"%".$search."%"],
            //     ['schoolCode', 'LIKE',"%".$search."%"],
            // ])->orWhere('schoolCode','LIKE',"%".$search."%")->paginate($limit);
            // $schools = School::where('status',"=","$status")->where('schoolName','LIKE',"%".$search."%")->orWhere('schoolCode','LIKE',"%".$search."%")->paginate($limit);
        }
        else{
            $schools = School::paginate($limit);
        }
        $data = $schools->toArray()['data'];
        $res['schools'] = $data;
        $res['totalRecord'] = $schools->count();
        if($data){
            return hresponse(true, $schools, 'All Schools list !!');
        }
        return hresponse(false, null, 'No Record Found !!');
    }

}
