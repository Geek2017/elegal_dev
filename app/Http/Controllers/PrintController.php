<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use PDF;

class PrintController extends Controller
{
    public function printBillingLayout(Request $request)
    {
        return view('user.print-layout.billing.index');
    }

    public function printBilling(Request $request)
    {
        return view('user.print-layout.billing.print');
    }

    public function pdfBilling()
    {
        $html = view('user.print-layout.billing.print')->render();
        return PDF::load($html)->download();
    }

}
