<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.role.index');
    }

    public function getList()
    {
        $list = Role::whereNotIn('id', [1])->get();

        $data = DataTables::of($list)
            ->addColumn('name', function ($list) {
                $info = $list->display_name;
                return $info;
            })
            ->addColumn('action', function ($list) {
                $menu = [];
//                $menu[] = '<button data-id="'.$list->id.'" type="button" class="btn-white btn btn-xs"><i class="fa fa-check text-success"></i> Edit</button>';
                $menu[] = '<a href="'. route('role-show',array('id'=>$list->id)) .'" class="btn-white btn btn-xs"><i class="fa fa-search text-success"></i> view</a>';
                return '<div class="btn-group text-right">'.implode($menu).'</div>';
            })
            ->make(true);

        return $data;
    }

    public function show($id)
    {
        $role = Role::find($id);
        $permissions = Permission::select('table_name', 'table_display_name')
            ->distinct('table_name')
            ->get();

        $default = DB::table('role_has_permissions')
            ->where('role_id',$role->id)
            ->pluck('permission_id')
            ->toArray();

//        return $permissions;

        return view('admin.role.show', compact('role','permissions','default'));
    }

    public function update(Request $request, $id)
    {
//        app()['cache']->forget('spatie.permission.cache');
        $ids = $request->input('permission',[]);
//        $permissions = Permission::whereIn('id', $ids)
//            ->pluck('name')
//            ->toArray();

        $role = Role::find($id);

        $role->syncPermissions($ids);

        return redirect()->back();
    }

    public function create()
    {
        return view('admin.role.create');
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|max:255|unique:roles,display_name'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $string = strtolower($request->input('name')); // Replaces all spaces with hyphens.
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

        $data = new Role();
        $data->name = $string;
        $data->display_name = $request->input('name');
        if($data->save()){
            return redirect()->route('role');
        }
    }
}
