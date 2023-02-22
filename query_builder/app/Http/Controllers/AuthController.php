<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits as TraitsResponseTraits;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTraits;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ResponseTraits;
    public function registerUser(Request $request)
    {
        $data = $request->all();
        $validation = validator($data,[
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed',
        ]);
        if($validation->fails()){
            return $this->sendErrorResponse($validation);
        }
        else{
            $data['password'] = Hash::make($request->password);
            $user = User::create($data);
            $token = $user->createToken('API TOKEN')->plainTextToken;
            return $this->sendSuccessResponse(200,"User Created Successfullly",$token);
        }
    }
    
    //-------------//User Login Function //-----------------//
    public function login(Request $request){
        try {
            $data = $request->all();
            $validation = validator($data,[
                'email'    => 'required|email',
                'password' => 'required'
            ]);

            if($validation->fails()){
                return $this->sendErrorResponse($validation);
            }

            if(!Auth::attempt($request->only(['email','password']))){
                return response()->json(['status'=>false,'message'=>'Email & password does not match!!'],401);
            }

            $user = User::where('email',$request->email)->first();  
            $token = $user->createToken("API TOKEN")->plainTextToken;
            return $this->sendSuccessResponse(200,"User Logged In Successfully",$token);

        } catch (Exception $th) {
            return $this->sendExecptionMessage($th);
        }
    }
}
