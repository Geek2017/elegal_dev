<?php

namespace App\Http\Controllers;

use App\Fee;
use App\FeeCategory;
use App\FeeDescription;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.fee.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = FeeCategory::get();
        return view('user.fee.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $string = strtolower($request->input('name'));
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

        $count = Fee::count();
        $count += 1;

        $data = new Fee();
        $data->code = str_pad($count, 3, '0', STR_PAD_LEFT);
        $data->name = $string;
        $data->category_id = $request->input('category');
        $data->display_name = $request->input('name');
        if($data->save()){

            if($request->input('desc-name')){
                $names = $request->input('desc-name', null);
                $desc = $request->input('desc-description',null);
                $amount = $request->input('desc-amount',0);
                foreach ($names as $key => $name){
                    $string = strtolower($name); // Replaces all spaces with hyphens.
                    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
                    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
                    $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

                    $data2 = new FeeDescription();
                    $data2->fee_id = $data->id;
                    $data2->name = $string;
                    $data2->display_name = $name;
                    $data2->description = $desc[$key];
                    $data2->default_amount = $amount[$key];
                    $data2->save();
                }
            }
            return redirect()->route('fee.show', array('fee' => $data->id));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function show(Fee $fee)
    {
        $fee = Fee::with('category')
            ->with('description')->find($fee->id);
        return view('user.fee.show', compact('fee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function edit(Fee $fee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fee $fee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fee $fee)
    {
        //
    }

    public function feeList(Request $request)
    {
        $type = $request->input('type');
        switch ($type){
            case 'chargeable-expense':
                $ids = FeeCategory::where('name', $type)->pluck('id')->toArray();
                break;
            default:
                $ids = FeeCategory::whereIn('name', array($type, 'special-and-general'))->pluck('id')->toArray();
        }

        $fee = Fee::whereIn('category_id', $ids)->get();
        return response()->json($fee);
    }

    public function feeGetList()
    {
        $list = Fee::with('category')
            ->with('description')
            ->orderBy('id', 'ASC')
            ->get();

        $data = DataTables::of($list)
            ->addColumn('category', function ($list) {
                $info = $list->category->display_name;
                return $info;
            })
            ->addColumn('code', function ($list) {
                $info = $list->code;
                return $info;
            })
            ->addColumn('name', function ($list) {
                $info = $list->display_name;
                return $info;
            })
            ->addColumn('description', function ($list) {
                $info = $list->description->count();
                return $info;
            })
            ->addColumn('action', function ($list) {
                $menu = [];
                $menu[] = '<a href="'. route('fee.show',array('fee'=>$list->id)) .'" class="btn-white btn btn-xs"><i class="fa fa-search text-success"></i> show</a>';
//                $menu[] = '<button data-id="'.$list->id.'" type="button" data-type="edit" class="action btn-white btn btn-xs"><i class="fa fa-pencil text-success"></i> Edit</button>';
//                $menu[] = '<button data-id="'.$list->id.'" type="button" data-type="delete" class="action btn-white btn btn-xs"><i class="fa fa-trash text-danger"></i> Trash</button>';
                return '<div class="btn-group text-right">'.implode($menu).'</div>';
            })
            ->make(true);

        return $data;
    }

    public function feeDesc($id)
    {
        $list = FeeDescription::where('fee_id', $id)
            ->get();
        $data = DataTables::of($list)
            ->addColumn('description', function ($list) {
                $info = $list->display_name;
                return $info;
            })
            ->addColumn('type', function ($list) {
                $info = $list->description;
                return $info;
            })
            ->addColumn('amount', function ($list) {
                $info = $list->default_amount;
                return $info;
            })
            ->addColumn('action', function ($list) {
                $menu = [];
                $menu[] = '<button data-id="'.$list->id.'" type="button" data-type="fee-description" data-action="edit" class="action btn-white btn btn-xs"><i class="fa fa-pencil text-success"></i> Edit</button>';
                $menu[] = '<button data-id="'.$list->id.'" type="button" data-type="fee-description" data-action="delete" class="action btn-white btn btn-xs"><i class="fa fa-times text-danger"></i> Delete</button>';
                return '<div class="btn-group text-right">'.implode($menu).'</div>';
            })
            ->make(true);

        return $data;
    }

    public function feeDescStore(Request $request)
    {
        $string = strtolower($request->input('name')); // Replaces all spaces with hyphens.
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

        $data = new FeeDescription();
        $data->fee_id = $request->input('id');
        $data->name = $string;
        $data->display_name = $request->input('name');
        $data->description = $request->input('desc',0);
        $data->default_amount = $request->input('amount',0);
        if($data->save()){
            return response()->json($data);
        }
    }

    public function feeDescUpdate(Request $request)
    {
        $string = strtolower($request->input('name')); // Replaces all spaces with hyphens.
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

        $data = FeeDescription::find($request->input('id'));
        $data->name = $string;
        $data->display_name = $request->input('name');
        $data->description = $request->input('desc',0);
        $data->default_amount = $request->input('amount',0);
        if($data->save()){
            return response()->json($data);
        }
    }

    public function feeFind(Request $request)
    {
        $data = FeeDescription::find($request->input('id'));
        return response()->json($data);
    }

    public function feeDetailFind(Request $request, FeeCategory $feeCategory)
    {
        $data = Fee::find($request->input('id'));
        $data2 = $feeCategory->get();
        return response()->json(array($data, $data2));
    }

    public function feeUpdate(Request $request, Fee $fee)
    {
        $string = strtolower($request->input('name')); // Replaces all spaces with hyphens.
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

        $data = $fee->find($request->input('id'));
        $data->category_id = $request->input('category');
        $data->name = $string;
        $data->display_name = $request->input('name');
        if($data->save()){
            $data = $fee->with('category')->find($data->id);
            return response()->json($data);
        }
    }

    public function feeCategory(FeeCategory $feeCategory)
    {
        $data = $feeCategory->get();
        return response()->json($data);
    }

    public function feeDescDelete(Request $request)
    {
        $data = FeeDescription::find($request->input('id'));
        if($data->delete()){
            return response()->json(1);
        }
    }

    public function getFeeDesc(Request $request)
    {
        $data = Fee::with('description')->find($request->input('id'));
        return response()->json($data->description);
    }

}
