<?php

namespace App\Http\Controllers;

use App\CaseCounsel;
use App\CaseManagement;
use App\Contract;
use App\Fee;
use App\FeeCategory;
use App\Helpers\LogActivity;
use App\ServiceReport;
use App\Transaction;
use App\TransactionFeeDetail;
use App\TrustFund;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;

class TransactionController extends Controller
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
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function caseFeeStore(Request $request)
    {
        $case = 0;
        if($request->input('action') === "add"){
            $data = new TransactionFeeDetail();
            $data->transaction_id = $request->input('transaction_id');
            $data->fee_id = $request->input('fee_id');
            $data->case_id = $request->input('case_id');
            $case = $request->input('case_id');
        }

        if($request->input('action') === "edit"){
            $data = TransactionFeeDetail::find($request->input('case_id'));
            $case = $data->case_id;
        }

        $data->charge_type = $request->input('charge_type');
        $data->free_page = $request->input('free_page');
        $data->charge_doc = $request->input('charge_doc');
        $data->rate_1 = $request->input('rate_1');
        $data->rate_2 = $request->input('rate_2');
        $data->rate = $request->input('rate');
        $data->consumable_time = $request->input('consumable_time');
        $data->excess_rate = $request->input('excess_rate');
        $data->amount = $request->input('amount');
        $data->cap_value = $request->input('cap_value');
        if($data->save()){
            $count = TransactionFeeDetail::where('case_id', $data->case_id)->count();
            $data = TransactionFeeDetail::with('fee')
                ->with('cases')
                ->find($data->id);
            return response()->json(array($data,$count,$case));
        }
    }

    public function tranFeeAction(Request $request)
    {
        $data = TransactionFeeDetail::with('fee')->find($request->input('id'));
        $case = $data->case_id;
        $data->delete();
        return response()->json($case);
    }

    public function storeTrustFund(Request $request)
    {
        $trustFund = TrustFund::where('client_id', $request->input('id'))
            ->orderBy('id','DESC')
            ->first();
        $balance = 0;
        if($trustFund){
            $balance += floatval($trustFund->balance);
        }
        $balance += floatval($request->input('deposit'));
        $data = new TrustFund();
        $data->client_id = $request->input('id');
        $data->deposit = $request->input('deposit');
        $data->balance = $balance;
        $data->description = $request->input('desc');
        $data->save();
        return response()->json($data);
    }

    public function getTrustFund(Request $request, TrustFund $trustFund)
    {
        $data = $trustFund->where('client_id', $request->input('id'))
            ->orderBy('id','DESC')
            ->first();
        return response()->json($data);
    }

    public function trustFundRecord(Request $request, TrustFund $trustFund)
    {
        $data = $trustFund->where('client_id', $request->input('id'))
            ->orderBy('id','DESC')
            ->get();

        $data2 = $trustFund->where('client_id', $request->input('id'))
            ->orderBy('created_at','DESC')
            ->first();

        return response()->json(array($data, $data2));
    }

    public function getTransFee(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('transaction_id');
        $cat = FeeCategory::where('name', $type)->first();
        switch ($type){
            case 'special':
                $data = TransactionFeeDetail::with('fee')
                    ->where('fee_cat_id', $cat->id)
                    ->where('transaction_id', $id)
                    ->get();
                break;
            case 'general':
                $data = TransactionFeeDetail::with('fee')
                    ->where('fee_cat_id', $cat->id)
                    ->where('transaction_id', $id)
                    ->get();
                break;
        }


        return response()->json($data);
    }

    public function storeFee(Request $request)
    {
        if(!auth()->user()->can('add-fee-contract')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        $transaction = Transaction::with('contract')->find($request->input('transaction_id'));
        $total = 0; $caseID = null;
        switch($transaction->contract->contract_type){
            case 'special':
                $caseID = $request->input('case_id');
                if($request->input('special_billing') == 1){
                    switch($request->input('charge_type')){
                        case 'amount':
                            $total = $request->input('amount');
                            break;
                        case 'percentage':
                            $total = ($request->input('percentage') / 100) * $request->input('amount');
                            break;
                    }
                }
                break;
            case 'general':
                $total = $request->input('amount');
                break;
        }
        $data = new TransactionFeeDetail();
        $data->account_id = $request->input('fee_id');
        $data->transaction_id = $transaction->id;
        $data->case_id = $caseID;
        $data->client_id = $transaction->client_id;
        $data->contract_id = $transaction->contract->id;
        $data->fee_id = $request->input('fee_id');
        $data->charge_type = $request->input('charge_type');
        $data->free_page = $request->input('free_page');
        $data->excess_rate = $request->input('excess_rate');
        $data->cap_value = $request->input('cap_value');
        $data->minutes = $request->input('minutes');
        $data->installment = $request->input('installment');
        $data->percentage = $request->input('percentage');
        $data->amount = $request->input('amount');
        $data->total = $total;
        $data->counsel_id = $request->input('counsel_id', null);
        $data->special_billing = $request->input('special_billing');
        if($data->save()){
            $data = TransactionFeeDetail::with('fee')->with('cases')->find($data->id);
            LogActivity::addToLog($data->fee->display_name, 'Add', 'Case Fee');
            return response()->json($data);
        }
    }

    public function transactionAmount(Request $request)
    {
        $data = TransactionFeeDetail::where('transaction_id', $request->input('transaction_id'))->sum('total');
        return response()->json($data);
    }

    public function deleteFee(Request $request, TransactionFeeDetail $transactionFeeDetail, ServiceReport $serviceReport)
    {
        if(!auth()->user()->can('delete-fee-contract')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        $data = $transactionFeeDetail->find($request->input('id'));
        $fee = $data->fee->display_name;
        $case_id = $data->case_id;
        $srItems = 0;
        $srItems += $serviceReport->where('fee_detail_id', $request->input('id'))->count();

        if( ($srItems > 0) || ($data->billing_id !== null) ){
            return response()->json(array($srItems, $case_id, $data));
        }else{
            if($data->delete()){
                $data = 0;
                LogActivity::addToLog($fee, 'Delete', 'Case Fee');
                return response()->json(array($srItems, $case_id, $data));
            }
        }
    }

    public function checkTransaction(Request $request)
    {
        $data = Transaction::with('fees')
            ->with('cases')
            ->find($request->input('id'));
        $count = 0;
        if((count($data->fees) > 0) || (count($data->cases) > 0)){
            $count += 1;
        }
        return response()->json($count);
    }

    public function clientCases(Request $request)
    {
        $inputs = $request->except('_token');
        $q = isset($inputs['q']) ? $inputs['q'] : '';

        if (!isset($inputs['id'])) {
            throw new \Exception("id is required", 1);
        }

        $transactions = Transaction::select([
                'case_managements.id',
                'case_managements.number',
                \DB::raw('COALESCE(case_managements.title, "No Case Name") as title'),
                DB::raw("CONCAT(COALESCE(case_managements.number, 'No Case Number'), ' (', case_managements.title,')') as text"),
                'case_managements.counsel_id',
                DB::raw("CONCAT(profiles.lastname, ', ', profiles.firstname, if(profiles.middlename is not null, CONCAT(' ', profiles.middlename) , '') ) as counsel_full_name")
            ])
            ->join('case_managements', 'case_managements.transaction_id', '=', 'transactions.id')
            ->join('counsels', 'counsels.id', '=', 'case_managements.counsel_id')
            ->join('profiles', 'profiles.counsel_id', '=', 'counsels.id')
            ->where('transactions.client_id', $inputs['id'])
            ->whereRaw("
                    (
                        case_managements.title like '%{$q}%'
                        OR
                        case_managements.number like '%{$q}%'
                        OR
                        case_managements.title IS NULL
                        OR
                        case_managements.number IS NULL

                    )
                ")
            ->whereIn('transactions.status', ['ongoing'])
            ->get();

        return response()->json(['results' => $transactions]);
    }

    public function clientServiceReport(Request $request)
    {
        $inputs = $request->except('_token');

        if (!isset($inputs['id'])) {
            throw new \Exception("id is required", 1);
        }

        $transaction = Transaction::with('servicesReport.case')
            ->where('client_id', $inputs['id'])
            ->firstOrFail();

        return response()->json($transaction);
    }
}
