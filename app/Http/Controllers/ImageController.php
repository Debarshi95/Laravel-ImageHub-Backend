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
        return response()->json([
            'images' => Image::all()
        ]);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'caption' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048']
        ]);
        if ($validation->fails()) {
            return response()->json([
                'message' => 'All fields are required',
                'errors' => $validation->errors()
            ]);
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            Storage::disk('public')->put($file->getFilename() . '.' . $extension, File::get($file));
            $image = Image::create([
                'caption' => $request->get('caption'),
                'user_id' => auth()->user()->id,
                'filename' => $file->getFilename() . '.' . $extension,
                'mime' => $file->getMimeType(),
                'original_filename' => $file->getClientOriginalName()
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
