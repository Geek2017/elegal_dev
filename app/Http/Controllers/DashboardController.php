<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Client;
use App\CaseManagement;
use App\Chargeable;

class DashboardController extends Controller
{
    public function clients()
    {
        $clients = Client::get();

        return sizeof($clients);
    }

    public function cases()
    {
        $cases = CaseManagement::get();

        return sizeof($cases);
    }

    public function chargeableExpenses()
    {
        $totalChargeableExpense = Chargeable::select([
            DB::raw("COALESCE(SUM(total), 0) as total_chargeable_expenses")
        ])->first();

        return number_format($totalChargeableExpense->total_chargeable_expenses);
    }
}
