<?php

namespace App\Http\Controllers;

use App\Ars;
use App\ArsAd;
use App\ArsOo;
use App\ArsFad;
use App\CaseManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

use DB;

class ArsController extends Controller
{
    private $rules = array(
                'case_project_name'   => 'required|max:255',
                'case_management_id'   => 'required|exists:case_managements,id',
                'docket_no_venue'     => 'required|max:255',
                'reporter'            => 'required|max:255',
                'client_id'           => 'required', // this should be a validation in clients table
                'ars_date'            => 'required', // this is date
                // 'time_start'          => 'required', // this is time
                // 'time_finnish'        => 'required', // this is time
                // 'duration'            => 'required|max:255', // decimal
                'sr_no'               => 'required|max:255',
                // 'billing_instruction' => 'required|max:255',
                // 'billing_entry'       => 'required|max:255'
            );
  
    public function index()
    {
        return view('user.ars.index');
    }
    
    public function create()
    {
        $date = Carbon::now();
        $arsNo = Ars::select(
                    DB::raw("LPAD(IF(MAX(ars_no) is NOt NULL, (MAX(ars_no) + 1),  1), 5, '0') as ars_no_max")
                )
                ->first();

        $prefixArsNo = $date->format('y').'-'.$date->format('m');
        $currentDate = $date->format('Y-m-d');

        return view('user.ars.create', compact('arsNo', 'prefixArsNo', 'currentDate'));
    }

   
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

            // save ars data
            $inputs['ars_no'] = ltrim($inputs['ars_no_hidden'], '0');
            $inputs['time_start'] = $inputs['time_start'] ? date('H:i:s', strtotime($inputs['time_start'])) : null;
            $inputs['time_finnish'] = $inputs['time_finnish'] ? date('H:i:s', strtotime($inputs['time_finnish'])) : null;
            $inputs['billing_instruction'] = ($inputs['billing_instruction'] !==  null) ? $inputs['billing_instruction'] : '';
            // dd($inputs);
            $ars = Ars::create($inputs);

            // saving Descriptions
            $ads = [];
            foreach ($inputs['descriptions'] as $key => $d) {
                $ads[] = [
                    'ars_id'      => $ars->id,
                    'description' => $d
                ];
            }
            ArsAd::insert($ads);

            // Saving Outcomes
            $oos = [];
            foreach ($inputs['outcomes'] as $key => $o) {
                $oos[] = [
                    'ars_id'      => $ars->id,
                    'description' => $d,
                    'outcome_outputcol' => '1'
                ];
            }
            ArsOo::insert($oos);

            // Saving Feature Activity
            $fads = [];
            foreach ($inputs['feacture_activities'] as $key => $f) {
                $fads[] = [
                    'ars_id'      => $ars->id,
                    'description' => $f,
                ];
            }
            ArsFad::insert($fads);

            DB::commit();
            
            return redirect()
                ->route('ars.edit', [$ars->id])
                ->with('message', 'Activity Report Sheet, successfully save!');
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e, 1);
        }
    }
    
    public function show(Ars $ar)
    {
        // return view('user.ars.show', compact('ars'));
    }

   
    public function edit($id)
    {
        $ars = Ars::with(['ads', 'oos', 'fads', 'client.profile'])
            ->select([
                    'ars.*',
                    DB::raw('TIME_FORMAT(time_start, "%H %i %p") as time_start'),
                    DB::raw('TIME_FORMAT(time_finnish, "%H %i %p") as time_finnish'),
                ])
            ->findorFail($id);
        $cases = CaseManagement::select([
                'case_managements.id',
                DB::raw("CONCAT(COALESCE(case_managements.number, 'No Case Number'), ' (', case_managements.title,')') as text"),
            ])
            ->join('transactions', 'transactions.id', '=', 'case_managements.transaction_id')
            ->where('transactions.client_id', $ars->client->profile->client_id)
            ->get();

        return view('user.ars.edit', compact('ars', 'cases'));
    }

    
    public function update(Request $request, $id)
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

            $inputs = $request->except(['_token']);

            $ars = Ars::findorFail($id);

            // update data
            $inputs['time_start'] = date('H:i:s', strtotime($inputs['time_start']));
            $inputs['time_finnish'] = date('H:i:s', strtotime($inputs['time_finnish']));
            $inputs['billing_instruction'] = ($inputs['billing_instruction'] !== null) ? $inputs['billing_instruction'] : '';
            $ars->fill($inputs);
            $ars->save();

            $ars->ads()->delete();
            $ars->oos()->delete();
            $ars->fads()->delete();

            // saving Descriptions
            $ads = [];
            foreach ($inputs['descriptions'] as $key => $d) {
                $ads[] = [
                    'ars_id'      => $ars->id,
                    'description' => $d
                ];
            }
            ArsAd::insert($ads);

            // Saving Outcomes
            $oos = [];
            foreach ($inputs['outcomes'] as $key => $o) {
                $oos[] = [
                    'ars_id'      => $ars->id,
                    'description' => $d,
                    'outcome_outputcol' => '1'
                ];
            }
            ArsOo::insert($oos);

            // Saving Feature Activity
            $fads = [];
            foreach ($inputs['feacture_activities'] as $key => $f) {
                $fads[] = [
                    'ars_id'      => $ars->id,
                    'description' => $f,
                ];
            }
            ArsFad::insert($fads);

            DB::commit();

            return redirect()
                    ->route('ars.edit', [$ars->id])
                    ->with('message', 'Activity Report Sheet, successfully update!');
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e, 1);
        }
    }

    
    public function destroy($id)
    {
        $ars = Ars::findorFail($id);

        $ars->ads()->delete();
        $ars->oos()->delete();
        $ars->fads()->delete();

        $ars->delete();

        return redirect()
                ->route('ars.index')
                ->with('message', 'Activity Report Sheet, successfully deleted!');
    }

    public function getList()
    {
        $model = Ars::select([
                'ars.*',
                DB::raw('DATE_FORMAT(ars.time_start, "%h:%i %p") as time_start'),
                DB::raw('DATE_FORMAT(ars.time_finnish, "%h:%i %p") as time_finnish')
            ])
            ->with(['client.profile'])
            ->join('clients', 'clients.id', '=', 'ars.client_id')
            ->join('profiles', 'profiles.client_id', '=', 'clients.id');

        $data = DataTables::eloquent($model)
            ->addColumn('action', function ($ars) {
                $menu = [];
                $menu[] = '<a href="'. route('ars.edit',array('ar'=>$ars->id)) .'" class="btn-white btn btn-xs"><i class="fa fa-eye text-success"></i> View</a>';
                $menu[] = '<a href="javascript:void(0);" class="btn-white btn btn-xs delete-ars" data-url="' . route("ars.destroy", [$ars->id]).'" data-id="'. $ars->id.'"><i class="fa fa-times text-danger"></i> Delete</a>';
                return '<div class="btn-group text-right">'.implode($menu).'</div>';
            })
            ->filter(function ($query) {
                if (request()->has('search')) {
                    $query->orWhere('profiles.lastname', 'like', "%" . request('search')['value'] . "%")
                        ->orWhere('profiles.firstname', 'like', "%" . request('search')['value'] . "%")
                        ->orWhere('profiles.middlename', 'like', "%" . request('search')['value'] . "%");
                }
            })
            ->make(true);

        return $data;
    }

}