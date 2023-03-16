<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'same:password|required',
        ]);

        if ($validator->fails()) {
            $mult_response = [
                "Title" =>
                "Operation failed",
                "message" =>
                $validator->errors(),
                "code" => 422,
            ];
            return response()->json(['terminus' => env('APP_URL') . '/' . 'api/register', 'status' => 'F9', 'response' => $mult_response], 422);
        }

        $user = new User;

        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $mult_response = [
            "Title" =>
            "Operation successful",
            "message" =>
            "Data added succesfully",
            "code" => 201,
            "data" => ['user' => $user]
        ];

        return response()->json(['terminus' => env('APP_URL') . '/' . 'api/register', 'status' => 'OK', 'response' => $mult_response], 201);
    }
}
