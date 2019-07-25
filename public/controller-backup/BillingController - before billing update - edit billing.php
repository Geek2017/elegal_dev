<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use Reports\Services\GenerateBillingReport;
use Repositories\BillingRepository;

use App\Billing;
use App\CaseManagement;
use App\Chargeable;
use App\Client;
use App\Contract;
use App\ContractBill;
use App\Counsel;
use App\Note;
use App\OperationalFund;
use App\ServiceReport;
use App\Transaction;
use App\TransactionFeeDetail;
use App\TrustFund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;

class BillingController extends Controller
{
    private $generateBillingReport;
    private $billingRepository;

    public function __construct(BillingRepository $billingRepository, GenerateBillingReport $generateBillingReport)
    {
        $this->billingRepository = $billingRepository;
        $this->generateBillingReport = $generateBillingReport;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.billing.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.billing.create');
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
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function show(Billing $billing)
    {
        $data = Billing::with('client')
            ->with('prevBalance')
            ->find($billing->id);

        $count = Billing::where('client_id', $billing->client_id)
            ->where(function ($query) {
                $query->where('paid', 0)
                    ->orWhere('balance', '>', 0);
            })
            ->count();

        $note = Note::where('name','billing-note')->first();

        return view('user.billing.show', compact('data', 'note', 'count'));

//        return view('user.billing.show', compact('data'));

    }

    public function billInfo($id)
    {
        $ids = ServiceReport::where('billing_id', $id)->pluck('transaction_id')->toArray();
        $data = Transaction::with('contract')
            ->with('cases')
            ->with(['srGeneral' => function($q) use ($id){
                $q->where('billing_id', $id);
            }])
            ->with(['srSpecial.serviceReports' => function($q) use ($id){
                $q->where('billing_id', $id);
            }])
            ->whereIn('status', array('ongoing', 'approved'))
            ->whereIn('id', $ids)
            ->get();

        $data2 = Billing::with('prevBalance')
            ->find($id);

        $prevBalance = Billing::whereDate('created_at', '<', $data2->created_at)->get();

        return response()->json(array($data, $data2, $prevBalance));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function edit(Billing $billing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Billing $billing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billing $billing)
    {
        //
    }

    public function getList()
    {
        $list = Billing::with('serviceReports')->get();
//        return $list;
        $data = DataTables::of($list)
            ->addColumn('count', function ($list) {
                $info = $list->invoice_number;
                return $info;
            })
            ->addColumn('name', function ($list) {
                $info = $list->client->profile->firstname.' '.$list->client->profile->lastname;
                return $info;
            })
            ->addColumn('amount', function ($list) {
                $info = $list->total;
                return $info;
            })
            ->addColumn('status', function ($list) {
                $info = "No Address";
                return $info;
            })
            ->addColumn('action', function ($list) {
                $menu = [];
                // $menu[] = '<button data-id="'.$list->id.'" type="button" class="btn-white btn btn-xs"><i class="fa fa-check text-success"></i> Edit</button>';
               // $menu[] = '<a href="'. route('client.edit',array('client'=>$list->id)) .'" class="btn-white btn btn-xs"><i class="fa fa-pencil text-success"></i> edit</a>';
               
                $menu[] = '<a href="'. route('billing.show',array('billing'=>$list->id)) .'" class="btn-white btn btn-xs"><i class="fa fa-search text-success"></i> show</a>';
                $menu[] = '<a href="'. route('billing.pdf-preview',array('billing_id'=>$list->id)) .'" target="_blank" class="btn-white btn btn-xs"><i class="fa fa-print text-success"></i> Print</a>';
                return '<div class="btn-group text-right">'.implode($menu).'</div>';
            })
            ->make(true);

        return $data;
    }

    public function getMonth(Request $request, ServiceReport $serviceReport, Contract $contract, TransactionFeeDetail $transactionFeeDetail)
    {
        $ids = [];
        $from = '';

        $monthInput = $request->input('month');
        if( ($monthInput == 'All') || ($monthInput == 'all') ){
            $to = Carbon::now()->endOfMonth()->format('Y-m-d');

            $generalRetainer = $contract->where('status','open')
                ->where('contract_type','general')
                ->whereDate('contract_date', '<', $to)
                ->pluck('client_id')->toArray();
            $ids = array_merge($ids, $generalRetainer);

            $serviceReports = $serviceReport->where('billing_id', null)
                ->whereDate('date', '<', $to)
                ->pluck('client_id')->toArray();
            $ids = array_merge($ids, $serviceReports);

            $transactionFeeDetails = $transactionFeeDetail->where('special_billing', 1)
                ->where('billing_id', null)
                ->whereDate('created_at', '<', $to)
                ->pluck('contract_id')->toArray();
            $specialBilling = $contract->where('status','open')
                ->whereIn('id', $transactionFeeDetails)
                ->pluck('client_id')->toArray();
            $ids = array_merge($ids, $specialBilling);

        }else{
            $from = Carbon::parse($monthInput)->startOfMonth()->format('Y-m-d');
            $to = Carbon::parse($monthInput)->endOfMonth()->format('Y-m-d');

            $generalRetainer = $contract->where('status','open')
                ->whereBetween('contract_date', array($from, $to))
                ->where('contract_type','general')
                ->pluck('client_id')->toArray();
            $ids = array_merge($ids, $generalRetainer);

            $serviceReports = $serviceReport->where('billing_id', null)
                ->whereBetween('date', array($from, $to))
                ->pluck('client_id')->toArray();
            $ids = array_merge($ids, $serviceReports);

            $transactionFeeDetails = $transactionFeeDetail->where('special_billing', 1)
                ->where('billing_id', null)
                ->whereBetween('created_at', array($from, $to))
                ->pluck('contract_id')->toArray();

            $specialBilling = $contract->where('status','open')
                ->whereIn('id', $transactionFeeDetails)
                ->pluck('client_id')->toArray();
            $ids = array_merge($ids, $specialBilling, $specialBilling);
        }

        $ids = array_unique($ids);
        $clients = Client::whereIn('id', $ids)
            ->with('profile')
            ->get();
        return response()->json($clients);
//        return response()->json(array($generalRetainer, $transactionFeeDetails, $specialBilling, $serviceReports, $ids, $from, $to));
    }

    public function getServiceReport(Request $request)
    {
        $monthInput = $request->input('month');
        if( ($monthInput == 'All') || ($monthInput == 'all') ){
            $to = Carbon::now()->endOfMonth()->format('Y-m-d');
            $data = ServiceReport::where('client_id', $request->input('client_id'))
                ->where('billing_id', null)
                ->whereDate('date', '<', $to)
                ->with('feeDetail')
                ->get();
        }else{
            $from = Carbon::parse($monthInput)->startOfMonth()->format('Y-m-d');
            $to = Carbon::parse($monthInput)->endOfMonth()->format('Y-m-d');
            $data = ServiceReport::where('client_id', $request->input('client_id'))
                ->where('billing_id', null)
                ->whereBetween('date', array($from, $to))
                ->with('feeDetail')
                ->get();
        }
        return response()->json($data);
    }

    public function getBillables(Request $request, Contract $contract, TransactionFeeDetail $transactionFeeDetail){
        $clientId = $request->input('client_id');
        $from = '';

        $monthInput = $request->input('month');
        if( ($monthInput == 'All') || ($monthInput == 'all') ){
            $now = Carbon::now();
            $to = Carbon::now()->endOfMonth()->format('Y-m-d');

            $generalRetainer = $contract->where('client_id', $clientId)
                ->where('contract_type','general')
                ->whereDate('contract_date', '<', $to)
                ->where('status','open')
                ->get();

            $ids = ServiceReport::where('client_id', $clientId)
                ->where('billing_id', null)
                ->whereDate('date', '<', $to)
                ->pluck('transaction_id')
                ->toArray();
            $ids = array_unique($ids);

            $billPeriod = 'Previous Months - '.$now->endOfMonth()->format('F').' '.$now->endOfMonth()->format('d').', '.$now->endOfMonth()->format('Y');

            $contractIds = $transactionFeeDetail->where('special_billing', 1)
                ->where('billing_id', null)
                ->pluck('contract_id')
                ->toArray();

            $specialBilling = $contract->where('client_id', $clientId)
                ->whereIn('id', $contractIds)
                ->whereDate('contract_date', '<', $to)
                ->where('status','open')
                ->get();

            $specialRetainer = $contract->whereIn('transaction_id', $ids)
                ->where('contract_type','special')
                ->whereDate('contract_date', '<', $to)
                ->where('status','open')
                ->get();

        }else{
            $from = Carbon::parse($monthInput)->startOfMonth()->format('Y-m-d');
            $to = Carbon::parse($monthInput)->endOfMonth()->format('Y-m-d');

            $generalRetainer = $contract->where('client_id', $clientId)
                ->where('contract_type','general')
                ->whereBetween('contract_date', array($from, $to))
                ->where('status','open')
                ->get();

            $ids = ServiceReport::where('client_id', $clientId)
                ->where('billing_id', null)
                ->whereBetween('date', array($from, $to))
                ->pluck('transaction_id')
                ->toArray();
            $ids = array_unique($ids);
            $billPeriod = Carbon::parse($monthInput)->endOfMonth()->format('F').' '.Carbon::parse($monthInput)->startOfMonth()->format('d').' - '.Carbon::parse($monthInput)->endOfMonth()->format('d').', '.Carbon::parse($monthInput)->endOfMonth()->format('Y');

            $contractIds = $transactionFeeDetail->where('special_billing', 1)
                ->where('billing_id', null)
                ->pluck('contract_id')
                ->toArray();

            $specialBilling = $contract->where('client_id', $clientId)
                ->whereIn('id', $contractIds)
                ->whereBetween('contract_date', array($from, $to))
                ->where('status','open')
                ->get();

            $specialRetainer = $contract->whereIn('transaction_id', $ids)
                ->where('contract_type','special')
                ->whereBetween('contract_date', array($from, $to))
                ->where('status','open')
                ->get();
        }

        $client = Client::with('profile')
            ->with('business')
            ->with('billingAddress')
            ->find($clientId);

        $note = Note::whereIn('id', array(1,2,3,4))->get();
        $billNumber = substr(Carbon::now()->format('Y'), -2).'-'.str_pad(Billing::count() + 1, 5, 0, STR_PAD_LEFT);
        return response()->json(array($specialBilling, $generalRetainer, $specialRetainer, $client, $note, $billNumber, $billPeriod, $ids));
    }

    public function getSpecialBilling(Request $request, CaseManagement $caseManagement, Billing $billing, TransactionFeeDetail $transactionFeeDetail)
    {
        $clientId = $request->input('client_id');
//        $case = $caseManagement->where('status','open')
//            ->with('specialFees')
//            ->find($request->input('case_id'));

        $specialBilling = $transactionFeeDetail->with(['cases', 'fee'])
            ->find($request->input('fee_detail_id'));

        $previousBalance = $billing->where('client_id', $clientId)
            ->where(function ($query) {
                $query->where('paid', 0)
                    ->orWhere('balance', '>', 0);
            })
            ->where('merged_to', null)
            ->with(['operationalFund' => function($q){
                $q->whereRaw('total_amount_paid < amount');
            }])
            ->get();

        return response()->json(array($specialBilling, $previousBalance));
    }

    public function getRetainers(Request $request, Contract $contract, ServiceReport $serviceReport, TrustFund $trustFund, Billing $billing, CaseManagement $caseManagement)
    {
        $clientId = $request->input('client_id');


        $monthInput = $request->input('month');
        if( ($monthInput == 'All') || ($monthInput == 'all') ){
            $to = Carbon::now()->endOfMonth()->format('Y-m-d');
            $generalRetainer = $contract->where('client_id', $clientId)
                ->where('contract_type','general')
                ->whereDate('contract_date', '<', $to)
                ->where('status','open')
                ->with(['feeDetail', 'serviceReports'])
                ->first();

        }else{
            $from = Carbon::parse($monthInput)->startOfMonth()->format('Y-m-d');
            $to = Carbon::parse($monthInput)->endOfMonth()->format('Y-m-d');
            $generalRetainer = $contract->where('client_id', $clientId)
                ->where('contract_type','general')
                ->whereBetween('contract_date', array($from, $to))
                ->where('status','open')
                ->with(['feeDetail', 'serviceReports'])
                ->first();
        }

        $inputIds = $request->input('ids', null);
        $cases = null;
        if($inputIds !== null){
            $caseIds = $serviceReport->where('client_id', $clientId)
                ->where('case_id', '!=', null)
                ->where('billing_id', null)
                ->whereIn('id', $inputIds)
                ->pluck('case_id')
                ->toArray(); $caseIds = array_unique($caseIds);

            $cases = $caseManagement->whereIn('id', $caseIds)
                ->where('status','open')
                ->with('serviceReports')
                ->get();
        }


        $trustFundData = $trustFund->where('client_id', $clientId)
            ->orderBy('created_at','DESC')
            ->first();
        $trustFund = 0;
        if($trustFundData){
            $trustFund = $trustFundData->balance;
        }

        $balance = $billing->where('client_id', $clientId)
            ->where(function ($query) {
                $query->where('paid', 0)
                    ->orWhere('balance', '>', 0);
            })
            ->where('merged_to', null)
            ->with(['operationalFund' => function($q){
                $q->whereRaw('total_amount_paid < amount');
            }])
            ->get();

        return response()->json(array($generalRetainer, $cases, $trustFund, $balance));
    }

    public function serviceReportList(Request $request, ServiceReport $serviceReport)
    {
        $clientId = $request->input('client_id');
        $monthInput = $request->input('month');
        if( ($monthInput == 'All') || ($monthInput == 'all') ){
            $to = Carbon::now()->endOfMonth()->format('Y-m-d');
            $serviceReports = $serviceReport->where('client_id', $clientId)
                ->where('billing_id', null)
                ->whereDate('date', '<', $to)
                ->with('feeDetail')
                ->get();
        }else{
            $from = Carbon::parse($monthInput)->startOfMonth()->format('Y-m-d');
            $to = Carbon::parse($monthInput)->endOfMonth()->format('Y-m-d');
            $serviceReports = $serviceReport->where('client_id', $clientId)
                ->where('billing_id', null)
                ->whereBetween('date', array($from, $to))
                ->with('feeDetail')
                ->get();
        }

        return response()->json($serviceReports);
    }

    public function specialBillingList(Request $request, TransactionFeeDetail $transactionFeeDetail, CaseManagement $caseManagement, Transaction $transaction)
    {
        $clientId = $request->input('client_id');
        $transID = $transaction->whereIn('status', ['ongoing', 'approved'])
            ->where('client_id', $clientId)
            ->pluck('id')
            ->toArray();

        $specialBilling = $transactionFeeDetail->where('special_billing', 1)
            ->where('billing_id', null)
            ->whereIn('transaction_id', $transID)
            ->with(['cases', 'fee'])
            ->get();

//        $caseIds = $transactionFeeDetail->where('special_billing', 1)
//            ->where('billing_id', null)
//            ->whereIn('transaction_id', $transID)
//            ->pluck('case_id')
//            ->toArray(); $caseIds = array_unique($caseIds);
//
//        $cases = $caseManagement->whereIn('id', $caseIds)
//            ->where('status','open')
//            ->with('specialFees')
//            ->get();

        return response()->json($specialBilling);
    }

    public function fetchServiceReport(Request $request)
    {
        $ids = $request->input('ids');
        $data = ServiceReport::whereIn('id', $ids)
            ->pluck('transaction_id')
            ->toArray();
        $data = array_unique($data);

        $data = Transaction::whereIn('id', $data)
            ->with('contract')
            ->with('cases')
            ->with('srGeneral')
            ->with('srSpecial')
            ->get();

        $balance = Billing::where('client_id', $request->input('client_id'))
            ->where(function ($query) {
                $query->where('paid', 0)
                    ->orWhere('balance', '>', 0);
            })
            ->where('merged_to', null)
            ->with(['operationalFund' => function($q){
//                $q->where('total_amount_paid', '<', 'amount');
                $q->whereRaw('total_amount_paid < amount');
            }])
            ->get();

        $billings = Billing::where('client_id', $request->input('client_id'))
            ->with([
                'serviceReports',
                'client',
                'client.latestTrustFund',
                'client.business.mobile',
                'client.billingAddress.mobile',
                'client.business.address',
                'client.billingAddress.address',
                'operationalFund'
            ])
            ->orderBy('created_at', 'DESC')
            ->orderBy('bill_date', 'DESC')
            ->whereRaw("
                    paid = 0
                    or
                    (paid = 1 and balance > 0)
                ")
            ->get();

        $client = Client::with('profile')
            ->with('business')
            ->with('billingAddress')
            ->find($request->input('client_id'));

        $trustFundData = TrustFund::where('client_id', $request->input('client_id'))
            ->orderBy('created_at','DESC')
            ->first();
        $trustFund = 0;
        if($trustFundData){
            $trustFund = $trustFundData->balance;
        }

        $number = str_pad(Billing::count() + 1, 5, 0, STR_PAD_LEFT);
        $number = substr(Carbon::now()->format('Y'), -2).'-'.$number;

        $from = Carbon::parse($request->input('month'))->startOfMonth()->format('d');
        $to = Carbon::parse($request->input('month'))->endOfMonth()->format('d');
        $year = Carbon::parse($request->input('month'))->endOfMonth()->format('Y');
        $month = Carbon::parse($request->input('month'))->endOfMonth()->format('F');

        $month = $month.' '.$from.' - '.$to.', '.$year;

        return response()->json(array($data,$client,$balance,$number,$trustFund,$number,$month,$billings));
    }

    public function storeBilling(Request $request, TransactionFeeDetail $transactionFeeDetail)
    {
        if(!auth()->user()->can('add-billing')){
            flash('You have no Permission!', 'danger');
            return back();
        }

        try {
            DB::beginTransaction();

            $number = str_pad(Billing::count() + 1, 5, 0, STR_PAD_LEFT);
            $billAmount = 0;
            $total = 0;
            $taxAmount = 0;
            $month = $request->input('month', 0);
            $pTax = $request->input('percentage_tax', 0);
            $billAmount += number_format($request->input('special', 0.00), 2, '.', '');
            $billAmount += number_format($request->input('general', 0.00), 2, '.', '');
            $billAmount += number_format($request->input('excess', 0.00), 2, '.', '');
            $total += $billAmount;
            if($pTax > 0){
                $divider = (100 - floatval($pTax)) / 100;
                $multiplier = floatval($pTax) / 100;
                $taxAmount += ($request->input('special', 0.00) / $divider) * $multiplier;
                $total += $taxAmount;
            }

            $specialBilling = ($request->input('billing_type') === 'special-billing') ? 1 : 0;

            $data = new Billing();
            $data->client_id = $request->input('client_id');
            $data->invoice_number = $number;
            $data->bill_number = substr(Carbon::now()->format('Y'), -2).'-'.$number;
            $data->bill_date = Carbon::now();
            $data->special = number_format($request->input('special', 0.00), 2, '.', '');
            $data->general = number_format($request->input('general', 0.00), 2, '.', '');
            $data->excess = number_format($request->input('excess', 0.00), 2, '.', '');
            $data->bill_amount = $billAmount;
            $data->percentage_tax = $request->input('percentage_tax', null);
            $data->tax_amount = $taxAmount;
            $data->total = $total;
            $data->special_billing = $specialBilling;
            $data->content = $request->input('content', null);
            if($data->save()){
                $newID = $data->id;
                LogActivity::addToLog('Bill #: '.$data->bill_number, 'Add', 'Billing');
                switch ($request->input('billing_type')){
                    case 'special-billing';
                        $id = $request->input('fee_detail_id');
                        $transFeeDetail = $transactionFeeDetail->find($id);
                        $transFeeDetail->billing_id = $newID;
                        $transFeeDetail->save();
                        $this->contractTotal('special-billing', $id, $data->id, $pTax, $data->client_id, $month);
                        break;
                    case 'retainer-contract';
                        $totalCE = 0;
                        $ids = $request->input('ids', null);

                        if($ids !== null){
                            ServiceReport::whereIn('id', $ids)->each(function($raw) use (&$newID){
                                $raw->billing_id = $newID;
                                $raw->save();
                            });
                            Chargeable::whereIn('sr_id', $ids)->each(function($raw) use (&$totalCE){
                                $totalCE += floatval($raw->total);
                            });

                        }
                        $this->contractTotal('retainer-contract', $ids, $data->id, $pTax, $data->client_id, $month);
                        if($totalCE > 0){
                            $trustFund1 = TrustFund::where('client_id', $data->client_id)->orderBy('id','DESC')->first();
                            $balance1 = 0;
                            if($trustFund1){
                                $balance1 += floatval($trustFund1->balance);
                            }
                            if($balance1 > 0){
                                $trustFund = TrustFund::where('client_id', $data->client_id)->orderBy('id','DESC')->first();
                                $balance = floatval($trustFund->balance);
                                $newTrustFundCredit = 0;
                                $newTrustFundBalance = 0;
                                $newBalance = $balance;
                                $newBalance -= $totalCE;
                                if($newBalance < 1){
                                    // make credit visible to next billing
                                    $totalCE -= floatval($trustFund->balance);
                                    $operationalFund = new OperationalFund();
                                    $operationalFund->client_id = $data->client_id;
                                    $operationalFund->billing_id = $data->id;
                                    $operationalFund->amount = $totalCE;
                                    $operationalFund->balance = $totalCE;
                                    $operationalFund->save();
                                    $newTrustFundCredit = $balance;
                                    $newTrustFundBalance = 0;
                                }else{
                                    if($balance > $totalCE){
                                        $balance -= $totalCE;
                                        $newTrustFundCredit = $totalCE;
                                        $newTrustFundBalance = $balance;
                                    }else{
                                        $totalCE -= $balance;
                                        $operationalFund = new OperationalFund();
                                        $operationalFund->client_id = $data->client_id;
                                        $operationalFund->billing_id = $data->id;
                                        $operationalFund->amount = $totalCE;
                                        $operationalFund->balance = $totalCE;
                                        $operationalFund->save();
                                        $newTrustFundCredit = $balance;
                                        $newTrustFundBalance = 0;
                                    }
                                }
                                $newTrustFund = new TrustFund();
                                $newTrustFund->client_id = $data->client_id;
                                $newTrustFund->billing_id = $data->id;
                                $newTrustFund->credit = $newTrustFundCredit;
                                $newTrustFund->balance = $newTrustFundBalance;
                                $newTrustFund->save();
                                // ========================
                                $this->makePayment($data->client_id);
                            }else{
                                // save chargeable as operational fund credit
                                $operationalFund = new OperationalFund();
                                $operationalFund->client_id = $data->client_id;
                                $operationalFund->billing_id = $data->id;
                                $operationalFund->amount = $totalCE;
                                $operationalFund->balance = $totalCE;
                                $operationalFund->save();
                            }
                        }
                        break;
                }

                // create the pdf for viewing
                $billings = $this->billingRepository->getUnpaidBills($request->input('client_id'));
                $this->generateBillingReport->generateReport($billings);

                DB::commit();
            }

            return response()->json($data);
        } catch (Exception $e) {
            DB::rollback();
        }
    }

    public function contractTotal($type, $ids, $billId, $pTax, $clientId, $month)
    {
        switch ($type){
            case 'special-billing':
                $data = TransactionFeeDetail::find($ids);
                $total = 0;
                $total += $data->total;
                $taxAmount = 0;
                if($pTax > 0){
                    $divider = (100 - floatval($pTax)) / 100;
                    $multiplier = floatval($pTax) / 100;
                    $taxAmount += ($data->total / $divider) * $multiplier;
                    $total += $taxAmount;
                }
                $contractBill = new ContractBill();
                $contractBill->transaction_id = $data->transaction_id;
                $contractBill->billing_id = $data->billing_id;
                $contractBill->case_id = $data->case_id;
                $contractBill->amount = $total;
                $contractBill->special_billing = 1;
                $contractBill->save();
                break;
            case 'retainer-contract':
                if( ($month == 'All') || ($month == 'all') ){
                    $to = Carbon::now()->endOfMonth()->format('Y-m-d');
                    $generalRetainer = Contract::where('client_id', $clientId)
                        ->where('contract_type','general')
                        ->where('status','open')
                        ->whereDate('contract_date', '<', $to)
                        ->with('detail')
                        ->first();
                }else{
                    $from = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
                    $to = Carbon::parse($month)->endOfMonth()->format('Y-m-d');
                    $generalRetainer = Contract::where('client_id', $clientId)
                        ->where('contract_type','general')
                        ->where('status','open')
                        ->whereBetween('contract_date', array($from, $to))
                        ->with('detail')
                        ->first();
                }

                if($generalRetainer){
                    $total = 0;
                    $total += $generalRetainer->detail->total;
                    if(!empty($ids)){
                        $totalMins = 0;
                        $grMin = ServiceReport::where('transaction_id', $generalRetainer->transaction_id)
                            ->whereIn('id', $ids)->sum('minutes');
                        if($grMin > 0){
                            $totalMins += $grMin;
                        }
                        if($totalMins > $generalRetainer->detail->minutes){
                            $excessMin = $totalMins - $generalRetainer->detail->minutes;
                            $perMin = $generalRetainer->detail->total / $generalRetainer->detail->minutes;
                            $total += $excessMin * $perMin;
                        }
                    }

                    $contractBill = new ContractBill();
                    $contractBill->transaction_id = $generalRetainer->transaction_id;
                    $contractBill->billing_id = $billId;
                    $contractBill->amount = $total;
                    $contractBill->save();
                }

                if(!empty($ids)){
                    $transIds = ServiceReport::whereIn('id', $ids)->pluck('transaction_id')->toArray();
                    $transIds = array_unique($transIds);
                    $specialRetainers = Contract::whereIn('transaction_id', $transIds)
                        ->where('contract_type', 'special')
                        ->with('caseDetails')
                        ->get();
                    if(count($specialRetainers)){
                        foreach ($specialRetainers as $specialRetainer){
                            foreach($specialRetainer->caseDetails as $case){
                                $total = 0;
                                $taxAmount = 0;
                                foreach ($case->serviceReports as $serviceReport){
                                    if (in_array($serviceReport->id, $ids))
                                    {
                                        $total += $serviceReport->total;
                                    }
                                }
                                if($pTax > 0){
                                    $divider = (100 - floatval($pTax)) / 100;
                                    $multiplier = floatval($pTax) / 100;
                                    $taxAmount += ($total / $divider) * $multiplier;
                                    $total += $taxAmount;
                                }
                                $contractBill = new ContractBill();
                                $contractBill->transaction_id = $case->transaction_id;
                                $contractBill->billing_id = $billId;
                                $contractBill->case_id = $case->id;
                                $contractBill->amount = $total;
                                $contractBill->save();
                            }
                        }
                    }
                }
                break;
        }
    }

    public function makePayment($client)
    {
        // make payments for previous operational funds
        $trustFund1 = TrustFund::where('client_id', $client)->orderBy('id','DESC')->first();
        $balance1 = 0;
        if($trustFund1){
            $balance1 += floatval($trustFund1->balance);
        }
        if($balance1 > 0){
            $opBalances = OperationalFund::where('client_id', $client)
                ->where('balance','>',0)
                ->orderBy('id','ASC')
                ->get();
            if($opBalances){
                foreach ($opBalances as $opBalance){
                    $trustFund1 = TrustFund::where('client_id', $client)->orderBy('id','DESC')->first();
                    $balance1 = 0;
                    if($trustFund1){
                        $balance1 += floatval($trustFund1->balance);
                    }
                    if($balance1 < 1){
                        return false;
                    }
                    $trustFund = TrustFund::where('client_id', $client)->orderBy('id','DESC')->first();
                    $balance = floatval($trustFund->balance);

                    $opBalanceAmountPaid = floatval($opBalance->total_amount_paid);
                    $opBalanceCredit = floatval($opBalance->balance);

                    $newTrustFundCredit = 0;
                    $newTrustFundBalance = 0;

                    $newBalance = $balance;
                    $newBalance -= $opBalanceCredit;

                    if($newBalance < 1){
                        $opBalanceAmountPaid += $balance;
                        $opBalanceCredit -= $balance;

                        $opBalance->total_amount_paid = $opBalanceAmountPaid;
                        $opBalance->balance = $opBalanceCredit;

                        $newTrustFundCredit = $balance;
                        $newTrustFundBalance = 0;

                        $type = 1;

                    }else{
                        if($balance > $opBalanceCredit){
                            $newTrustFundCredit = $opBalanceCredit;
                            $opBalanceAmountPaid += $opBalanceCredit;
                            $balance -= $opBalanceCredit;
                            $newTrustFundBalance = $balance;
                            $opBalanceCredit = 0;
                            $type = '2: '.$newTrustFundCredit.' = '.$newTrustFundBalance;
                        }else{
                            $opBalanceAmountPaid += $balance;
                            $opBalanceCredit -= $balance;

                            $newTrustFundCredit = $balance;
                            $newTrustFundBalance = 0;
                            $type = 3;
                        }

                        $opBalance->total_amount_paid = $opBalanceAmountPaid;
                        $opBalance->balance = $opBalanceCredit;

                    }

                    $opBalance->save();


                    $newTrustFund = new TrustFund();
                    $newTrustFund->client_id = $opBalance->client_id;
                    $newTrustFund->billing_id = $opBalance->billing_id;
                    $newTrustFund->credit = $newTrustFundCredit;
                    $newTrustFund->balance = $newTrustFundBalance;
                    $newTrustFund->description = $type;
                    $newTrustFund->save();
                }
            }
        }
    }

    public function balance()
    {
        $balance = Billing::where(function ($query) {
            $query->where('paid', 0)
                ->orWhere('balance', '>', 0);
        })->get();
        return $balance;
    }

    public function mockUp()
    {
        return view('user.billing.mockup2');
    }

}