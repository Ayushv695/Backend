<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Node\Builder;
use App\Models\State;
use App\Models\City;
use App\Models\Enquiry;
class UserController extends Controller
{
    public function register(Request $request){
       $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>['required','email','unique:users,email'],
            'password'=>['required','confirmed'],
            'phoneNo'=>['required','min:11','numeric'],
            'contactPerson'=>['required'],
            'roleID'=>['required'],
            'address'=>['required'],
            'collegeCode'=>['numeric'],
            'stateID'=>['numeric'],
            'countryID'=>['numeric'],
            'cityID'=>['numeric'],
        ]);
    
        if($validator->fails()){
            $response = $validator->messages()->first();
            return hresponse(false, null, $response);
        }
       
        $user =  User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            'phoneNo'=> $request->phoneNo,
            'roleID'=> $request->roleID,
            'contactPerson'=> $request->contactPerson,
            'address'=> $request->address,
            'collegeCode'=> $request->collegeCode,
            'stateID'=> $request->stateID,
            'countryID'=> $request->countryID,
            'cityID'=> $request->cityID,
        ]);

        // $token = $User->createToken('mytoken')->plainTextToken;
            
        return hresponse(true, $user, "Registration Succcessful !!");
    }

// ______________________________________________________________ login___________________________________________

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

        $user = User::where('email',$request->email)->first();
        if($user){
            if(Hash::check($request->password, $user->password) && $user->status == "Active"){
                $token = $user->createToken('mytoken')->plainTextToken;
                $user->token=$token;
                $user->isSuperUser= $user->role == 'SuperUser' ? true : false;
    
                return hresponse(true, $user, 'Login Successful');
            }
            $message = $user->status == "Active" ? 'Wrong Password' : "Your status is not active please contact with SuperUser";
    
            return hresponse(false, null, $message);
        }
        return hresponse(false, null, "User Not Found !!");
    }

// _________________________________________________________________________ logout _______________________________________

    public function logout(){
        auth()->user()->tokens()->delete();
        return hresponse(true, null, 'Logout successful !! ');
    }
// _________________________________________________________________________ loggedUser _________________________________

    public function loggedUser(Request $request){
        return hresponse(true, auth()->user(), '');
    }

// ____________________________________________________________________ change Password ___________________________________
    public function changePassword(Request $request){
        $request->validate([
            'password'=>['required','confirmed']
        ]);
        $loggedUser = auth()->user();
        $loggedUser->password = Hash::make($request->password);
        $loggedUser->save();
        return hresponse(false, auth()->user(), 'Password Changed');
    }

//____________________________________________________________________ show All Clients _________________________________________ 
    public function showAllClients(Request $req){
        $res = [];
        $search = $req->search ? $req->search : "";
        $limit = $req->limit ? $req->limit : 17;
        $status = $req->status ? $req->status : "";

        if(!empty($search) && $status == ""){
            $users = User::with(['state:stateID,stateName','city:cityID,cityName'])->where('name','LIKE',"%".$search."%")->orWhere('email','LIKE',"%".$search."%")->paginate($limit);
        }
        else if($status != "" && $search == ""){
            $users = User::with(['state:stateID,stateName','city:cityID,cityName'])->where('status',"=","$status")->paginate($limit);
        }
        else if(!empty($search) && !empty($status)){
            $users = User::with(['state:stateID,stateName','city:cityID,cityName'])->where('status',"=",$status);
            $users = $users->where('name','LIKE',"%".$search."%")->orWhere('email','LIKE',"%".$search."%")->paginate($limit);
        }
        else{
            $users = User::with(['state:stateID,stateName','city:cityID,cityName'])->paginate($limit);
        }
        $data = $users;
        $res['users'] = $users;
        $res['totalRecord'] = $users->count();
        if($data){
    
            return hresponse(true, $res, 'All clients list !!');
        }
        return hresponse(false, null, 'No Record Found !!');
    }

// _________________________________________________________________ Update Client _______________________________________________

    public function updateClient(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required',
            'email'=> ['required','email','unique:users,email,'.$id],
            'phoneNo'=>['required','min:11','numeric'],
            'contactPerson'=>['required'],
            'address'=>['required'],
            'collegeCode'=>['numeric'],
            'stateID'=>['numeric'],
            'cityID'=>['numeric'],
        ]);
        if($validator->fails()){
            $response = $validator->messages()->first();
            return hresponse(false, null, $response);
        }

        $client = User::find($id);
        
        if($client){
            $client->first();
            $client->update($request->all());
            return hresponse(true, $client, 'Client updated Successful !!');
        }
        else{
            return hresponse(false, null, 'Client Not Found !!');
        }
    }
// _________________________________________________________________ Delete Client _______________________________________________
    public function deleteClient(string $id)
    {
        $client = User::find($id);
        if($client){
            $client->delete();
            return hresponse(true, null, 'Client Deleted Successfully !!');
        }
        return hresponse(false, null, 'Client Not Found !!');
    }

// _________________________________________________________________ Client Status Update _______________________________________________
    public function clientStatusUpdate(Request $request, string $id){
        if($request->status){
            $client = User::find($id);
        
            if($client){
                $client->first();
                $client->status = $request->status;
                $client->save();
                return hresponse(true, $client, 'Client Status Updated !!');
            }
            else{
                return hresponse(false, null, 'Client Not Found !!');
            }
        }
        return hresponse(false, null, 'Please select status !!');
    }
// _________________________________________________________________Show City _______________________________________________
    public function showCity($stateID){
       if($stateID){
        $state = State::with(['city:stateID,cityName'])->where('stateID','=',$stateID)->get();
        if(!empty($state)){
            return hresponse(true, $state, 'All Available States with their corresponding Cities list !!');
        }
        return hresponse(false, null, 'State Not Found !!');
       }
        return hresponse(false, null, 'Please select correct State !!');
    }

    public function showState(){
         $state = State::all();
         if(!empty($state)){
             return hresponse(true, $state, 'All Available States list !!');
         }
         return hresponse(false, null, 'State Not Found !!');
     }
}

