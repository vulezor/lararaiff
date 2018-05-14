<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Validator;
class UserController extends Controller
{
    //
    public function userLogin(Request $request){
        $validator = Validator::make($request->all(), ['email'=> 'required|email','password'=>'required']);

        if($validator->fails()) {
            return response()->json(['Data'=>NULL, 'ErrorMessage'=>$validator->errors(), 'Success'=>false], 401);
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $user['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['Data'=>$user, 'ErrorMessage'=>NULL, 'Success'=>true], 200);
        } else {
            return response()->json(['Data'=>NULL, 'ErrorMessage'=>'Unauthorized', 'Success'=>true], 401);
        }
    }

    public function userRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'email'=> 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if($validator->fails()) {
            return response()->json(['Data'=>NULL, 'ErrorMessage'=>$validator->errors(), 'Success'=>false], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user['token'] =  $user->createToken('MyApp')->accessToken;
        $user['name'] =  $user->name;
        return response()->json(['Data'=> $user, 'ErrorMessage'=>NULL, 'success'=>true], 200);
    }

    public function userDetails(){
      $user = User::get();
      return response()->json(['Data'=> $user, 'ErrorMessage'=>NULL, 'Success'=>true], 200);  
    }
}
