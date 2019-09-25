<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Rules\ResetToken;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
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
        $tokenObj = $this->createToken($email);
        //sending mail and token to mailable
        Mail::to($email)->send(new ResetPasswordMail($tokenObj->token));

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
        //calling saveToken Method
        $this->saveToken($token, $email);

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

    //Process the request for token and other fields
    public function process(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'resetToken' => [new ResetToken],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:6'],
            'password_confirmation' => ['required', 'string', 'min:6', 'same:password']
        ]);
        if ($validation->fails()) {
            return response()->json([
                'message' => 'All fields are required',
                'errors' => $validation->errors()
            ]);
        }

        $match = DB::table('password_resets')->where('token', $request->get('resetToken'))->first();
        if ($match) {
            return $this->changePassword($request);
        }
        return response()->json([
            'message' => 'Token didnot match. Pl try again'
        ], 404);
    }

    //Changing the existing password and saving it to DB
    public function changePassword(Request $request)
    {
        $user = User::whereEmail($request->get('email'));
        $user->update(['password' => Hash::make($request->get('password_confirmation'))]);
        return response()->json([
            'message' => 'Password changed successfully. Login with your new password to continue'
        ], 200);
    }
}
