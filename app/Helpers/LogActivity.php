<?php

namespace App\Helpers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\LogActivity as LogActivityModel;

class LogActivity
{

    public static function addTOLog($subject, $action, $model)
    {
        $log = [];
        $log['subject'] = $subject;
        $log['action'] = $action;
        $log['model'] = $model;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['ip'] = Request::ip();
        $log['agent'] = Request::header('user-agent');
        $log['user_id'] = auth()->check() ? auth()->user()->id : 1;
        LogActivityModel::create($log);
    }

    public static function logActivityLists()
    {
        $user = User::find(Auth::user()->id);
        $urole = $user->roles->pluck('name');
        $urole = preg_replace('/[^A-Za-z0-9\-]/', '', $urole); // Removes special chars.
        if($urole == 'admin'){
            $logs = LogActivityModel::with('user')->latest()->get();
        }else{
            $logs = LogActivityModel::with('user')->where('user_id', Auth::user()->id)->latest()->get();
        }
        return $logs;
    }

    // Parameter LogActivity::addToLog('Subject/Description', 'Action[Browse, Read, Edit, Add, Delete]', 'Model Name');

}