<?php

namespace App\Http\Controllers;

use App\CaseCounsel;
use App\CaseManagement;
use App\Chargeable;
use App\Client;
use App\Contract;
use App\Fee;
use App\Helpers\LogActivity;
use App\Profile;
use App\ServiceReport;
use App\Transaction;
use App\TransactionFeeDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ids = Transaction::where('status','ongoing')
            ->pluck('client_id')
            ->toArray();
//        $clients = Client::whereIn('id',$ids)->with('profile')->get();
        $clients = Profile::whereIn('client_id',$ids)->orderBy('firstname')->get();
        return view('user.service-report.create', compact('clients'));
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
     * @param  \App\ServiceReport  $serviceReport
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceReport $serviceReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceReport  $serviceReport
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceReport $serviceReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceReport  $serviceReport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceReport $serviceReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceReport  $serviceReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceReport $serviceReport)
    {
        //
    }

    public function clientContract(Request $request){
        $contractIds = Contract::where('client_id', $request->input('id'))
            ->where('status','open')
            ->where('contract_type','!=','billing')
            ->pluck('transaction_id')->toArray();

        $data = Transaction::whereIn('id', $contractIds)
            ->where('status','ongoing')
            ->with('contract')
            ->get();

        return response()->json($data);
    }

    public function contractInfo(Request $request){
        $data = Transaction::with('cases')
            ->with('contract')
            ->with('fees')
            ->find($request->input('id'));
        switch($data->contract->contract_type){
            case 'special':
                return response()->json(array($data->contract->contract_type, $data->cases));
                break;
            default:
                return response()->json(array($data->contract->contract_type, $data->fees));
        }
    }

    public function caseFee(Request $request){
        $data = TransactionFeeDetail::with('fee')
            ->where('case_id',$request->input('id'))
            ->whereIn('charge_type',array('standard','fixed'))
            ->get();
        $counsel = CaseCounsel::where('case_id',$request->input('id'))
            ->where('lead',0)
            ->count();
        return response()->json(array($data, $counsel));
    }

    public function storeServiceReport(Request $request){

        $count = ServiceReport::count();
        $count = str_pad($count + 1, 5, 0, STR_PAD_LEFT);
        $count = Carbon::now()->format('my') .'-'. $count;
        $totalPage = $request->input('page_count');

        $fee = TransactionFeeDetail::with(['transaction','contract'])
            ->find($request->input('id'));

        switch($request->input('type')){
            case 'sr-edit':
                $data = ServiceReport::find($request->input('srid'));
                break;
            default:
                $data = new ServiceReport();
                $data->sr_number = $count;
        }


        $data->case_id = $request->input('case_id', null);
        $data->client_id = $fee->transaction->client->id;
        $data->transaction_id = $fee->transaction->id;
        if( ($fee->counsel_id != null) && ($fee->contract->contract_type == 'special')){
            $data->counsel_id = $fee->counsel_id;
        }
        if( ($request->input('counsel_rendered') == 1) && ($fee->contract->contract_type == 'general')){
            $data->counsel_id = $fee->counsel_id;
        }

        $data->fas_number = $request->input('fas_number',null);
        $data->fee_detail_id = $fee->id;
        $data->fee_description = $request->input('fee_description',null);
        $data->description = $request->input('description');
        $data->date = Carbon::parse($request->input('date'));
        $data->page_count = $totalPage;
        $data->minutes = $request->input('minutes');
        switch($fee->charge_type){
            case 'time':
                $perMinute = floatval($fee->amount) / floatval($fee->minutes);
                $totalAmount = intval($request->input('minutes')) * floatval($perMinute);
                $data->total = $totalAmount;
                break;
            case 'document':
                $totalAmount = 0;
                if($totalPage > $fee->free_page){
                    $totalPage = $totalPage - $fee->free_page;
                    $totalAmount += $fee->amount;
                    $totalAmount += intval($totalPage) * floatval($fee->excess_rate);
                    if( ($fee->cap_value > 0) && ($totalAmount > $fee->cap_value)){
                        $totalAmount = $fee->cap_value;
                    }
                }else{
                    $totalAmount += $fee->amount;
                }
                $data->total = $totalAmount;
                break;
            default:
                $data->total = $request->input('total');
        }
        if($data->save()){
            $data = ServiceReport::with('feeDetail')->find($data->id);
            LogActivity::addToLog($data->feeDetail->fee->display_name, 'Add', 'Service Report');
            return response()->json($data);
        }





    }

    public function getServiceReport(Request $request){
        $data = ServiceReport::where('fee_detail_id', $request->input('id'))
            ->with('feeDetail')
            ->with('chargeables')
            ->get();
        return response()->json($data);
    }

    public function feeInfo(Request $request)
    {
        $data = TransactionFeeDetail::with('fee')
            ->with('counsel')
            ->with('serviceReport')
            ->find($request->input('id'));
        return response()->json($data);
    }

    public function feeSrInfo(Request $request)
    {
        $data0 = TransactionFeeDetail::with('fee')
            ->with('counsel')
            ->with('serviceReport')
            ->find($request->input('id'));

        $data1 = ServiceReport::find($request->input('srid'));

        return response()->json(array($data0, $data1));
    }

    public function storeChargeableExpense(Request $request)
    {
        $data = new Chargeable();
        $data->sr_id = $request->input('sr_id');
        $data->fee_id = $request->input('fee_id');
        $data->description = $request->input('description');
        $data->qty = $request->input('qty');
        $data->total = $request->input('total');
        if($data->save()){
            $data = Chargeable::with('serviceReport')->find($data->id);
            LogActivity::addToLog($data->fee->display_name, 'Add', 'Chargeable Expense');
            return response()->json($data);
        }
    }

    public function deleteChargeableExpense(Request $request)
    {
        $data = Chargeable::find($request->input('id'));
        $ceName = $data->fee->display_name;
        $sr_id = $data->sr_id;
        if($data->delete()){
            LogActivity::addToLog($ceName, 'Delete', 'Chargeable Expense');
            return response()->json($sr_id);
        }
    }

    public function deleteServiceReport(Request $request, ServiceReport $serviceReport, Chargeable $chargeable)
    {
        $data = $serviceReport->find($request->input('id'));
        $srName = $data->feeDetail->fee->display_name;
        $id = $data->id;
        $id2 = $data->fee_detail_id;
        $ceItems = $chargeable->where('sr_id', $id)->count();
        if( ($ceItems > 0) || ($data->billing_id !== null) ){
            return response()->json(array($ceItems, $id2, $data));
        }else{
            if($serviceReport->destroy($request->input('id'))){
                LogActivity::addToLog($srName, 'Add', 'Service Report');
                return response()->json(array($ceItems, $id2, $data));
            }
        }

    }
}
