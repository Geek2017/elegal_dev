<?php

namespace Repositories;

use App\Billing;

class BillingRepository
{
    protected function mainQuery()
    {
        return Billing::with([
                'transactionFeeDetails.cases',
                'transactionFeeDetails.fee',
                'serviceReports.chargeables.fee',
                'serviceReports.feeDetail.fee',
                'client', 
                'client.latestTrustFund', 
                'client.business.mobile', 
                'client.billingAddress.mobile', 
                'client.business.address', 
                'client.billingAddress.address',
                'operationalFund',
                'trustFund'
            ])
            ->orderBy('created_at', 'DESC')
            ->orderBy('bill_date', 'DESC');
    }

    public function getUnpaidBills($clientId)
    {
        return $this->mainQuery()
            ->whereRaw("(
                        paid = 0
                        or
                        (paid = 1 and balance > 0)
                    )
                ")
            ->where('client_id', $clientId)
            ->get();
    }

    public function getBill($id)
    {
        return $this->mainQuery()->where('id', $id)->first();
    }
}
