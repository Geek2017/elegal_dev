<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use Repositories\CounselRepository;
use Repositories\ClientRepository;

class ReportController extends Controller
{
	private $baseX = 10;

    private $cellHeight = 8;

    private $fontSize = 12;

    private $counselRepository;
    private $clientRepository;

    public function __construct(CounselRepository $counselRepository, ClientRepository $clientRepository)
    {
        $this->counselRepository = $counselRepository;
        $this->clientRepository = $clientRepository;
    }

    public function cashReceiptView(Request $request)
    {
        $date = new Carbon();

        $now = $date->format('m/d/Y');
        $clients = $this->clientRepository->getClients();
    	return view('user.reports.cash_receipts', compact('now', 'clients'));
    }

    public function counselServiceReportView(Request $request)
    {
        $date = new Carbon();

        $now = $date->format('m/d/Y');
        $counsels = $this->counselRepository->getCounsels();
    	return view('user.reports.counsel_service_report', compact('now', 'counsels'));
    }
}
