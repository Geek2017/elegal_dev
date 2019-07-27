<?php

namespace App\Http\Controllers;

use App\CaseManagement;
use App\Client;
use App\Contract;
use App\Counsel;
use App\Fee;
use App\Helpers\LogActivity;
use App\ServiceReport;
use App\Transaction;
use App\TransactionDetail;
use App\TransactionFeeDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->can('browse-contract')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        LogActivity::addToLog(null, 'Browse', 'Contract');
        return view('user.contract.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract, CaseManagement $caseManagement, TransactionFeeDetail $transactionFeeDetail)
    {
        if(!auth()->user()->can('read-contract')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        $contract = $contract
            ->with(['contractBills', 'transaction','caseDetails','client'])
            ->find($contract->id);
        switch ($contract->contract_type){
            case 'special':
                $data = $caseManagement->where('transaction_id', $contract->transaction_id)
                    ->with(['bills', 'counselList'])
                    ->get();
                break;
            case 'general':
                $data = $transactionFeeDetail->where('transaction_id', $contract->transaction_id)
                    ->with('counsel')
                    ->first();
                break;
        }
        LogActivity::addToLog(null, 'Read', 'Contract');
//        return array($contract, $data);

        return view('user.contract.show', compact('contract', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function edit(Contract $contract, CaseManagement $caseManagement, TransactionFeeDetail $transactionFeeDetail)
    {
        if(!auth()->user()->can('edit-contract')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        //fetch case for the contract
        $cases = $caseManagement->where('transaction_id', $contract->transaction_id)
                    ->get();

        foreach($cases as $case){
            $feedetails = $transactionFeeDetail->where('case_id', $case->id)->get();  
        }

        $data = Transaction::with('client')
            ->with('contract')
            ->find($contract->transaction_id);

//        $data = collect($data->client->bill)->splice(6,7);

//        return $data;
        $client_id = str_pad($data->client->id, 5, 0, STR_PAD_LEFT);
        $data['billing'] = $this->billingAdd($data->client);
        $action = 'edit';
        return view('user.contract.create', compact('data','client_id', 'action', 'cases'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        //
    }

    public function getList()
    {
        $list = Contract::where('status', 'open')
            ->with('client', 'contractBills', 'caseDetails')
            ->get();

        $data = DataTables::of($list)
            ->addColumn('number', function ($list) {
                $info = $list->contract_number;
                return $info;
            })
            ->addColumn('type', function ($list) {
                $info = '';
                switch($list->contract_type) {
                    case 'special':
                        $case = [];
                        foreach($list->caseDetails as $caseDetail){
                            $title = (str_word_count($caseDetail->title) > 6) ? substr($caseDetail->title,0,25).' ...' : $caseDetail->title;
                            $case[] = $title;
                        }
                        $info = implode(", ", $case);
                        break;
                    case 'general':
                        $info = strtoupper($list->contract_type).' Retainer';
                        break;
                }
                return $info;
            })
            ->addColumn('client', function ($list) {
                $info = $list->client->profile->full_name;
                return $info;
            })
            ->addColumn('date', function ($list) {
                $info = Carbon::parse($list->contract_date)->toFormattedDateString();
                return $info;
            })
            ->addColumn('amount', function ($list) {
                $total = 0;
                foreach ($list->contractBills as $bill){
                    $total += $bill->amount;
                }
                $info = number_format($total, 2, '.', ',');
                return $info;
            })
            ->addColumn('status', function ($list) {
                $info = $list->status;
                return $info;
            })
            ->addColumn('action', function ($list) {
                $menu = [];
                if(auth()->user()->can('read-contract')) {
                    $menu[] = '<a href="' . route('contract.show', array('contract' => $list->id)) . '" class="btn-white btn btn-xs"><i class="fa fa-search text-success"></i> show</a>';
                }
                if(auth()->user()->can('edit-contract')) {
                    $menu[] = '<a href="'. route('contract.edit',array('contract'=>$list->id)) .'" class="btn-white btn btn-xs"><i class="fa fa-pencil text-success"></i> edit</a>';
                }
//                $menu[] = '<button data-id="'.$list->id.'" type="button" class="btn-white btn btn-xs"><i class="fa fa-times text-danger"></i> delete</button>';
                return '<div class="btn-group text-right">'.implode($menu).'</div>';
            })
            ->make(true);
        return $data;
    }

    public function contractFee(Request $request)
    {
        switch($request->input('type')){
            case 'special':
                $data = CaseManagement::where('transaction_id', $request->input('transaction_id'))
                    ->with('fees')
                    ->with('counselList')
                    ->get();
                break;
            default:
                $data = Transaction::with('fees')->find($request->input('transaction_id'));
        }
        return response()->json($data);
    }

    public function createContract($id)
    {
        if(!auth()->user()->can('add-contract')){
            flash('You have no Permission!', 'danger');
            return back();
        }

        $data = Transaction::where('client_id', $id)
            ->where('status','pending')
            ->with('client')
            ->with('contract')
            ->first();
        if(!$data){
            $data = new Transaction();
            $data->author = Auth::user()->id;
            $data->client_id = $id;
            if($data->save()){
                $con = new Contract();
                $con->transaction_id = $data->id;
                $con->client_id = $data->client_id;
                $con->author = Auth::user()->id;
                if($con->save()){
                    $data = Transaction::with('client')
                        ->with('contract')
                        ->find($data->id);
                }
            }
        }

//        return $data;
        $action = 'create';

        return view('user.contract.create', compact('data', 'action'));
    }

    public function updateContract($id, Request $request)
    {
        $data = Contract::find($id);
        if(($data->contract_type != $request->input('contract_type')) && ($data->contract_type != null)){
            switch ($data->contract_type){
                case 'special':
                    CaseManagement::where('transaction_id', $data->transaction_id)->each(function($row){ $row->delete();});
                    break;
                default:
                    TransactionFeeDetail::where('transaction_id', $data->transaction_id)->each(function($row){ $row->delete();});
            }
        }
        $data->contract_date = Carbon::parse($request->input('contract_date'));
//        $data->start_date = Carbon::parse($request->input('start_date'));
        $data->contract_type = $request->input('contract_type');
        $data->other_conditions = $request->input('other_conditions');
        $data->contract_amount = $request->input('contract_amount');
        $data->total = 0;
        $data->save();
    }

    public function contractStore(Request $request)
    {
//        if(!auth()->user()->can('add-contract')){
//            flash('You have no Permission!', 'danger');
//            return back();
//        }
        $tran = Transaction::with('fees')->find($request->input('id'));
        $fee = count($tran->fees);
        $data = null;
        if($fee > 0){
            $data = Contract::where('transaction_id', $tran->id)->first();
//            $firstFee = TransactionFeeDetail::where('transaction_id', $tran->id)->orderBy('id', 'ASC')->first();

            if($request->input('action') === 'add'){
                $count = Transaction::whereNotIn('status',['pending'])->count();
                $count = str_pad($count + 1, 6, 0, STR_PAD_LEFT);
                $count = $count .'-'. Carbon::now()->format('m-Y');
                $data->contract_number = $count;
                $data->status = 'Open';
            }
            if($data->save()){
                $tran->status = 'Ongoing';
                $tran->save();
            }
        }
        $tran = Transaction::with('fees')->find($request->input('id'));
        $fee = count($tran->fees);
        return response()->json(array($fee, $data));
    }

    public function billingAdd($client){
        return collect($client->business->find($client->billing))->splice(6,7);
    }

    public function getCaseCounsel(Request $request, CaseManagement $caseManagement)
    {
        $id = $request->input('case_id');
        $data = $caseManagement->with('counselList')->find($id);
        return response()->json($data);
    }

    public function casename(Request $request)
    {
        $type = $request->input('type');
        $ids = Contract::where('client_id', $type)->pluck('transaction_id')->toArray();

        $case = CaseManagement::whereIn('transaction_id', $ids)->get();
        return response()->json($case);
    }
}
