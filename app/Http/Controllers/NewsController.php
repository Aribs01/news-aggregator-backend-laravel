<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\SavedNews;
use App\Models\User;

class NewsController extends Controller
{
    public function createNews(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = User::find($user_id);

        if (!$user) {
            $mult_response = [
                "Title" =>
                "Operation failed",
                "message" =>
                'User not found',
                "code" => 404,
            ];
            return response()->json(['terminus' => env('APP_URL') . '/' . 'api/create-news', 'status' => 'F9', 'response' => $mult_response], 404);
        }


        $news = new SavedNews;

        $news->user_id = $request->user_id;
        $news->title = $request->title;
        $news->description = $request->description;
        $news->url = $request->url;
        $news->imageUrl = $request->imageUrl;

        $news->save();


        $mult_response = [
            "Title" =>
            "Operation successful",
            "message" =>
            "Data added succesfully",
            "code" => 201,
            "data" => ['news' => $news]
        ];

        return response()->json(['terminus' => env('APP_URL') . '/' . 'api/create-news', 'status' => 'OK', 'response' => $mult_response], 201);
    }

    public function getNewsByUserId(Request $request, $user_id)
    {
        // Check if user ID exists
        $user = User::find($user_id);

        if (!$user) {
            $mult_response = [
                "Title" =>
                "Operation failed",
                "message" =>
                'User not found',
                "code" => 404,
            ];
            return response()->json(['terminus' => env('APP_URL') . '/' . 'api/create-news', 'status' => 'F9', 'response' => $mult_response], 404);
        }

        // Get tasks by user ID
        $news = SavedNews::where('user_id', $user_id)->get();

        $mult_response = [
            "Title" =>
            "Operation successfull",
            "message" =>
            "Get News succesfull",
            "code" => 200,
            "data" => ['news' => $news]
        ];

        return response()->json(['terminus' => env('APP_URL') . '/' . 'api/getNewsByUserId/:user_id', 'status' => 'OK', 'response' => $mult_response], 200);
    }
}
