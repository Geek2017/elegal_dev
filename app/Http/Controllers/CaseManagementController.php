<?php

namespace App\Http\Controllers;

use App\ContractBill;
use App\Helpers\LogActivity;
use App\ServiceReport;
use DB;
use App\CaseCounsel;
use App\CaseManagement;
use App\Counsel;
use App\TransactionFeeDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CaseManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CaseManagement  $caseManagement
     * @return \Illuminate\Http\Response
     */
    public function show(CaseManagement $caseManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CaseManagement  $caseManagement
     * @return \Illuminate\Http\Response
     */
    public function edit(CaseManagement $caseManagement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CaseManagement  $caseManagement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CaseManagement $caseManagement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CaseManagement  $caseManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy(CaseManagement $caseManagement)
    {
        //
    }

    public function createCase(Request $request)
    {
        $data = CaseManagement::where('transaction_id', $request->input('id'))
            ->where('temp',1)
            ->with('counsel_list')
            ->first();
        if(!$data){
            $data = new CaseManagement();
            $data->transaction_id = $request->input('id');
            $data->save();
            $data = CaseManagement::with('counsel_list')->find($data->id);
        }
        $data2 = Counsel::with('profile')->get();
        return response()->json(array($data, $data2));
    }

    public function addCoCounsel(Request $request)
    {
        $data = new CaseCounsel();
        $data->case_id = $request->input('case_id');
        $data->counsel_id = $request->input('id');
        if($data->save()){
            $data2 = CaseCounsel::where('case_id',$data->case_id)
                ->with('info')
                ->get();

            if($request->input('lead') != ''){
                $lead = CaseCounsel::where('case_id',$data->case_id)
                    ->where('lead',1)
                    ->first();
                if($lead){
                    $lead->counsel_id = $request->input('lead');
                }else{
                    $lead = new CaseCounsel();
                    $lead->case_id = $data->case_id;
                    $lead->counsel_id = $request->input('lead');
                    $lead->lead = 1;
                }
                $lead->save();
            }
            return response()->json($data2);
        }
    }

    public function removeCoCounsel(Request $request)
    {
        CaseCounsel::find($request->input('id'))->delete();
    }

    public function loadCounsel(Request $request)
    {
        $data = Counsel::with('profile')->get();
        return response()->json($data);
    }

    public function actionCase(Request $request)
    {
        $data = CaseManagement::with('counsel_list')->find($request->input('id'));
        switch ($request->input('action')){
            case 'edit':
                $data2 = Counsel::with('profile')->get();
                return response()->json(array($data, $data2));
                break;
            case 'delete':
                $data->delete();
                break;
        }
    }

    public function storeCase(Request $request)
    {
        if(!auth()->user()->can('add-case-contract')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        $ids = $request->input('co_counsel');
//        return array($request->input('counsel_id'), $ids);
        switch($request->input('action')){
            case 'add':
                $data = new CaseManagement();
                LogActivity::addToLog($request->input('title'), 'Add', 'Case');
                break;
            case 'edit':
                $data = CaseManagement::find($request->input('case_id'));
                LogActivity::addToLog($request->input('title'), 'Edit', 'Case');
                break;
        }

        $caseDate = ($request->input('date') == 'For Filing') ? null : Carbon::parse($request->input('date'));

        $data->transaction_id = $request->input('transaction_id');
        $data->title = $request->input('title');
        $data->venue = $request->input('venue');
        $data->date = $caseDate;
        $data->number = $request->input('number');
        $data->class = $request->input('case_class');
        $data->counsel_id = $request->input('counsel_id');
        $data->creator_id = \Auth::user()->id;
        if($data->save()){
            if($request->input('action') === 'edit'){
                CaseCounsel::where('case_id', $data->id)->get()->each(function($row){$row->delete();});
            }

            $counsel = new CaseCounsel();
            $counsel->case_id = $data->id;
            $counsel->counsel_id = $request->input('counsel_id');
            $counsel->lead = 1;
            if($counsel->save()){
                if($ids){
                    foreach ($ids as $id){
                        $coCounsel = new CaseCounsel();
                        $coCounsel->case_id = $data->id;
                        $coCounsel->counsel_id = $id;
                        $coCounsel->save();
                    }
                }
            }
            return $data->id;
        }
    }

    public function caseServiceReport(Request $request)
    {
        $inputs = $request->except('_token');

        $data = CaseManagement::with('latestServiceReports');

        if (isset($inputs['id'])) {
            $data->where('id', $inputs['id']);
        }

        return $data->first();
    }

    public function cases(Request $request)
    {
        $cases = CaseManagement::select([
                'case_managements.*',
                DB::raw("COALESCE(case_managements.number, 'No Available') as number"),
                DB::raw("
                        (
                            SELECT
                                count(*)
                            FROM case_trackers
                            where case_trackers.case_management_id = case_managements.id
                            AND case_trackers.status = 'P'
                        ) as no_of_pending_case_activities
                    ")
            ])
            ->with(['transaction.contract', 'transaction.client.profile', 'counsel.profile'])
            ->orderBy('no_of_pending_case_activities', 'DESC');

        $data = DataTables::of($cases->get())
            ->addColumn('client_full_name', function ($case) {
                $info = $case->transaction->client->profile->full_name;
                return $info;
            })
            ->addColumn('client_full_name', function ($case) {
                $info = $case->transaction->client->profile->full_name;
                return $info;
            })
            ->addColumn('counsel_full_name', function ($case) {
                $info = $case->counsel->profile->full_name;
                return $info;
            })
            ->addColumn('contract_no', function ($case) {
                $info = $case->transaction->contract->contract_number;
                return $info;
            })
            ->addColumn('action', function ($case) {
                $menu = [];
                $menu[] = '<a href="'. route('case-tracker.client-case', ['id'=>$case->id]) .'" class="btn-info btn btn-xs"><i class="fa fa-pencil"></i> edit</a>';
                return '<div class="btn-group text-right">'.implode($menu).'</div>';
            })
            ->make(true);

        return $data;
    }

    public function editCase(Request $request, CaseManagement $caseManagement, ContractBill $contractBill)
    {
        if(!auth()->user()->can('edit-case-contract')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        $data = $caseManagement->with('counselList')
            ->find($request->input('id'));
        $billing = $contractBill->where('transaction_id', $data->transaction_id)
            ->where('case_id', $data->id)
            ->get();
        return response()->json(array($data, $billing));
    }

    public function updateCase(Request $request, CaseManagement $caseManagement)
    {
//        $data = $caseManagement->find($request->input('id'));
//        $data->title = $request->input('');
//        $data->venue = $request->input('');
//        $data->number = $request->input('');
//        $data->class = $request->input('');
//        if($data->save()){
//
//        }
//        return response()->json($data);
    }

    public function deleteCase(Request $request, CaseManagement $caseManagement, ServiceReport $serviceReport, TransactionFeeDetail $transactionFeeDetail)
    {
        if(!auth()->user()->can('delete-case-contract')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        $data = $caseManagement->find($request->input('id'));
        $transactionId = $data->transaction_id;
        switch($request->input('action')){
            case 'check':
                //find service reports
                $data2 = $serviceReport->where('transaction_id', $transactionId)
                    ->where('case_id', $data->id)
                    ->get();
                //find special billing
                $data3 = $transactionFeeDetail->where('transaction_id', $transactionId)
                    ->where('case_id', $data->id)
                    ->where('special_billing', 1)
                    ->get();
                return response()->json(array($data, $data2, $data3));
                break;
            case 'delete':
                if($data->delete()){
                    return response()->json('deleted');
                }
                break;
        }
    }

}
