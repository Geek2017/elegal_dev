<?php

namespace Repositories;

use App\CashReceipt;

class CashReceiptRepository
{
    private function mainQuery()
    {
        return CashReceipt::with([
                'client.profile', 
                'billings'
            ]);
    }

    public function getCashReceiptsForReport($request)
    {
        $cashReceipts = $this->mainQuery();

        if ($request['type'] == 'dateRange') {
            $cashReceipts->whereBetween('cash_receipts.payment_date', [$request['start_date'], $request['end_date']]);
        } else {
            $cashReceipts->where('cash_receipts.payment_date', '<=', $request['as_of_date']);
        }

        if (isset($request['client_id']) && $request['client_id']) {
            $cashReceipts->where('client_id', $request['client_id']);
        }

        return $cashReceipts->get();
    }

    public function getAll()
    {
        return $this->mainQuery()
            ->with(['operationalFunds'])
            ->orderBy('payment_date', 'DESC')
            ->get();
    }

    public function getById($id)
    {
        return $this->mainQuery()
            ->with(['operationalFunds', 
                'billOperationalFundReceipt.billing' => function ($query) {
                    return $query->select([
                        'id',
                        'client_id',
                        'invoice_number',
                        'bill_number',
                        'bill_date',
                        'special',
                        'general',
                        'excess',
                        'bill_amount',
                        'percentage_tax',
                        'tax_amount',
                        'total',
                        'balance',
                        'paid'
                    ]);
                }, 
                'billOperationalFundReceipt.operationalFund.billing'
            ])
            ->findOrFail($id);
    }

    public function getBillOperationalFundReceipt($id)
    {
        return CashReceipt::with([ 
                'billOperationalFundReceipt' => function($query) {
                    return $query->orderBy('created_at');
                },
                'billOperationalFundReceipt.billing' => function ($query) {
                    return $query->select([
                        'id',
                        'client_id',
                        'invoice_number',
                        'bill_number',
                        'bill_date',
                        'special',
                        'general',
                        'excess',
                        'bill_amount',
                        'percentage_tax',
                        'tax_amount',
                        'total',
                        'balance',
                        'paid'
                    ]);
                }, 
                'billOperationalFundReceipt.billing.operationalFund',
                'billOperationalFundReceipt.operationalFund', 
            ])
            ->findOrFail($id);
    }
}
