<?php

namespace Reports;



use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Repositories\ServiceReportRepository;
use Repositories\CounselRepository;
use Services\PDF_MC_Table;
use Services\FPDF_COLOR;

class CounselServiceReportReportController extends Controller
{
	private $baseX = 10;

    private $cellHeight = 8;

    private $fontSize = 12;

    private $serviceReportRepository;
    private $counselRepository;

    public function __construct(FPDF_COLOR $color, ServiceReportRepository $serviceReportRepository, CounselRepository $counselRepository)
    {
        $this->color = $color;

        $this->serviceReportRepository = $serviceReportRepository;
        $this->counselRepository = $counselRepository;
    }

    public function index(Request $request)
    {

        if (!$request['counsel_id']) {
            throw new \Exception("Please Select a Counsel", 1);
        }

    	$title = 'Counsel Service Report';

        // Set a filename
        $filename = "AppName_Day__gen_".date("Y-m-d_H-i").".pdf";

        $pdf = new PDF_MC_Table('L', 'mm', 'a4');
        $pdf->AddPage();
        $pdf->SetTitle($title);

        $serviceReports = $this->serviceReportRepository->getcounselServiceReports($request);
        $counsel = $this->counselRepository->getCounselById($request['counsel_id']);


        // Title
        $pdf->SetFont('Arial', 'B', $this->fontSize + 2);
        $pdf->Cell(275, $this->cellHeight + 2, 'SERVICE SUMMARY REPORT PER COUNSEL', 0, 0, 'C');
        // $pdf->SetFont('Arial', '', $this->fontSize + 1);
        $pdf->Ln(8);
        $pdf->Cell(275, $this->cellHeight, 'Atty.' . $counsel->profile->full_name, 0, 0, 'C');
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', $this->fontSize);
        if ($request['type'] ==  'dateRange') {
            $sDate = new Carbon($request['start_date']);
            $eDate = new Carbon($request['end_date']);
            $pdf->Cell(275, $this->cellHeight, 'For the Period from ' . $sDate->format('m/d/Y') . ' to ' . $eDate->format('m/d/Y'), 0, 0, 'C');
        } else {
            $pdf->Cell(275, $this->cellHeight, 'As of: ' . $now->format('m d, Ys'), 0, 0, 'C');
        }


        // Table Title
        $pdf->Ln(20);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 3);
        // $pdf->SetFillColor(150, 65, 3);
        // $pdf->SetFillColor(176,196,222);
        // $pdf->SetTextColor(255,255,255);
        $pdf->SetFillColor(180, 216, 231);
        $pdf->Cell(198, $this->cellHeight - 2, 'SERVICE REPORT DESCRIPTION', 1, 0, 'C', true);
        $pdf->Cell(80, $this->cellHeight - 2, 'CHARGEABLES EXPENSES', 1, 0, 'C', true);
        $pdf->Ln();
        $pdf->Cell(18, $this->cellHeight - 2, 'Date', 1, 0, 'C', true);
        $pdf->Cell(30, $this->cellHeight - 2, 'SR Number', 1, 0, 'C', true);
        $pdf->Cell(35, $this->cellHeight - 2, 'SERVICE RENDERED', 1, 0, 'L', true);
        $pdf->Cell(10, $this->cellHeight - 2, 'MIN', 1, 0, 'C', true);
        $pdf->Cell(40, $this->cellHeight - 2, 'DOCKET NO/PROJECT', 1, 0, 'L', true);
        $pdf->Cell(40, $this->cellHeight - 2, 'CLIENT NAME', 1, 0, 'L', true);
        $pdf->Cell(25, $this->cellHeight - 2, 'PROF. FEES', 1, 0, 'R', true);
        $pdf->Cell(40, $this->cellHeight - 2, 'DESCRIPTION', 1, 0, 'L', true);
        $pdf->Cell(40, $this->cellHeight - 2, 'AMOUNT', 1, 0, 'R', true);
        // $pdf->SetTextColor(0,0,0);
        $pdf->Ln();


