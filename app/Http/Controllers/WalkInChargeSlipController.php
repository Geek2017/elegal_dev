<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Auth;
use DB;

use App\Fee;
use App\FeeCategory;
use App\WalkInChargeSlip;
use App\Transaction;
use App\TransactionFeeDetail;

class WalkInChargeSlipController extends Controller
{
    private $rules = array(
        'client_id'             => 'required',
        'address'               => 'required',
        // 'charge_slip_no'        => 'required',
        'transaction_date'      => 'required|date',
        'service_specification' => 'required',
        'details'               => 'required',
        'total_expenses'        => 'required',
        'professional_fees'     => 'required',
        'total_charges'         => 'required',
        'reporter'              => 'required',
    );

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'transaction_date',
    ];

    public function index()
    {
        return view('user.walk-in-chargable-slip.index');
    }

    public function create()
    {
        $date = new Carbon();
        $now = $date->format('Y-m-d');

        return view('user.walk-in-chargable-slip.create', compact('now'));
    }

    public function edit($id)
    {
        $walkInChargeSlip = WalkInChargeSlip::with(['transactionFees.fee', 'client.profile'])
            ->findorFail($id);

        // die($walkInChargeSlip);

        $generalFeeId = FeeCategory::GENERAL;

        return view('user.walk-in-chargable-slip.edit', compact('walkInChargeSlip', 'generalFeeId'));
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

            DB::beginTransaction();

            $inputs = $request->except('_token');
            $user = Auth::user();

            // Create Transaction
            $transaction = Transaction::create(['user_id' => $user->id, 'client_id' => $inputs['client_id'], 'status' => 'Ongoing']);

            $chargeSlip = WalkInChargeSlip::select([DB::raw("COUNT(*) as number")])
                ->first();

            $date = new Carbon($inputs['transaction_date']);

            // Create Charge-slip
            $walkInChargeSlip = WalkInChargeSlip::create(
                array_merge(
                    $inputs, 
                    [
                        'charge_slip_no' => "{$date->format('mY')}-".str_pad($chargeSlip->number, 5, '0', STR_PAD_LEFT),
                        'transaction_id' => $transaction->id
                    ]
                )
            );

            // Add Transaction Fee Detatils
            $fees = [];

            foreach ($inputs['chargable_expense_id'] as $key => $feeId) {
                if ($inputs['chargable_expense_amount'][$key] > 0) {
                    $fees[] = [
                        'transaction_id'         => $transaction->id,
                        'walk_in_charge_slip_id' => $walkInChargeSlip->id,
                        // 'fee_cat_id'             => FeeCategory::GENERAL,
                        'fee_id'                 => $feeId,
                        'amount'                 => $inputs['chargable_expense_amount'][$key],
                        'client_id'              => $inputs['client_id']
                    ];
                }
            }
            
            $professionalFee = Fee::select(['id','category_id'])->whereRaw("name like '%p%r%o%f%e%s%s%i%o%n%a%l%f%e%e%'")->first();
            $fees[] = [
                'transaction_id'         => $transaction->id,
                'walk_in_charge_slip_id' => $walkInChargeSlip->id,
                // 'fee_cat_id'             => $professionalFee->category_id,
                'fee_id'                 => $professionalFee->id,
                'amount'                 => $inputs['professional_fees'],
                'client_id'              => $inputs['client_id']
            ];

            $fees = TransactionFeeDetail::insert($fees);

            DB::commit();
            
            return redirect()
                ->route('walk-in.charge-slip.edit', [$walkInChargeSlip->id])
                ->with('message', 'Walk-In Client Charge Slip, successfully save!');
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e, 1);
        }
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

            DB::beginTransaction();

            $inputs = $request->except('_token');
            $user = Auth::user();

            // Find Walk-In Charge Slip then update
            $walkInChargeSlip = WalkInChargeSlip::findorFail($id);
            $walkInChargeSlip->fill($inputs);
            $walkInChargeSlip->save();

            // delete some relations
            $walkInChargeSlip->transactionFees()->delete();

            // Add Transaction Fee Detatils
            $fees = [];

            foreach ($inputs['chargable_expense_id'] as $key => $feeId) {
                if ($inputs['chargable_expense_amount'][$key]) {
                    $fees[] = [
                        'transaction_id'         => $walkInChargeSlip->transaction_id,
                        'walk_in_charge_slip_id' => $walkInChargeSlip->id,
                        // 'fee_cat_id'             => FeeCategory::GENERAL,
                        'fee_id'                 => $feeId,
                        'amount'                 => $inputs['chargable_expense_amount'][$key],
                        'client_id'              => $inputs['client_id']
                    ];
                }
            }
            
            $professionalFee = Fee::select(['id','category_id'])->whereRaw("name like '%p%r%o%f%e%s%s%i%o%n%a%l%f%e%e%'")->first();
            $fees[] = [
                'transaction_id'         => $walkInChargeSlip->transaction_id,
                'walk_in_charge_slip_id' => $walkInChargeSlip->id,
                // 'fee_cat_id'             => $professionalFee->category_id,
                'fee_id'                 => $professionalFee->id,
                'amount'                 => $inputs['professional_fees'],
                'client_id'              => $inputs['client_id']
            ];

            $fees = TransactionFeeDetail::insert($fees);

            DB::commit();
            
            return redirect()
                ->route('walk-in.charge-slip.edit', [$walkInChargeSlip->id])
                ->with('message', 'Walk-In Client Charge Slip, successfully update!');
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e, 1);
        }
    }

    public function destroy($id)
    {
        $walkInChargeSlip = WalkInChargeSlip::findorFail($id);
        $walkInChargeSlip->deleted_at = Carbon::now();
        $walkInChargeSlip->save();

        return redirect()
                ->route('walk-in.charge-slip.index')
                ->with('message', 'Walk-In Charge Slip transaction was void!');
    }

    public function generalFeeList(Request $request)
    {
        $search = $request->get('q');

        $fees = Fee::select([
                'fees.*',
                'fees.display_name as text'
            ])
            ->where('category_id', FeeCategory::GENERAL)
            ->whereRaw("display_name  like '%{$search}%'")
            ->orWhereRaw("name  like '%{$search}%'")
            ->get();

        return response()->json(['results' => $fees]);
    }

    public function getList()
    {

        $model = WalkInChargeSlip::select([
                'walk_in_charge_slips.*',
                DB::raw('DATE_FORMAT(walk_in_charge_slips.transaction_date, "%m/%d/%Y") as formatted_transaction_date'),
            ])
            ->with(['client.profile'])
            ->join('clients', 'clients.id', '=', 'walk_in_charge_slips.client_id')
            ->join('profiles', 'profiles.client_id', '=', 'clients.id')
            ->whereNull('walk_in_charge_slips.deleted_at');

        $data = DataTables::eloquent($model)
            ->addColumn('action', function ($walkInChargeSlip) {
                $menu = [];
                $menu[] = '<a href="'. route('walk-in.charge-slip.edit',[$walkInChargeSlip->id]) .'" class="btn-white btn btn-xs"><i class="fa fa-eye text-success"></i> View</a>';
                $menu[] = '<a href="javascript:void(0);" class="btn-white btn btn-xs delete-walk-in-charge-slip" data-url="' . route("walk-in.charge-slip.destroy", [$walkInChargeSlip->id]).'" data-id="'. $walkInChargeSlip->id.'"><i class="fa fa-times text-danger"></i> Delete</a>';
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
