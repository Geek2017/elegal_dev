<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SettingsController extends Controller
{
    public function noteIndex()
    {
        return view('admin.note.index');
    }

    public function noteCreate()
    {
        $type = 'create';
        return view('admin.note.create', compact('type'));
    }

    public function noteDelete($id)
    {
        if($id > 4){
            $data = Note::find($id);
            if($data->delete()){
                return redirect()->back();
            }
        }
        return redirect()->back();
    }

    public function noteShow($id)
    {
        $type = 'edit';
        $data = Note::find($id);
        return view('admin.note.create', compact('type','data'));
    }

    public function noteStore(Request $request)
    {
        $string = strtolower($request->input('name')); // Replaces all spaces with hyphens.
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

        if($request->input('type') == 'edit'){
            $data = Note::find($request->input('id'));
        }else{
            $data = new Note();
        }
        $data->name = $string;
        $data->display_name = $request->input('name');
        $data->description = $request->input('description');
        if($data->save()){
            return $data;
        }
    }

    public function noteList()
    {
        $list = Note::get();

        $data = DataTables::of($list)
            ->addColumn('name', function ($list) {
                $info = $list->display_name;
                return $info;
            })
            ->addColumn('action', function ($list) {
                $menu = [];
                if($list->id > 4){
                    $menu[] = '<a href="'. route('note-delete',array('id'=>$list->id)) .'" class="btn-white btn btn-xs"><i class="fa fa-times text-danger"></i> delete</a>';
                }
                $menu[] = '<a href="'. route('note-show',array('id'=>$list->id)) .'" class="btn-white btn btn-xs"><i class="fa fa-search text-success"></i> show</a>';
                return '<div class="btn-group text-right">'.implode($menu).'</div>';
            })
            ->make(true);

        return $data;
    }
}
