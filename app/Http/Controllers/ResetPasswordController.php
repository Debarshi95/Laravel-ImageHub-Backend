<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public function sendEmail(Request $request)
    {
        if (!$this->validateEmail($request->get('email'))) {
            return response()->json([
                'message' => 'Email doesnot exist'
            ], 404);
        }
        $token = str_random(60);
        Mail::to($request->get('email'))->send(new ResetPasswordMail($token));
        return response()->json([
            'message' => 'Mail sent successfully. Check your inbox'
        ], 200);
    }

    public function validateEmail($email)
    {
        return !!User::where('email', $email)->first();
    }
}
