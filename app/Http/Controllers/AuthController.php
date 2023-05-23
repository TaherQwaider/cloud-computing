<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function login(Request $request){

        $validator = Validator($request->all(), [
            'email' => 'required|email|'
        ]);



    }

    public function register(Request $request){
        $validator = Validator($request->all(), [
            'email' => 'required|email|unique:users,email',
            'user_type' => 'required|in:trainee,manager,advisor',
            'password' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['status' => false, 'message' => $validator->getMessageBag()->first()]);
        }

        $user = User::create([
            'email' => $request->get('email'),
            'user_type' => $request->get('user_type'),
            'password' => Hash::make($request->get('password'))
        ]);

        return response()->json(['status' => true]);
    }

}
