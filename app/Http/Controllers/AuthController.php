<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'password_confirmation' => ['required', 'string', 'min:6', 'same:password']
        ]);
        if ($validation->fails()) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $validation->errors()
            ], 404);
        }
        $user = User::create([
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password_confirmation'))
        ]);
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User created',
            'user' => $user,
            'token' => $token
        ], 201);
    }
    public function validateUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(
                    ['message' => 'User doesnot exists'],
                    404
                );
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(
                ['message' => 'Token expired. Pl logout and login to continue'],
                $e->getStatusCode()
            );
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(
                ['message' => 'Invalid token. Pl logout and login to continue'],
                $e->getStatusCode()
            );
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(
                ['message' => 'Token absent. Pl logout and login to continue'],
                $e->getStatusCode()
            );
        }

        return response()->json(
            ['user' => $user],
            200
        );
    }
}
