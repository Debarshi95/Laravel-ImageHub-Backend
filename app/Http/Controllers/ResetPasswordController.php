<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function sendEmail(Request $request)
    {

        //Getting Email from request
        $email = $request->get('email');

        // Validating the request email with DB
        if (!$this->validateEmail($email)) {
            return response()->json([
                'message' => 'Email doesnot exist'
            ], 404);
        }


        //calling and Token from createToken method
        $token = $this->createToken($email);

        //calling saveToken Method
        $this->saveToken($token, $email);

        //sending mail and token to mailable
        Mail::to($email)->send(new ResetPasswordMail($token));

        //Returning the response
        return response()->json([
            'message' => 'Mail sent successfully. Check your inbox'
        ], 200);
    }

    //Validating email with DB
    public function validateEmail($email)
    {
        return !!User::where('email', $email)->first();
    }

    // Creating a random token
    public function createToken($email)
    {
        $oldToken = DB::table('password_resets')->where('email', $email)->first();
        if ($oldToken) {
            return $oldToken;
        }

        $token = Str::random(60);
        return $token;
    }

    // Saving created token into DB
    public function saveToken($token, $email)
    {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }
}
