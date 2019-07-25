<?php

namespace App\Http\Controllers;

use App\Supply;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Yajra\DataTables\DataTables;

use Settings\SupplyCategories;
use Repositories\SupplyRepository;
use Repositories\SupplyTrackerRepository;

class SupplyController extends Controller
{
    private $rules = array(
        'name'     => 'required|max:255',
        'category' => 'required|max:255',
    );

    private $categories;

    private $supplyRepository;

    public function __construct(SupplyRepository $supplyRepository, SupplyTrackerRepository $supplyTrackerRepository)
    {
        $this->supplyRepository = $supplyRepository;
        $this->supplyTrackerRepository = $supplyTrackerRepository;

        $this->categories = SupplyCategories::CATEGORIES;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.supply.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->categories;

        return view('user.supply.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules);
            
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $inputs = $request->except(['_token']);

            DB::beginTransaction();

            // create supply since we need the id of it for initial supply tracker
            $supply = $this->supplyRepository->add($inputs);


            if ($inputs['initial_supply'] > 0 ) {
                $supplyTracker = $this->supplyTrackerRepository->add(
                    [
                        'supply_id' => $supply->id,
                        'in'        => $inputs['initial_supply'],
                        'balance'   => $inputs['initial_supply'],
                        'remarks'   => 'Initial Amount of Supply.'
                    ]
                );
            }

            DB::commit();
            
            return redirect()
                ->route('supply.edit', [$supply->id])
                ->with('message', 'Supply successfully saved!');
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e, 1);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supply  $supply
     * @return \Illuminate\Http\Response
     */
    public function edit(Supply $supply)
    {
        $categories = $this->categories;

        return view('user.supply.edit', compact('supply', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supply  $supply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supply $supply)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules);
            
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $inputs = $request->except(['_token']);

            DB::beginTransaction();

            // update supply information
            $supply->update($inputs);

            $supplyLatestBalance = $supply->latestHistory->balance;

            if (isset($inputs['in']) && $inputs['in'] > 0){
                $supplyLatestBalance += $inputs['in'];
                $this->supplyTrackerRepository->add(
                    [
                        'supply_id' => $supply->id,
                        'in'        => $inputs['in'],
                        'balance'   => $supplyLatestBalance,
                        'remarks'   => 'Initial Amount of Supply.'
                    ]
                );
            }

            if (isset($inputs['out']) && $inputs['out'] > 0){
                $supplyLatestBalance -= $inputs['out'];
                $this->supplyTrackerRepository->add(
                    [
                        'supply_id' => $supply->id,
                        'out'       => $inputs['out'],
                        'balance'   => $supplyLatestBalance,
                        'remarks'   => 'Initial Amount of Supply.'
                    ]
                );
            }

            DB::commit();
            
            return redirect()
                ->route('supply.edit', [$supply->id])
                ->with('message', 'Supply successfully updated!');
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e, 1);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supply  $supply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supply $supply)
    {
        //
    }


    public function getList()
    {
        $supplies = $this->supplyRepository->getAll();

        $data = DataTables::of($supplies)
            ->addColumn('category', function ($supply) {
                $categoryName = '';
                switch ($supply->category) {
                    case 'P':
                        $categoryName = 'Paper';
                        break;
                    case 'T':
                        $categoryName = 'Tape';
                        break;
                    default:
                        $categoryName = 'Others';
                        break;
                }

                return $categoryName;
            })->addColumn('in', function ($supply) {
                return $supply->total_in;
            })
            ->addColumn('out', function ($supply) {
                return $supply->total_out;
            })
            ->addColumn('balance', function ($supply) {
                return $supply->total_in - $supply->total_out;
            })
            ->addColumn('short', function ($supply) {
                $short = abs($supply->in - $supply->out);
                return ($short < 0) ? $short: 0;
            })
            ->addColumn('action', function ($supply) {
                $menu = [];
                $menu[] = '<a href="'. route('supply.edit', ['id'=>$supply->id]) .'" class="btn-white btn btn-sm" style="margin-right:5px;"><i class="fa fa-pencil text-success"></i> edit</a>';
                
                return '<div class="btn-group text-right">'.implode($menu).'</div>';
            })
            ->make(true);

        return $data;
    }
}
