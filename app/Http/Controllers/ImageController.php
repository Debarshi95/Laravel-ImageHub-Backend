<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{

    public function __construct()
    {
        return $this->middleware('jwt')->except(['index']);
    }

    public function index()
    {
        $url = url('storage/upload/GeuEyHE4M78J2opzrNMaxs2fnsWF10n1ZD1ZtOPW.jpeg');
        return response($url)->header('Content-Type', 'image/png');

        // $path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        // // $file = File::allFiles();
        // // foreach ($file as $f);
        // $headers = ['Content-type', 'image/jpeg'];
        // return response()->file($path, $headers);
    }


    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'caption' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image:jpeg,jpg,png']
        ]);
        if ($validation->fails()) {
            return response()->json([
                'message' => 'All fields are required',
                'errors' => $validation->errors()
            ], 422);
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . '.' . $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $file->storeAs('upload', 'public');
            $filepath = '/public/upload/';
            $image = Image::create([
                'caption' => $request->get('caption'),
                'user_id' => auth()->user()->id,
                'filename' => $file->getFilename() . '.' . $extension,
                'mime' => $file->getMimeType(),
                'original_filename' => $file->getClientOriginalName(),
                'url' => $filepath . $name . '.' . $extension
            ]);
            return response()->json([
                'message' => 'You have successfully uploaded the image',
                'image' => $image
            ], 201);
        }
        return response()->json([
            'message' => 'Some error occured'
        ], 404);
    }
}
