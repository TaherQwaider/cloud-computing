<?php

namespace App\Http\Controllers;

use App\Mail\SendDemoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    //


    public function sendMail(Request $request){
        $validator = Validator($request->all(), [
            'email' => 'required|email|exists:users,email',
            'msg' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json(['status' => false, 'message' => $validator->getMessageBag()->first()]);
        }

        Mail::to($request->get('email'))->send(new SendDemoMail($request->get('msg')));

        return response()->json(['status' => true]);
    }
}
