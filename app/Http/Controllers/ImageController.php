<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function __construct()
    {
        return $this->middleware('jwt')->except(['index']);
    }

    public function index(Request $request)
    {
        return $request->all();
    }
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'caption' => ['required', 'string', 'min:6'],
            'image' => ['required', 'mimes:jpeg,png,jpeg', 'max:2048']
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'All fields are required',
                'errors' => $validation->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            $file = $request->image;
            $name = time() . '.' . $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $mime = $file->getMimeType();
            // $path =
            $file->storeAs('', $name);
            $image = Image::create([
                'caption' => $request->get('caption'),
                'user_id' => Auth::user()->id,
                'filename' => $file->getFileName(),
                'mime' => $mime,
                'original_filename' => $name,
                'url' => url('/public')
            ]);

            return response()->json([
                'message' => 'Image uploaded successfully',
                'image' => $image
            ], 201);
        }
        return response()->json([
            'message' => 'Some error occured'
        ], 500);
    }
}
