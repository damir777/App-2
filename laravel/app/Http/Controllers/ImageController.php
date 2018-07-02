<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\VillaImage;
use App\Repositories\ImageRepository;

class ImageController extends Controller
{
    //set repo variable
    private $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new ImageRepository;
    }

    //upload image
    public function uploadImage(Request $request)
    {
        //decode json
        $input = $request->json()->all();

        $is_featured = $input['is_featured'];
        $villa_id = $input['villa_id'];
        $is_insert = $input['is_insert'];
        $image = $input['image'];

        //validate form inputs
        $validator = Validator::make($input, VillaImage::validateImageForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //call uploadImage method from ImageRepository to upload image
        $response = $this->repo->uploadImage($is_featured, $villa_id,  $is_insert, $image);

        return response()->json($response);
    }

    //delete image
    public function deleteImage(Request $request)
    {
        $is_featured = $request->is_featured;
        $is_insert = $request->is_insert;
        $image_id = $request->image_id;
        $villa_id = $request->villa_id;

        //call deleteImage method from ImageRepository to delete image
        $response = $this->repo->deleteImage($is_featured, $is_insert, $image_id, $villa_id);

        return response()->json($response);
    }
}
