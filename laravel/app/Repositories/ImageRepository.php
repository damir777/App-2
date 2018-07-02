<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\VillaFeaturedImage;
use App\VillaImage;
use App\Villa;

class ImageRepository
{
    private $villa_prefix = 'Croatia_Istria_Villa_';
    /*
    |--------------------------------------------------------------------------
    | Icons
    |--------------------------------------------------------------------------
    */

    //upload icon
    public function uploadIcon($icon)
    {
        try
        {
            //set icon path
            $icon_path = public_path().'/icon';

            //generate icon string
            $icon_string = substr(md5(rand()), 0, 15);

            //get icon extension
            $extension = $icon->getClientOriginalExtension();

            //set icon name
            $icon_name = $icon_string.'.'.$extension;

            //upload icon
            $icon->move($icon_path, $icon_name);

            return ['status' => 1, 'data' => $icon_name];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //delete icon
    public function deleteIcon($icon)
    {
        try
        {
            //set icon path
            $icon_path = public_path().'/icon/'.$icon;

            //delete icon
            File::delete($icon_path);

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Images
    |--------------------------------------------------------------------------
    */

    //upload image
    public function uploadImage($is_featured, $villa_id, $is_insert, $image)
    {
        try
        {
            //decode image
            $img = Image::make(base64_decode($image));

            //generate image name string
            $image_name = substr(md5(rand()), 0, 15).'.jpg';

            $img_path = '';
            $thumb_path = '';

            if ($is_insert == 'T')
            {
                //if gallery session doesn't exist create it
                if (!Session::has('gallery'))
                {
                    //generate gallery token
                    $token = substr(md5(rand()), 0, 40);

                    //set gallery session
                    Session::put('gallery', $token);
                }

                //set gallery token
                $token = Session::get('gallery');

                //set img path
                $img_path = public_path().'/images/'.$image_name;

                //set thumb path
                $thumb_path = public_path().'/images/thumbs/'.$image_name;
            }
            else
            {
                //set gallery token
                $token = null;
            }

            //start transaction
            DB::beginTransaction();

            $image_model = new VillaFeaturedImage;

            if ($is_featured == 'F')
            {
                $image_model = new VillaImage;
            }

            $image_model->villa_id = $villa_id;
            $image_model->image = $image_name;
            $image_model->gallery_token = $token;
            $image_model->save();

            if ($is_insert == 'F')
            {
                $featured_identifier = 1;

                if ($is_featured == 'F')
                {
                    $featured_identifier = 2;
                }

                //get villa url name and city
                $villa = Villa::find($villa_id);

                //call formatName method from GeneralRepository to format city
                $city = GeneralRepository::formatName($villa->city);

                //set image name
                $image_name = $this->villa_prefix.$villa->url_name.'_'.$city.'_'.$image_model->id.$featured_identifier.'.jpg';

                //update image name
                $image_model->image = $image_name;
                $image_model->save();

                //set img path
                $img_path = public_path().'/images/'.$villa_id.'/'.$image_name;

                //set thumb path
                $thumb_path = public_path().'/images/thumbs/'.$villa_id.'/'.$image_name;
            }

            //save image
            $img->save($img_path);

            //resize image
            $img->resize(420, 280);

            //save thumb image
            $img->save($thumb_path);

            //commit transaction
            DB::commit();

            return ['status' => 1, 'image_id' => $image_model->id, 'image_name' => $image_name, 'villa_id' => $villa_id];
        }
        catch (Exception $exp)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //delete image
    public function deleteImage($is_featured, $is_insert, $image_id, $villa_id)
    {
        try
        {
            $image = VillaFeaturedImage::where('id', '=', $image_id);

            if ($is_featured == 'F')
            {
                $image = VillaImage::where('id', '=', $image_id);
            }

            if ($villa_id)
            {
                $image->where('villa_id', '=', $villa_id);
            }
            else
            {
                $image->where('gallery_token', '=', Session::get('gallery'));
            }

            $image = $image->first();

            //if image doesn't exist return error status
            if (!$image)
            {
                return ['status' => 0];
            }

            if ($is_insert == 'T')
            {
                //set img path
                $img_path = public_path().'/images/'.$image->image;

                //set thumb path
                $thumb_path = public_path().'/images/thumbs/'.$image->image;
            }
            else
            {
                //set img path
                $img_path = public_path().'/images/'.$image->villa_id.'/'.$image->image;

                //set thumb path
                $thumb_path = public_path().'/images/thumbs/'.$image->villa_id.'/'.$image->image;
            }

            //delete image
            File::delete($img_path);

            //delete thumb
            File::delete($thumb_path);

            //delete image
            $image->delete();

            return ['status' => 1];
        }
        catch (Exception $exp)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //move temp images
    public function moveTempImages($token, $villa_id, $url_name, $city)
    {
        try
        {
            //set temp directory path
            $temp_directory_path = public_path().'/images';

            //set temp thumb directory path
            $temp_thumb_directory_path = public_path().'/images/thumbs';

            //set directory path
            $directory_path = public_path().'/images/'.$villa_id;

            //set thumb directory path
            $thumb_directory_path = public_path().'/images'.'/thumbs/'.$villa_id;

            //delete directory to prevent duplicate images
            File::deleteDirectory($directory_path);

            //delete thumbs directory to prevent duplicate images
            File::deleteDirectory($thumb_directory_path);

            //create new directory
            File::makeDirectory($directory_path);

            //create new thumbs directory
            File::makeDirectory($thumb_directory_path);

            //get featured images
            $featured_images = VillaFeaturedImage::select('id', 'image')->where('gallery_token', '=', $token)->get();

            foreach ($featured_images as $image)
            {
                //call formatName method from GeneralRepository to format city
                $city = GeneralRepository::formatName($city);

                $image_name = $this->villa_prefix.$url_name.'_'.$city.'_'.$image->id.'1.jpg';

                if (!File::move($temp_directory_path.'/'.$image->image, $directory_path.'/'.$image_name))
                {
                    return ['status' => 0];
                }

                if (!File::move($temp_thumb_directory_path.'/'.$image->image, $thumb_directory_path.'/'.$image_name))
                {
                    return ['status' => 0];
                }

                $image->villa_id = $villa_id;
                $image->image = $image_name;
                $image->gallery_token = null;
                $image->save();
            }

            //get images
            $images = VillaImage::select('id', 'image')->where('gallery_token', '=', $token)->get();

            foreach ($images as $image)
            {
                //call formatName method from GeneralRepository to format city
                $city = GeneralRepository::formatName($city);

                $image_name = $this->villa_prefix.$url_name.'_'.$city.'_'.$image->id.'2.jpg';

                if (!File::move($temp_directory_path.'/'.$image->image, $directory_path.'/'.$image_name))
                {
                    return ['status' => 0];
                }

                if (!File::move($temp_thumb_directory_path.'/'.$image->image, $thumb_directory_path.'/'.$image_name))
                {
                    return ['status' => 0];
                }

                $image->villa_id = $villa_id;
                $image->image = $image_name;
                $image->gallery_token = null;
                $image->save();
            }

            //clear gallery session
            Session::forget('gallery');

            return ['status' => 1];
        }
        catch (Exception $exp)
        {
            return ['status' => 0];
        }
    }

    //delete temp images
    public function deleteTempImages()
    {
        try
        {
            //if gallery session exists delete temp images
            if (Session::has('gallery'))
            {
                //get featured images
                $featured_images = VillaFeaturedImage::select('image')->where('gallery_token', '=', Session::get('gallery'))
                    ->get();

                foreach ($featured_images as $image)
                {
                    //delete image
                    File::delete(public_path().'/images/'.$image->image);

                    //delete thumb
                    File::delete(public_path().'/images/thumbs/'.$image->image);
                }

                //delete featured images
                VillaFeaturedImage::where('gallery_token', '=', Session::get('gallery'))->delete();

                //get images
                $images = VillaImage::select('image')->where('gallery_token', '=', Session::get('gallery'))->get();

                foreach ($images as $image)
                {
                    //delete image
                    File::delete(public_path().'/images/'.$image->image);

                    //delete thumb
                    File::delete(public_path().'/images/thumbs/'.$image->image);
                }

                //delete images
                VillaImage::where('gallery_token', '=', Session::get('gallery'))->delete();
            }

            return ['status' => 1];
        }
        catch (Exception $exp)
        {
            return ['status' => 0];
        }
    }
}
