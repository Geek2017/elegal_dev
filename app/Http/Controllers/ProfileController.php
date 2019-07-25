<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use Auth;
use App\user;
use App\Profile;

class ProfileController extends Controller
{

    private $rules = array(
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email',
            'status' => 'required',
            'blood_type' => 'required',
            
        );

    public function index()
    {
        $user = Auth::user();

        return view('user.profile.index', compact('user'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $inputs =$request->except('_token');

        $user = Auth::user();
        $user->fill($inputs);
        

        if ($user->save()) {
            $profile = Profile::where('user_id', $user->id)->first();

            if ($inputs['image']) {
                // store new image
                File::move(public_path().'/temp/image/'.$inputs['image'], public_path().'/uploads/image/'.$inputs['image']);
                $files = File::files(public_path().'/temp/image/');
                File::delete($files);
            }

            if ($profile) {
                if ($profile->imgae != null && $inputs['image']) {
                    $file = File::file(public_path().'/uploads/image/'.$profile->image);
                    File::delete($files);
                }

                // Update profile info
                $profile->fill($inputs);
                $profile->save();
            } else {
                $profile = Profile::create(array_merge($inputs, ['user_id'=>$user->id]));
            }

            return redirect()
                    ->route('profile.index')
                    ->with('message', 'Information successfully updated!');
        } else {
            throw new \Exception("Error Processing Request", 1);
        }
    }
}