        $pdf->SetWidths([18, 30, 35, 10, 40, 40, 25, 40, 40]);
        $pdf->SetAligns(['C', 'C', 'L', 'C', 'L', 'L', 'R', 'L', 'R']);

        $totalProfFees = 0;
        $overallChargeable = 0;
        $totalMinutes = 0;
        foreach ($serviceReports as $index => $sr) {
            $pdf->showBorder = 0;
            $pdf->SetFont('Arial', '', $this->fontSize - 3);

            $caseTitle = "";
            $caseNumber = "";
            if ($sr->case) {
                $caseTitle = $sr->case->title;
                $caseNumber = ($sr->case->number) ? ' (' . $sr->case->number . ')' : '';
            }
            $pdf->Row([
                $sr->date->format('m/d/Y'),
                $sr->sr_number,
                $sr->fee_description . " \n" . $sr->description,
                $sr->minutes,
                $caseTitle . $caseNumber,
                $sr->client->profile->full_name,
                number_format($sr->total, 2),
                '',
                '',
            ]);

            $totalMinutes += $sr->minutes;
            $totalProfFees += $sr->total;

            $totalChargeables = 0;
            foreach ($sr->chargeables as $key => $c) {
                $pdf->Row([
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $c->fee->display_name,
                    number_format($c->total, 2),
                ]);

                $totalChargeables += $c->total;
            }

            $overallChargeable += $totalChargeables;

            // $pdf->SetTextColor(255, 255, 255);
            // $pdf->SetFont('Arial', 'B', $this->fontSize - 1);
            // $pdf->Cell(198, $this->cellHeight - 2, '', 'T', 0, 'C', true);
            // $pdf->Cell(40, $this->cellHeight - 2, 'Total', 'TR', 0, 'R', true);
            // $pdf->Cell(40, $this->cellHeight - 2, number_format($totalChargeables, 2), 'T', 0, 'R', true);
            // $pdf->SetTextColor(0, 0, 0);
            $pdf->Ln();
        }

        $pdf->Ln();

        $pdf->Cell(83, $this->cellHeight - 2, '', 'T', 0, 'C', true);
        $pdf->Cell(10, $this->cellHeight - 2, "TOTAL MIN.:    " . $totalMinutes, 'TR', 0, 'R', true);
        $pdf->Cell(40, $this->cellHeight - 2, '', 'T', 0, 'R', true);
        $pdf->Cell(40, $this->cellHeight - 2, '', 'T', 0, 'R', true);
        $pdf->Cell(25, $this->cellHeight - 2, "TOTAL PROF. FEE:    " . number_format($totalProfFees, 2), 'TR', 0, 'R', true);
        $pdf->Cell(40, $this->cellHeight - 2, '', 'T', 0, 'R', true);
        $pdf->Cell(40, $this->cellHeight - 2, "TOTAL CHARGEABLE.:    " . number_format($overallChargeable, 2), 'T', 0, 'R', true);


        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(80, $this->cellHeight - 2, 'PRODCUTION SHARE (PROF FEE)', 'B', 0, 'L');
        $pdf->Cell(40, $this->cellHeight - 2, number_format($totalProfFees, 2), 'B', 2, 'C');
        $pdf->Ln(1);
        $pdf->Cell(80, $this->cellHeight - 2, '', 'T', 0, 'L');
        $pdf->Cell(40, $this->cellHeight - 2, '', 'T', 0, 'L');

        $pdf->Ln(8);
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell(80, $this->cellHeight - 2, 'Certified Correct:', 0, 0, 'L');


        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', $this->fontSize + 2);
        $pdf->Cell(80, $this->cellHeight - 2, 'PETER LEO M. RALLA', 0, 2, 'L');
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell(80, $this->cellHeight - 2, 'Executive Partner', 0, 0, 'L');

        // $pdfContent = $pdf->Output('I', "{$title}.pdf");
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
