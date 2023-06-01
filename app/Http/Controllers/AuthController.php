<?php

namespace App\Http\Controllers;

use App\Mail\SendDemoMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    //
    public function login(Request $request){
        $validator = Validator($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['status' => false, 'message' => $validator->getMessageBag()->first()]);
        }
        $user = User::where('email', $request->get('email'))->orWhere('id', $request->get('email'))->first();

        if ($user){
            if (Hash::check($request->get('password'), $user->password)){
                return response()->json([
                    'status' => true,
                    'user_type' => $user->user_type
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'user password id wrong'
                ]);
            }

        }
        return response()->json([
            'status' => false,
            'message' => 'user not found'
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

        Mail::to($request->get('email'))->send(new SendDemoMail('Your id is : '.$user->id));

        return response()->json(['status' => true]);
    }

}
