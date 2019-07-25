<?php

namespace Reports;

use Services\PDF_MC_Table;
use Services\FPDF_COLOR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client;
use App\TrustFund;

use DB;
use Carbon\Carbon;

class TrustFunController extends Controller
{
    private $baseX = 10;

    private $cellHeight = 8;

    private $fontSize = 12;

    private $color;

    public function __construct(FPDF_COLOR $color)
    {
        $this->color = $color;
    }


    public function index(Request $request)
    {
        $inputs = $request->except('_token');
        $now = new Carbon();

        $clientFrustFunds = TrustFund::with(['bill', 'client.profile'])
            ->where('client_id', $inputs['client_id'])
            ->orderBy('created_at')
            ->get();


        if (!$clientFrustFunds || !sizeof($clientFrustFunds)) {
            echo "Client has no trust fund!";
            return;
        }

        $firstData = $clientFrustFunds->first();

        $title = 'Clients Trust Fund Ledger - ' . $firstData->Client->profile->full_name;

        // Set a filename
        $filename = "AppName_Day__gen_".date("Y-m-d_H-i").".pdf";

        $pdf = new PDF_MC_Table();
        $pdf->AddPage();
        $pdf->SetTitle($title);

        // Title
        $pdf->SetFont('Arial', 'B', $this->fontSize + 6);
        $pdf->Cell(40, $this->cellHeight, 'Clients Trust Fund Ledger', 0, 2);
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(20, $this->cellHeight, 'Client: ' . $firstData->Client->profile->full_name, 0);
        $pdf->Ln(5);
        $pdf->Cell(20, $this->cellHeight, 'As of: ' . $now->format('M. d, Y'));

        // table for Trust fund
        $pdf->Ln(15);

        // fill color for the table header
        // $pdf->SetFillColor(176, 196, 222);
        $pdf->SetFillColor(180, 216, 231);
        $pdf->Cell(10, $this->cellHeight - 2, '#', 1, 0, 'L', true);
        $pdf->Cell(30, $this->cellHeight - 2, 'Date', 1, 0, 'L', true);
        $pdf->Cell(35, $this->cellHeight - 2, 'Deposit', 1, 0, 'R', true);
        $pdf->Cell(35, $this->cellHeight - 2, 'Deductions', 1, 0, 'R', true);
        $pdf->Cell(35, $this->cellHeight - 2, 'Billing (Invoice)', 1, 0, 'C', true);
        $pdf->Cell(35, $this->cellHeight - 2, 'Balance', 1, 0, 'R', true);

        $pdf->Ln();
        $pdf->showBorder = 1;
        $pdf->SetWidths([10, 30, 35, 35, 35, 35]);
        $pdf->SetAligns(['L', 'L', 'R', 'R', 'C', 'R']);
        foreach ($clientFrustFunds as $key => $tf) {
            $pdf->Row(
                [
                    $key + 1,
                    $tf->created_at->format('m/d/y'),
                    number_format($tf->deposit, 2),
                    ($tf->credit) ? number_format($tf->credit, 2): '',
                    ($tf->bill) ? $tf->bill->invoice_number: '',
                    number_format($tf->balance, 2)
                ]
            );
        }

        $pdfContent = $pdf->Output("{$title}.pdf", 'S');

        return response($pdfContent, 200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Length'      =>  strlen($pdfContent),
                'Content-Disposition' => 'inline; filename="' .$title . '.pdf"',
                'Cache-Control'       => 'private, max-age=0, must-revalidate',
                'Pragma'              => 'public'
            ]
        );
    }

    public function all(Request $request)
    {
        $inputs = $request->except('_token');
        $now = new Carbon();

        $clients = TrustFund::select(
                [
                    'trust_funds.client_id',
                    \DB::raw("SUM(trust_funds.deposit) as total_deposit"),
                    \DB::raw("SUM(trust_funds.credit) as total_credit"),
                    \DB::raw("(SUM(trust_funds.deposit) - SUM(trust_funds.credit)) as latest_balance"),
                    \DB::raw("CONCAT(profiles.lastname, ', ', profiles.firstname, ' ', COALESCE(profiles.middlename, '')) as full_name")
                ]
            )
            ->join('profiles', 'profiles.client_id', '=', 'trust_funds.client_id')
            ->groupBy('trust_funds.client_id')
            ->groupBy('full_name')
            ->get();


        if (!$clients) {
            echo "All Clients has zero balance in their trust funds!";
            return;
        }

        // $firstData = $clientFrustFunds->first();

        $title = 'Clients Trust Fund Ledger';

        // Set a filename
        $filename = "AppName_Day__gen_".date("Y-m-d_H-i").".pdf";

        $pdf = new PDF_MC_Table();
        $pdf->AddPage();
        $pdf->SetTitle($title);

        // Title
        $pdf->SetFont('Arial', 'B', $this->fontSize + 6);
        $pdf->Cell(40, $this->cellHeight, 'Members Trust Fund Ledger', 0, 2);
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(20, $this->cellHeight, 'As of: ' . $now->format('M. d, Y'));

        // table for Trust fund
        $pdf->Ln(15);

        // fill color for the table header
        $pdf->SetFillColor(176, 196, 222);
        $pdf->Cell(10, $this->cellHeight - 2, '#', 1, 0, 'L', true);
        $pdf->Cell(50, $this->cellHeight - 2, 'Client', 1, 0, 'L', true);
        $pdf->Cell(35, $this->cellHeight - 2, 'Deposit', 1, 0, 'R', true);
        $pdf->Cell(35, $this->cellHeight - 2, 'Deductions', 1, 0, 'R', true);
        $pdf->Cell(35, $this->cellHeight - 2, 'Balance', 1, 0, 'R', true);

        $pdf->Ln();
        $pdf->showBorder = 1;
        $pdf->SetWidths([10, 50, 35, 35, 35, 35]);
        $pdf->SetAligns(['L', 'L', 'R', 'R', 'R']);

        $totalDeposit = 0;
        $totalCredit = 0;
        $total = 0;
        foreach ($clients as $key => $c) {

            if (!$c->latest_balance) {
                continue;
            }

            $pdf->Row(
                [
                    $key + 1,
                    $c->full_name,
                    number_format($c->total_deposit, 2),
                    number_format($c->total_credit, 2),
                    number_format($c->latest_balance, 2)
                ]
            );

            $totalDeposit += $c->total_deposit;
            $totalCredit += $c->total_credit;
            $total += $c->latest_balance;
        }

        $pdf->Cell(10, $this->cellHeight - 2, '', 1, 0, 'L', true);
        $pdf->Cell(50, $this->cellHeight - 2, '', 1, 0, 'L', true);
        $pdf->Cell(35, $this->cellHeight - 2, number_format($totalDeposit, 2), 1, 0, 'R', true);
        $pdf->Cell(35, $this->cellHeight - 2, number_format($totalCredit, 2), 1, 0, 'R', true);
        $pdf->Cell(35, $this->cellHeight - 2, number_format($total, 2), 1, 0, 'R', true);



        $pdfContent = $pdf->Output("{$title}.pdf", 'S');

        return response($pdfContent, 200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Length'      =>  strlen($pdfContent),
                'Content-Disposition' => 'inline; filename="' .$title . '.pdf"',
                'Cache-Control'       => 'private, max-age=0, must-revalidate',
                'Pragma'              => 'public'
            ]
        );
    }
}
