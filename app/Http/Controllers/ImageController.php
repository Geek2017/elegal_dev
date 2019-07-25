<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    public function imageUpload(Request $request) {

        $file = $request->file('photo');
        $v = str_random(5);
        $ext = $request->file('photo')->getClientOriginalExtension();
        $filename = $v.'.'.$ext;
        $input = array('image' => $file);
        $rules = array(
            'image' => 'required|image'
        );
        $message = array(
            'image' => 'Please provide a valid image file.'
        );
        $validator = Validator::make($input, $rules, $message);
        if ( $validator->fails() )
        {
            return response()->json(['success' => false, 'errors' => $validator->getMessageBag()->toArray()]);
//            return Redirect::back()->withErrors($validator)->withInput();
        }
        else {
            $img = Image::make($file);
            $img->backup();
            $img->resize(null, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(300,300);
            $img->save('temp/image/'.$filename,80);
            return response()->json(['success' => true, 'file' => $filename, 'version'=>$v,'uid'=>$v]);
        }
    }

    public function imageMove($image)
    {
        File::move(public_path().'/temp/image/'.$image, public_path().'/uploads/image/'.$image);
        $files = File::files(public_path().'/temp/image/'.$image);
        File::delete($files);
    }
}
