<?php

namespace App\Http\Controllers;

use App\ContactInfo;
use App\Counsel;
use App\Profile;
use App\Helpers\LogActivity;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CounselController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Parameter LogActivity::addToLog('Subject/Description', 'Action[Browse, Read, Edit, Add, Delete]', 'Model Name');
        LogActivity::addToLog(null, 'Browse', 'Counsel');
        return view('user.counsel.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('add-counsel')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        return view('user.counsel.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('add-counsel')){
            flash('You have no Permission!', 'danger');
            return back();
        }

        $rules = array(
            'first-name' => 'required|max:255',
            'middle-name' => 'required|max:255',
            'last-name' => 'required|max:255',
            'email' => 'required|email|unique:users,email|max:255',
//            'image' => 'required|max:255',
            'address' => 'required|max:255',
            'lawyer-type' => 'required|max:255',
            'lawyer-code' => 'required|max:255',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = new Counsel();
        $data->lawyer_type = $request->input('lawyer-type');
        $data->lawyer_code = $request->input('lawyer-code');
        $data->email = $request->input('email');
        $data->author = Auth::user()->id;
        if($data->save()){
            $profile = new Profile();
            $profile->counsel_id = $data->id;
            $profile->firstname = $request->input('first-name');
            $profile->middlename = $request->input('middle-name');
            $profile->lastname = $request->input('last-name');
            if($request->input('image') != null){
                $profile->image = $request->input('image');
                File::move(public_path().'/temp/image/'.$request->input('image'), public_path().'/uploads/image/'.$request->input('image'));
                $files = File::files(public_path().'/temp/image/');
                File::delete($files);
            }
            $profile->save();

            $contact = new ContactInfo();
            $contact->counsel_id = $data->id;
            $contact->type = 'present_address';
            $contact->description = $request->input('address');;
            $contact->save();

            $user = new User();
            $user->name = $profile->firstname.' '.$profile->lastname;
            $user->email = $data->email;
            $user->password = bcrypt('pacific');
            if($user->save()){
                $user->assignRole('counsel');
                $data->user_id = $user->id;
                $data->save();
            }

            //Parameter LogActivity::addToLog('Subject/Description', 'Action[Browse, Read, Edit, Add, Delete]', 'Model Name');
            LogActivity::addToLog($profile->firstname.' '.$profile->lastname, 'Add', 'Counsel');

            return redirect()->route('counsel.index');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Counsel  $counsel
     * @return \Illuminate\Http\Response
     */
    public function show(Counsel $counsel)
    {
        if(!auth()->user()->can('read-counsel')){
            flash('You have no Permission!', 'danger');
            return back();
        }

        $counsel = Counsel::with('address','profile')->find($counsel->id);

        // return $counsel;
        //Parameter LogActivity::addToLog('Subject/Description', 'Action[Browse, Read, Edit, Add, Delete]', 'Model Name');
        LogActivity::addToLog($counsel->profile->firstname.' '.$counsel->profile->lastname, 'Read', 'Counsel');

        return view('user.counsel.show', compact('counsel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Counsel  $counsel
     * @return \Illuminate\Http\Response
     */
    public function edit(Counsel $counsel)
    {
        if(!auth()->user()->can('edit-counsel')){
            flash('You have no Permission!', 'danger');
            return back();
        }

        $counsel = $counsel->with('address', 'profile')->find($counsel->id);
//        return $counsel;
        return view('user.counsel.edit', compact('counsel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Counsel  $counsel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Counsel $counsel, Profile $profile)
    {
        if(!auth()->user()->can('edit-counsel')){
            flash('You have no Permission!', 'danger');
            return back();
        }

        $rules = array(
            'firstname' => 'required|max:255',
            'middlename' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$counsel->user_id,
//            'image' => 'required|max:255',
            'address' => 'required|max:255',
            'lawyer_type' => 'required|max:255',
            'lawyer_code' => 'required|max:255',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $counsel->lawyer_type = $request->input('lawyer_type');
        $counsel->lawyer_code = $request->input('lawyer_code');
        if($counsel->save()){
            $profile = $profile->where('counsel_id', $counsel->id)->first();
            $profile->firstname = $request->input('firstname');
            $profile->middlename = $request->input('middlename');
            $profile->lastname = $request->input('lastname');
            if($counsel->profile->image != $request->input('image')){
                File::delete(public_path().'/uploads/image/'.$counsel->image);
                $profile->image = $request->input('image');
                File::move(public_path().'/temp/image/'.$request->input('image'), public_path().'/uploads/image/'.$request->input('image'));
                $files = File::files(public_path().'/temp/image/');
                File::delete($files);
            }
            $profile->save();

            ContactInfo::where('counsel_id', $counsel->id)->get()->each(function($row){$row->delete();});
            $contact = new ContactInfo();
            $contact->counsel_id = $counsel->id;
            $contact->type = 'present_address';
            $contact->description = $request->input('address');;
            $contact->save();

            LogActivity::addToLog($profile->firstname.' '.$profile->lastname, 'Edit', 'Counsel');
            return redirect()->route('counsel.show',array('counsel'=>$counsel->id));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Counsel  $counsel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Counsel $counsel)
    {
        //
    }

    public function getList()
    {
        $list = Counsel::with(['profile'])->get();

        $data = DataTables::of($list)
            ->addColumn('full_name', function ($counsel) {
                $info = $counsel->profile->full_name;
                return $info;
            })
            ->addColumn('action', function ($counsel) {
                $menu = [];
//                $menu[] = '<button data-id="'.$counsel->id.'" type="button" class="btn-white btn btn-xs"><i class="fa fa-check text-success"></i> Edit</button>';
                if(auth()->user()->can('edit-counsel')){
                    $menu[] = '<a href="'. route('counsel.edit',array('counsel'=>$counsel->id)) .'" class="btn-white btn btn-xs"><i class="fa fa-pencil text-success"></i> edit</a>';
                }
                if(auth()->user()->can('read-counsel')){
                    $menu[] = '<a href="'. route('counsel.show',array('counsel'=>$counsel->id)) .'" class="btn-white btn btn-xs"><i class="fa fa-search text-success"></i> show</a>';
                }

                return '<div class="btn-group text-right">'.implode($menu).'</div>';
            })
            ->make(true);

        return $data;
    }
}
