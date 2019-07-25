<?php

namespace App\Http\Controllers;

use App\ContactInfo;
use App\Helpers\LogActivity;
use App\IcoeInfo;
use App\Profile;
use App\SecurityQA;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;

class ParalegalAssignmentSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.pas.index');
    }


}