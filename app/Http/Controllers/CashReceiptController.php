<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Account;
use App\Billing;
use App\CashReceipt;
use App\WalkInChargeSlip;
use App\Transaction;
use App\TransactionFeeDetail;

use Repositories\ClientRepository;
use Repositories\CashReceiptRepository;
use Yajra\DataTables\DataTables;

class CashReceiptController extends Controller
{
    private $clientRepository, $cashReceiptRepository;

    public function __construct(ClientRepository $clientRepository, CashReceiptRepository $cashReceiptRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->cashReceiptRepository = $cashReceiptRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.cash-receipt.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cashTypeACcount = Account::where('is_cash_type', 1)->where('type', 'A')->get();
        $date = Carbon::now()->format('Y-m-d');

        $regurlarClients = $this->clientRepository->getClientWithActiveBill();
        $walkInClients = $this->clientRepository->getClientWithActiveWalkIn();

        return view('user.cash-receipt.create', compact('cashTypeACcount', 'date', 'regurlarClients', 'walkInClients'));
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
            $inputs = $request->except(['_token']);
            $message = '';

            // if (!isset($inputs['date'])) {
                // $inputs['payment_date'] = Carbon::now();
            // }
            
            // dd($inputs);z
            
            switch (strtolower($inputs['type'])) {
                case 'walk-in':
                    $this->paidWalkInChargeSlip($inputs);
                    $message = 'Payment for walk-in successfully recorded!';
                    break;
                case 'billing':
                    \DB::beginTransaction();
                    $this->payBill($inputs);
                    $message = 'Payment for bill successfully recorded!';
                    \DB::commit();
                    break;
                
                default:
                    throw new \Exception("Wrong transaction", 1);
                    
                    break;
            }

            DB::commit();
            
            return redirect()
                ->route('cash-receipt.index')
                ->with('message', $message);
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e, 1);
        }
    }

    private function paidWalkInChargeSlip($inputs)
    {
        $walkInChargeSlip = WalkInChargeSlip::with(['transaction'])
                ->where('client_id', $inputs['client_id'])
                ->where('id', $inputs['walk_in_charge_slip_id'])
                ->first();

        $cashReceipt = CashReceipt::create([
            'account_id'             => $inputs['walk_in_account_id'],
            'transaction_id'         => $walkInChargeSlip->transaction_id,
            'walk_in_charge_slip_id' => $walkInChargeSlip->id,
            'client_id'              => $inputs['client_id'],
            'cash_receipt_no'        => $inputs['walk_in_cash_receipt_no'],
            'amount_due'             => $inputs['amount_due'],
            'amount_paid'            => $inputs['walk_in_amount_paid'],
            'change'                 => $inputs['walk_in_amount_paid'] - $inputs['amount_due'],
            'balance'                => $inputs['amount_due'] - $inputs['walk_in_amount_paid'],
            'payment_date'           => $inputs['payment_date']
        ]);

        $walkInChargeSlip->transaction->status = 'Approved';
        $walkInChargeSlip->transaction->save();

        // return 'done';
    }

    private function payBill($inputs)
    {
        // list of clients bill to be paid
        $billIds = explode(',', $inputs['billing_ids']);

        // get the list of billing
        $billings = Billing::whereIn('id', $billIds)
            ->with(['operationalFund' => function ($query) {
                return $query->whereRaw("
                        balance > 0
                        or 
                        (balance = 0 and total_amount_paid = 0)
                    ");
            }])
            ->orderBy('bill_date')
            ->get();
        
        /**
         * This amount should be equally distributed
         * 
         * @var [type]
         */
        $clientPaid = $inputs['billing_amount_paid'];
        // die($clientPaid);

        $clientMoneyRemainingBalance = $clientPaid;

        // 1. save Cash Receipt
        $balance = abs($clientPaid - $inputs['amount_due']);
        $change = $inputs['amount_due'] - $clientPaid;
        $cashReceipt = CashReceipt::create([
            'account_id'             => $inputs['billing_account_id'],
            'client_id'              => $inputs['client_id'],
            'cash_receipt_no'        => $inputs['billing_cash_receipt_no'],
            'amount_due'             => $inputs['amount_due'],
            'amount_paid'            => $clientPaid,
            'change'                 => $change,
            'balance'                => $balance,
            'payment_date'           => $inputs['payment_date']
        ]);

        foreach ($billings as $key => $bill) {
            $ofBalance = 0;

            // check if bill has operational fund
            if ($bill->operationalFund) {

                $amountPaid = 0;
                /**
                 * In this payment we prioritize operational Fund amount to be paid
                 * rather than the bill amount
                 */
                if ($bill->paid) {
                    /**
                     * if the bill was already paid then we will look for the balance
                     * of the operational fund
                     */
                    if ($bill->operationalFund->balance > 0) {
                        if ($bill->operationalFund->balance >= $clientMoneyRemainingBalance && $clientMoneyRemainingBalance) {
                            $amountPaid = $clientMoneyRemainingBalance;
                            $bill->operationalFund->total_amount_paid += $amountPaid;
                            $bill->operationalFund->balance = $bill->operationalFund->balance - $amountPaid;
                            $bill->operationalFund->save();
                        } else if ($clientMoneyRemainingBalance >= $bill->operationalFund->balance && $clientMoneyRemainingBalance) {
                            // since the payment of the client is higher the that amount
                            // we set the total_amount_paid = to the amount
                            // set the balance to zero (0)
                            $amountPaid = $bill->operationalFund->balance;
                            $bill->operationalFund->total_amount_paid = $bill->operationalFund->total_amount_paid + $bill->operationalFund->balance;
                            $bill->operationalFund->balance = 0;
                            $bill->operationalFund->save();
                        }
                        
                        // we less the paid operational amount to the client total pay
                        $clientMoneyRemainingBalance -= $bill->operationalFund->balance;
                    }
                } else  {
                    /**
                     * if the bill was not yet paid then we will look for the amount
                     * of the operational fund
                     */
                    if ($bill->operationalFund->amount >= $clientMoneyRemainingBalance && $clientMoneyRemainingBalance) {
                        // this mean that the payment of the client only suit the Operational fund amount
                        // 2. save total paid and balance.
                        $amountPaid = $clientMoneyRemainingBalance;
                        $bill->operationalFund->total_amount_paid += $amountPaid;
                        $bill->operationalFund->balance = $bill->operationalFund->amount - $amountPaid;
                        $bill->operationalFund->save();
                    } else if ($clientMoneyRemainingBalance >= $bill->operationalFund->amount && $clientMoneyRemainingBalance) {
                        // since the payment of the client is higher the that amount
                        // we set the total_amount_paid = to the amount
                        // set the balance to zero (0)
                        $amountPaid = $bill->operationalFund->amount;
                        $bill->operationalFund->total_amount_paid = $bill->operationalFund->amount;
                        $bill->operationalFund->balance = 0;
                        $bill->operationalFund->save();
                    }

                    // we less the paid operational amount to the client total pay
                    $clientMoneyRemainingBalance -= $amountPaid;
                }

                // 3. create operational fund cash receipt
                $this->saveBillOperationalFundCashReceipt([
                    'cash_receipt_id'     => $cashReceipt->id,
                    'operational_fund_id' => $bill->operationalFund->id,
                    'amount_paid'         => $amountPaid,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);
            }

            // 4. update bill
            $clientMoneyRemainingBalance = $this->updateBillData($bill, $cashReceipt, $clientMoneyRemainingBalance);
        }

        return 'done';
    }

    private function updateBillData($bill, $cashReceipt, $clientMoneyRemainingBalance)
    {
        if (!$bill->paid) {
            // we expect that the remaining money paid by the client is > 0
            $bill->paid = 1; // so we always set the paid column to true
            $amountPaid = 0;
            if ($bill->total >= $clientMoneyRemainingBalance && $clientMoneyRemainingBalance) {
                $amountPaid = $clientMoneyRemainingBalance;
                $clientMoneyRemainingBalance = $bill->total - $amountPaid;
                $bill->balance = $clientMoneyRemainingBalance;
            } else if ($clientMoneyRemainingBalance >= $bill->total && $clientMoneyRemainingBalance) {
                $amountPaid = $bill->total;
                $clientMoneyRemainingBalance = $clientMoneyRemainingBalance - $bill->total;
                $bill->balance = 0;
            }
            $bill->save();// save it
        } else {
            // check the bill balance is greater then the client money
            if ($bill->balance >= $clientMoneyRemainingBalance && $clientMoneyRemainingBalance) {
                $bill->balance -= $clientMoneyRemainingBalance;

                $amountPaid = $clientMoneyRemainingBalance;
            } else if ($clientMoneyRemainingBalance >= $bill->balance && $clientMoneyRemainingBalance) {
                $amountPaid = $bill->balance;

                $bill->balance = 0;
            }

            $bill->save();
            // we less the paid billing balance to the client total pay
            $clientMoneyRemainingBalance -= $amountPaid;
        }

        // dd($bill);

        // 5. create billing cash receipt
        $this->saveBillOperationalFundCashReceipt([
            'cash_receipt_id' => $cashReceipt->id,
            'billing_id'      => $bill->id,
            'amount_paid'     => $amountPaid,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // return the remaining money
        return $clientMoneyRemainingBalance;
    }

    private function saveBillOperationalFundCashReceipt($inputs)
    {
        return \DB::table('bill_operational_fund_receipts')
            ->insert($inputs);
    }

    public function paidTrustFund($inputs)
    {
        $user = Auth::user();
        // Create Transaction
        $transaction = Transaction::create(['user_id' => $user->id, 'client_id' => $inputs['client_id'], 'status' => 'approved']);

        $cashReceipt = CashReceipt::create([
            'account_id'             => $inputs['trust_fund_account_id'],
            'transaction_id'         => $transaction->id,
            'client_id'              => $inputs['client_id'],
            'cash_receipt_no'        => $inputs['trust_fund_cash_receipt_no'],
            'amount_paid'            => $inputs['trust_fund_amount_paid'],
            'is_trust_fund'          => 1
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CashReceipt  $cashReceipt
     * @return \Illuminate\Http\Response
     */
    public function show(CashReceipt $cashReceipt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CashReceipt  $cashReceipt
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cashReceipt = $this->cashReceiptRepository->getById($id);

        return view('user.cash-receipt.edit', compact('cashReceipt'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CashReceipt  $cashReceipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            \DB::beginTransaction();
            $cashReceipt = $this->cashReceiptRepository->getBillOperationalFundReceipt($id);
            $amountPaid = $request['amount_paid'];
            $clientMoneyRemainingBalance = $amountPaid;
            
            $cashReceiptId = $cashReceipt->id;
            $billIdThatHasBeenUpdated = [];
            $operationalFundIdThatHasBeenUpdated = [];

            $totalAmountDue = 0;
            foreach ($cashReceipt->billOperationalFundReceipt as $key => $cr) {

                if ($clientMoneyRemainingBalance > 0) {
                    if ($cr->operational_fund_id) {
                        $operationalFund = $cr->operationalFund;
                        $operationalFundIdThatHasBeenUpdated[] = $operationalFund->id;

                        // Check operational fund balance != 0
                        // if ($operationalFund->balance > 0) {
                            $tmpTotalAmountPaidBeforePayment = $operationalFund->total_amount_paid - $cr->amount_paid;
                            $tmpBalanceBeforePayment = $operationalFund->balance + $cr->amount_paid;
                            $totalAmountDue += $tmpBalanceBeforePayment;
                            if ($tmpBalanceBeforePayment >= $clientMoneyRemainingBalance && $clientMoneyRemainingBalance) {
                                $amountPaid = $clientMoneyRemainingBalance;
                                $operationalFund->total_amount_paid = $tmpTotalAmountPaidBeforePayment;
                                $operationalFund->total_amount_paid += $amountPaid;
                            } else if ($clientMoneyRemainingBalance >= $tmpBalanceBeforePayment && $clientMoneyRemainingBalance) {
                                $amountPaid = $tmpBalanceBeforePayment;
                                $operationalFund->total_amount_paid = $tmpBalanceBeforePayment;
                                $operationalFund->total_amount_paid += $amountPaid;
                            }
                            
                            $operationalFund->balance = $tmpBalanceBeforePayment - $amountPaid;
                            $operationalFund->save();

                            $clientMoneyRemainingBalance -= $amountPaid;

                            // Update
                            \DB::table('bill_operational_fund_receipts')
                                ->where('cash_receipt_id', $cr->cash_receipt_id)
                                ->where('operational_fund_id', $operationalFund->id)
                                ->update(['amount_paid' => $amountPaid]);
                        // } else {
                        //     $clientMoneyRemainingBalance -= $cr->amount_paid;
                        // }
                    }  else if ($cr->billing_id) {
                        $billing = $cr->billing;
                        $billIdThatHasBeenUpdated[] = $billing->id;
                        
                        // if ($billing->balance > 0) {


                            if ($billing->operationalFund && $billing->operationalFund->balance > 0) {
                                $tmpOperationalFundBalanceBeforePayment = $billing->operationalFund->balance + $cr->amount_paid;
                            } 
                            // else {
                            //     $clientMoneyRemainingBalance = abs($clientMoneyRemainingBalance - $cr->amount_paid);
                            // }

                            $tmpBillBalanceBeforePayment = $billing->balance + $cr->amount_paid;
                            $totalAmountDue += $tmpBillBalanceBeforePayment;

                            if ($tmpBillBalanceBeforePayment >= $clientMoneyRemainingBalance && $clientMoneyRemainingBalance) {

                                $billing->balance = $tmpBillBalanceBeforePayment - $clientMoneyRemainingBalance;
                                $billing->paid = 1;
                                $billing->save();

                                // Update
                                \DB::table('bill_operational_fund_receipts')
                                    ->where('cash_receipt_id', $cr->cash_receipt_id)
                                    ->where('billing_id', $cr->billing_id)
                                    ->update(['amount_paid' => $clientMoneyRemainingBalance]);

                                $clientMoneyRemainingBalance = 0;
                            } else if ($clientMoneyRemainingBalance >= $tmpBillBalanceBeforePayment && $clientMoneyRemainingBalance) {
                                
                                $clientMoneyRemainingBalance -= $tmpBillBalanceBeforePayment;
                                $billing->balance = 0;
                                $billing->paid = 1;
                                $billing->save();

                                // Update
                                \DB::table('bill_operational_fund_receipts')
                                    ->where('cash_receipt_id', $cr->cash_receipt_id)
                                    ->where('billing_id', $cr->billing_id)
                                    ->update(['amount_paid' => $tmpBillBalanceBeforePayment]);
                                    
                                // dd($clientMoneyRemainingBalance, 2);
                            }

                        // } else {
                            // $clientMoneyRemainingBalance -= $cr->amount_paid;
                        // }

                        // dd($clientMoneyRemainingBalance);
                    }
                } 
                // else {
                //     if ($cr->operational_fund_id) {
                //         $operationalFund = $cr->operationalFund;
                //         $tmpBalanceBeforePayment = $operationalFund->balance + $cr->amount_paid;
                //         $operationalFund->total_amount_paid = $tmpTotalAmountPaidBeforePayment;
                //         $operationalFund->balance = $tmpBalanceBeforePayment;
                //         $operationalFund->save();

                //         // Delete
                //         \DB::table('bill_operational_fund_receipts')
                //             ->where('cash_receipt_id', $cr->cash_receipt_id)
                //             ->where('billing_id', $cr->billing_id)
                //             ->delete();
                //     }  else if ($cr->billing_id) {
                //         $billing = $cr->billing;
                //         $tmpBillBalanceBeforePayment = $billing->balance + $cr->amount_paid;
                //         $billing->balance = $tmpBillBalanceBeforePayment;
                //         $billing->paid = 1;
                //         $billing->save();

                //         // Delete
                //         \DB::table('bill_operational_fund_receipts')
                //             ->where('cash_receipt_id', $cr->cash_receipt_id)
                //             ->where('billing_id', $cr->billing_id)
                //             ->delete();
                //     }

                // }
            }

            // update cash Receipt
            $cashReceipt->amount_due = $totalAmountDue;
            $cashReceipt->amount_paid = $request->amount_paid;
            $cashReceipt->cash_receipt_no = $request->cash_receipt_no;
            $cashReceipt->payment_date = $request->payment_date;
            $cashReceipt->save();

            $this->reverseBill($billIdThatHasBeenUpdated, $cashReceipt->billOperationalFundReceipt);
            $this->reverseOperationalFund($operationalFundIdThatHasBeenUpdated, $cashReceipt->billOperationalFundReceipt);

            \DB::commit();
            return redirect()
                ->route('cash-receipt.edit', [$cashReceipt->id])
                ->with('message', 'Cash receipt, successfully update!');
        } catch (Exception $e) {
            \DB::rollback();
            throw new Exception("Error Processing Request", 1);
        }
        
    }

    private function reverseBill($billIdThatHasBeenUpdated, $cashReceiptsBillingOperationalFundReceipt)
    {
        foreach ($cashReceiptsBillingOperationalFundReceipt as $key => $row) {
            $reverse = true;
            foreach ($billIdThatHasBeenUpdated as $key => $bill) {
                if ($row->billing_id && $row->billing_id == $bill) {
                    $reverse = false;
                }
            }

            if ($reverse && $row->billing_id) {
                $billing = $row->billing;
                $tmpBillBalanceBeforePayment = $billing->balance + $row->amount_paid;

                $billing->balance = $tmpBillBalanceBeforePayment;
                $billing->paid = ($tmpBillBalanceBeforePayment == $billing->total_amount) ? 0: 1;
                $billing->save();

                \DB::table('bill_operational_fund_receipts')
                    ->where('cash_receipt_id', $row->cash_receipt_id)
                    ->where('billing_id', $row->billing_id)
                    ->delete();
            }

        }

        // foreach ($billIdThatHasBeenUpdated as $key => $bill) {
        //     $needToReverse = true;
        //     $operationalFundBill = null;
        //     foreach ($cashReceiptsBillingOperationalFundReceipt as $key => $row) {
        //         if ($row->billing_id && $row->billing_id == $bill) {
        //             $operationalFundBill = $row;
        //             $needToReverse = false;
        //         }
        //     }

        //     if ($needToReverse) {
        //         $billing = $operationalFundBill->billing;
        //         // dd($billing, $billIdThatHasBeenUpdated);
        //         $tmpBillBalanceBeforePayment = $billing->balance + $operationalFundBill->amount_paid;
        //         // dd($tmpBillsBalanceBeforePayment);

        //         $billing->balance = $tmpBillBalanceBeforePayment;
        //         $billing->paid = ($tmpBillBalanceBeforePayment == $billing->total_amount) ? 0: 1;
        //         $billing->save();

        //         \DB::table('bill_operational_fund_receipts')
        //             ->where('cash_receipt_id', $operationalFundBill->cash_receipt_id)
        //             ->where('billing_id', $operationalFundBill->billing_id)
        //             ->delete();
        //     }
        // }
    }

    private function reverseOperationalFund($operationalFundIdThatHasBeenUpdated, $cashReceiptsBillingOperationalFundReceipt)
    {
        foreach ($cashReceiptsBillingOperationalFundReceipt as $key => $row) {
            $needToUpdate = true;
            foreach ($operationalFundIdThatHasBeenUpdated as $key => $op) {
                if ($row->operational_fund_id && $row->operational_fund_id == $op) {
                    $needToUpdate = false;
                }
            }

            if ($needToUpdate && $row->operational_fund_id) {
                $operationalFund = $row->operationalFund;
                $tmpTotalAmountPaidBeforePayment = $operationalFund->total_amount_paid - $row->amount_paid;
                $tmpBalanceBeforePayment = $operationalFund->balance + $row->amount_paid;


                $operationalFund->total_amount_paid = $tmpTotalAmountPaidBeforePayment;
                $operationalFund->balance = $tmpBalanceBeforePayment;
                $operationalFund->save();

                \DB::table('bill_operational_fund_receipts')
                    ->where('cash_receipt_id', $row->cash_receipt_id)
                    ->where('operational_fund_id', $row->operational_fund_id)
                    ->delete();
            }

        }
        // foreach ($operationalFundIdThatHasBeenUpdated as $key => $op) {
        //     $needToUpdate = true;
        //     $operationalFundBill = null;
        //     foreach ($cashReceiptsBillingOperationalFundReceipt as $key => $row) {
        //         $operationalFundBill = $row;
        //         if ($row->operational_fund_id && $row->operational_fund_id == $op) {
        //             $notIncludedInPayment = false;
        //         }
        //     }

        //     if ($needToUpdate) {
        //         $operationalFund = $operationalFundBill->operationalFund;
        //         $tmpTotalAmountPaidBeforePayment = $operationalFund->total_amount_paid - $operationalFundBill->amount_paid;
        //         $tmpBalanceBeforePayment = $operationalFund->balance + $operationalFundBill->amount_paid;


        //         $operationalFund->total_amount_paid = $tmpTotalAmountPaidBeforePayment;
        //         $operationalFund->balance = $tmpBalanceBeforePayment;
        //         $operationalFund->save();

        //         \DB::table('bill_operational_fund_receipts')
        //             ->where('cash_receipt_id', $operationalFundBill->cash_receipt_id)
        //             ->where('operational_fund_id', $operationalFundBill->operational_fund_id)
        //             ->delete();
        //     }
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CashReceipt  $cashReceipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(CashReceipt $cashReceipt)
    {
        //
    }


    public function getPayment(Request $request)
    {
        $request = $request->except(['_token']);
        $data = [
            'info' => null,
            'fees' => null
        ];

        switch (strtolower($request['fee_category'])) {
            case 'walk-in':
                $walkInChargeSlip = WalkInChargeSlip::select(
                        [
                            'walk_in_charge_slips.*',
                            DB::raw('DATE_FORMAT(walk_in_charge_slips.transaction_date, "%m/%d/%Y") as t_date'),
                            DB::raw('FORMAT(walk_in_charge_slips.total_charges, 2) as format_total_charges')
                        ]
                    )
                    ->join('transactions', 'transactions.id', '=', 'walk_in_charge_slips.transaction_id')
                    ->where('walk_in_charge_slips.client_id', $request['client_id'])
                    ->where('transactions.status', '=', 'Ongoing')
                    ->first();

                if ($walkInChargeSlip) {
                    $data['info'] = $walkInChargeSlip;
                    $data['fees'] = TransactionFeeDetail::with(['fee'])
                        ->where('transaction_fee_details.walk_in_charge_slip_id', $walkInChargeSlip->id)
                        ->join('transactions', function ($q) {
                            $q->on('transactions.id', '=', 'transaction_fee_details.transaction_id')
                                ->on('transactions.status', '=', DB::raw("'Ongoing'"));
                        })
                        ->get();
                }
                break;
            
            case 'trust-fund':
                // $trustFund = Trus
                break;
            
            case 'special':
                # code...
                break;
            
            case 'trust':
                # code...
                break;
            
            case 'advances':
                # code...
                break;
            
            case 'Operational':
                # code...
                break;
            
            default:
                throw new \Exception("Invalid fee_category or client_id", 1);
                break;
        }

        return response()->json($data);
    }


    public function getCashReceipts()
    {
        $cashReceipts = $this->cashReceiptRepository->getAll();

        $data = DataTables::of($cashReceipts)
            ->addColumn('payment_date', function ($cashReceipt) {
                return $cashReceipt->payment_date->format('m/d/Y');
            })
            ->addColumn('amount_due', function ($cashReceipt) {
                return number_format($cashReceipt->amount_due, 2);
            })
            ->addColumn('amount_paid', function ($cashReceipt) {
                return number_format($cashReceipt->amount_paid, 2);
            })
            ->addColumn('balance', function ($cashReceipt) {
                return number_format(($cashReceipt->amount_due - $cashReceipt->amount_paid), 2);
            })
            ->addColumn('client_full_name', function ($cashReceipt) {
                $info = $cashReceipt->client->profile->full_name;
                return $info;
            })
            ->addColumn('action', function ($cashReceipt) {
                $menu = [];
                $menu[] = '<a href="'. route('cash-receipt.edit',array('client'=>$cashReceipt->id)) .'" class="btn-white btn btn-xs"><i class="fa fa-pencil text-success"></i> edit</a>';
                return '<div class="btn-group text-right">'.implode($menu).'</div>';
            })
            ->make(true);

        return $data;
    }
}
