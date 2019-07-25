<?php

namespace Repositories;

use App\Client;

class ClientRepository
{
    public function getClientWithActiveBill()
    {
        $clients = Client::select([
                'clients.id',
                \DB::raw("SUM(billings.total) as tmp")
            ])
            ->with(['profile'])
            ->join('billings', 'billings.client_id', '=', 'clients.id')
            ->whereRaw("
                    billings.paid = 0
                    OR
                    (billings.paid = 1 and billings.balance > 0)
                ")
            ->leftJoin('profiles', 'profiles.client_id', 'clients.id')
            ->groupBy('clients.id')
            // ->orderBy('profiles.lastname')
            ->get();

        return $clients;
    }

    public function getClientWithActiveWalkIn()
    {
        $clients = Client::select([
                'clients.id'
            ])
            ->with(['profile'])
            ->join('walk_in_charge_slips', 'walk_in_charge_slips.client_id', '=', 'clients.id')
            ->leftJoin('cash_receipts', 'cash_receipts.client_id', '=', 'clients.id')
            ->leftJoin('profiles', 'profiles.client_id', 'clients.id')
            ->whereNull('cash_receipts.id')
            ->orderBy('profiles.lastname')
            ->get();
            
        return $clients;
    }

    public function getClients($search = '')
    {
        $clients = Client::select(['clients.*'])->with(['profile']);

        return $clients->get();
    }

    public function getById($id)
    {
        return Client::select(['clients.*'])
            ->with(['profile'])->where('id', $id)->first();
    }
}
