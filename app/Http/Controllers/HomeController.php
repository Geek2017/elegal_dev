<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = LogActivity::logActivityLists();

        return view('dashboard',compact('logs'));
    }

    public function myTestAddToLog()
    {
        //Parameter LogActivity::addToLog('Subject/Description', 'Action[Browse, Read, Edit, Add, Delete]', 'Model Name');
        LogActivity::addToLog('Subject/Description', 'Action[Browse, Read, Edit, Add, Delete]', 'Model Name');

        //dd('log insert successfully.');
    }

    public function logActivity()
    {
        return view('admin.log.log');
    }

    public function getLogs()
    {
        $list = LogActivity::logActivityLists();
        $data = DataTables::of($list)
            ->addColumn('image', function ($list) {
                $info = '/img/placeholder.jpg';
                if($list->user->profile){
                    $info = '/uploads/image/'.$list->user->profile->image;
                }
                return $info;
            })
            ->addColumn('user', function ($list) {
                $info = $list->user->name;
                if($list->user->profile){
                    $info = $list->user->profile->firstname.' '.$list->user->profile->lastname;
                }
                return $info;
            })
            ->addColumn('description', function ($list) {
                return $list;
            })
            ->addColumn('timestamp', function ($list) {
                $info = Carbon::parse($list->created_at)->diffForHumans();
                return $info;
            })
            ->make(true);
        return $data;
    }

    public function dtGetLogs(){
        $list = LogActivity::logActivityLists();
        $data = DataTables::of($list)
            ->addColumn('number', function ($list) {
                $info = $list->id;
                return $info;
            })
            ->addColumn('user', function ($list) {
                $info = $list->user->name;
                if($list->user->profile){
                    $info = $list->user->profile->firstname.' '.$list->user->profile->lastname;
                }
                return $info;
            })
            ->addColumn('description', function ($list) {
                return $list;
            })
            ->addColumn('desc_url', function ($list) {
                $info = $list->url;
                return $info;
            })
            ->addColumn('desc_ip', function ($list) {
                return $list->ip;
            })
            ->addColumn('timestamp', function ($list) {
                $info = Carbon::parse($list->created_at)->toDayDateTimeString();
                return $info;
            })
            ->make(true);
        return $data;
    }

}