<?php

namespace Reports;



use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Repositories\CashReceiptRepository;
use Repositories\ClientRepository;
use Services\PDF_MC_Table;
use Services\FPDF_COLOR;

class CashReceiptPaymentsReportController extends Controller
{
	private $baseX = 10;

    private $cellHeight = 8;

    private $fontSize = 12;

    private $cashReceiptRepository;
    private $clientRepository;

    public function __construct(FPDF_COLOR $color, CashReceiptRepository $cashReceiptRepository, ClientRepository $clientRepository)
    {
        $this->color = $color;

        $this->cashReceiptRepository = $cashReceiptRepository;
        $this->clientRepository = $clientRepository;
    }

    public function index(Request $request)
    {
    	$title = 'Cash Receipt Payments';

        // Set a filename
        $filename = "AppName_Day__gen_".date("Y-m-d_H-i").".pdf";

        $pdf = new PDF_MC_Table();
        $pdf->AddPage();
        $pdf->SetTitle($title);

        $cashReceipts = $this->cashReceiptRepository->getCashReceiptsForReport($request);


        // Title
        $pdf->SetFont('Arial', 'B', $this->fontSize + 6);
        $pdf->Cell(40, $this->cellHeight + 5, 'Cash Receipt Payments');

        if (isset($request['client_id']) && $request['client_id']) {
            $client = $this->clientRepository->getById($request['client_id']);

            $pdf->Ln(9);
            $pdf->SetFont('Arial', 'I', $this->fontSize + 3);
            $pdf->Cell(40, $this->cellHeight, 'Client: ' . $client->profile->full_name);
            $pdf->Ln(6);
        } else {
            $pdf->Ln(10);
        }

        $pdf->SetFont('Arial', '', $this->fontSize);

        if ($request['type'] ==  'dateRange') {
            $sDate = new Carbon($request['start_date']);
            $eDate = new Carbon($request['end_date']);
            $pdf->Cell(20, $this->cellHeight, 'For the Period from ' . $sDate->format('m/d/Y') . ' to ' . $eDate->format('m/d/Y'));
        } else {
            $pdf->Cell(20, $this->cellHeight, 'As of: ' . $now->format('m d, Ys'));
        }


        // Table Title
        $pdf->Ln(20);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);

        $pdf->SetFillColor(180, 216, 231);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(10, $this->cellHeight - 2, '#', 1, 0, 'L', true);
        $pdf->Cell(25, $this->cellHeight - 2, 'O.R No.', 1, 0, 'L', true);
        $pdf->Cell(50, $this->cellHeight - 2, 'Client', 1, 0, 'L', true);
        $pdf->Cell(30, $this->cellHeight - 2, 'Date', 1, 0, 'L', true);
        $pdf->Cell(35, $this->cellHeight - 2, 'Amount Due', 1, 0, 'R', true);
        $pdf->Cell(35, $this->cellHeight - 2, 'Amount Paid', 1, 0, 'R', true);

        $pdf->Ln();

        $pdf->SetWidths([10, 25, 50, 30, 35, 35]); // #, OR, Client, Date, Amount Due, Amount Paid
        $pdf->SetAligns(['L', 'L', 'L', 'L', 'R', 'R']);

        $totalAmountDue = 0;
        $totalAmountPaid = 0;
        if (sizeof($cashReceipts)) {
            foreach ($cashReceipts as $index => $cr) {
                // die($cr);
                $pdf->Row([
                    $index+1,
                    $cr->cash_receipt_no,
                    $cr->client->profile->full_name,
                    $cr->payment_date->format('m/d/Y'),
                    number_format($cr->amount_due,2),
                    number_format($cr->amount_paid,2)
                ]);

                $totalAmountDue += $cr->amount_due;
                $totalAmountPaid += $cr->amount_paid;
            }
        } else {
            $pdf->Cell(185, $this->cellHeight - 2, 'No Recorded Payments', 0, 2, 'C');
        }

        // $pdf->SetTextColor(255,255,255);
        $pdf->Cell(115, $this->cellHeight - 2, 'Total', 1, 0, 'R', true);
        $pdf->Cell(35, $this->cellHeight - 2, number_format($totalAmountDue, 2), 1, 0, 'R', true);
        $pdf->Cell(35, $this->cellHeight - 2, number_format($totalAmountPaid, 2), 1, 0, 'R', true);

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
