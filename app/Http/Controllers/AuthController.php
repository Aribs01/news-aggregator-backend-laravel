<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
// use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $mult_response = [
                "Title" =>
                "Operation failed",
                "message" =>
                $validator->errors(),
                "code" => 422,
            ];
            return response()->json(['terminus' => env('APP_URL') . '/' . 'api/login', 'status' => 'F9', 'response' => $mult_response], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user == NULL) {
            $mult_response = [
                "Title" =>
                "operation failed",
                "message" =>
                'The Email Address Not Found',
                "code" => 404,
            ];
            return response()->json(['terminus' => env('APP_URL') . '/' . 'api/login', 'status' => 'F9', 'response' => $mult_response], 404);
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            $mult_response = [
                "Title" =>
                "Operation failed",
                "message" =>
                'Invalid Credentials',
                "code" => 401,
            ];
            return response()->json(['terminus' => env('APP_URL') . '/' . 'api/login', 'status' => 'F9', 'response' => $mult_response], 401);
        }

        $random = mt_rand(100000, 999999);
        $token = $user->createToken($random)->plainTextToken;

        $mult_response = [
            "Title" =>
            "operation successful",
            "message" =>
            "Login Succesfull",
            "code" => 200,
            "token" => $token,
            "data" => $user
        ];

        return response()->json(['terminus' => env('APP_URL') . '/' . 'api/login', 'status' => 'OK', 'response' => $mult_response], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $mult_response = [
            "Title" =>
            'Operation successful',
            "message" =>
            "User Logout Succesfully",
            "code" => 202,
        ];
        return response()->json(['terminus' => env('APP_URL') . '/' . 'api/logout', 'status' => 'OK', 'response' => $mult_response], 202);
    }
}
