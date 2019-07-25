<?php

namespace App\Http\Controllers;

use Repositories\ClientRepository;

use App\Client;
use Illuminate\Http\Request;
use App\TrustFund;
use App\Chargeable;
use DB;

class TrustFundController extends Controller
{
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function index()
    {
        $clients = $this->clientRepository->getClients();
        
        return view('user.reports.trus-fund-ledger', compact('clients'));
    }

    public function show(Request $request)
    {
        $inputs = $request->except('_token');

        if (!isset($inputs['client_id'])) {
            throw new Exception('client_id is required', 1);
        }

        $turstFund = TrustFund::select([
                DB::raw("FORMAT(COALESCE(SUM(deposit),0), 2) as deposit"),
            ])->where('client_id', $inputs['client_id'])
            ->first();

        $turstFundBalance = TrustFund::select([
                DB::raw("FORMAT(balance, 2) as balance"),
            ])->where('client_id', $inputs['client_id'])
            ->orderBy('created_at', 'DESC')
            ->first();


        return response()->json([
            'total_deposit'  => $turstFund->deposit,
            'latest_balance' => $turstFundBalance->balance
        ]);        
    }

    public function deposits(TrustFund $trustFund)
    {
        $clientIds = $trustFund->pluck('client_id')->unique()->toArray();
        $ids = [];
        foreach ($clientIds as $id){
            $trustFund = $trustFund->where('client_id', $id)->orderBy('id','DESC')->first();
            if($trustFund->balance > 0){
                array_push($ids,$trustFund->id);
            }
        }
        $datas = $trustFund->whereIn('id', $ids)->with('client')->get();
        $total = $trustFund->whereIn('id', $ids)->with('client')->sum('balance');
        return view('user.trust-fund.list', compact('datas', 'total'));
    }
}
