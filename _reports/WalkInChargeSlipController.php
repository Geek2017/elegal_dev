<?php 

namespace Reports;

use Services\PDF_MC_Table;

use App\Http\Controllers\Controller;
use App\WalkInChargeSlip;
use App\FeeCategory;

class WalkInChargeSlipController extends Controller
{
    private $baseX = 10;

    private $cellHeight = 8;

    private $fontSize = 12;

    public function index($id)
    {
        $walkInChargeSlip = WalkInChargeSlip::with(['client.profile'])->findOrFail($id);
        $title = 'Walk-In Charge Slip - ' . $walkInChargeSlip->sr_no;

        // Set a filename
        $filename = "AppName_Day__gen_".date("Y-m-d_H-i").".pdf";

        $pdf = new PDF_MC_Table();
        $pdf->AddPage();
        $pdf->SetTitle($title);
        $this->addBackgroundLogo($pdf);
        
        $pdf->SetX(90);

        // Title
        $pdf->SetFont('Arial', 'B', $this->fontSize + 6);
        $pdf->Cell(40, $this->cellHeight + 5, 'Charge Slip');

        $pdf->Ln();

        // Client Name
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(30, $this->cellHeight, 'Client Name :');
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell(110, $this->cellHeight, $walkInChargeSlip->client->profile->full_name);
        // $pdf->Cell(30, $this->cellHeight, '');

        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(12, $this->cellHeight, 'CS #:');
        $pdf->SetFont('Arial', '', $this->fontSize);
        $pdf->Cell(25, $this->cellHeight, $walkInChargeSlip->charge_slip_no);

        $pdf->Ln($this->cellHeight);

        // Client Address
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(30, $this->cellHeight, 'Address : ', 0, 0, 'R');
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $line_height = $this->cellHeight - 3;
        $width = 110;
        $text = ($walkInChargeSlip->address);
        $rows = ceil(($pdf->GetStringWidth($text) / $width));
        $height = ($rows > 1) ? $this->cellHeight : $this->cellHeight;
        // $height = ( ( ($rows > 1) ? ) * $line_height) - 10;
        
        // $pdf->SetXY(40, 32);
        $pdf->Multicell($width, $height, $text, 0, 'L');

        $pdf->SetXY(150, 31);

        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(12, $this->cellHeight, 'Date:');
        $pdf->SetFont('Arial', '', $this->fontSize);
        $pdf->Cell(25, $this->cellHeight, $walkInChargeSlip->transaction_date->format('m/d/Y'));

        $pdf->SetY(40);

        // Reporter
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(30, $this->cellHeight, 'Reporter : ', 0, 0, 'R');
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell(90, $this->cellHeight, $walkInChargeSlip->reporter);

        $pdf->Ln();

        // Service Specs
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(33, $this->cellHeight, 'Service Specs: ');
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $text = ($walkInChargeSlip->service_specification);
        $rows = ceil(($pdf->GetStringWidth($text) / $width));
        $height = ($rows > 1) ? $this->cellHeight : $this->cellHeight - 1;
        $pdf->Multicell(120, $height, $text, 0, 'L');

        // $pdf->Cell(110, $this->cellHeight, $walkInChargeSlip->service_specification);


        // Client Address
        // $pdf->Ln();
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(33, $this->cellHeight, '    Detail : ', 0, 0, 'R');
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $line_height = $this->cellHeight - 3;
        $width = 100;
        $text = ($walkInChargeSlip->details);
        $rows = ceil(($pdf->GetStringWidth($text) / $width));
        $height = ($rows > 1) ? $this->cellHeight : $this->cellHeight - 1;
        $pdf->Multicell($width, $height, $text, 0, 'L');

        $pdf->Ln($this->cellHeight - 5);

        // Professional Fees
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(33, $this->cellHeight, "Prof'l Fee P: ", 0, 0, 'R');
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell(40, $this->cellHeight, number_format($walkInChargeSlip->professional_fees, 2));

        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(30, $this->cellHeight, "");
        $pdf->Cell(70, $this->cellHeight, "PAYMENT DETAILS", 'B', 0, 'C');


        $pdf->Ln($this->cellHeight);

        // Expenses
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(33, $this->cellHeight, "Expenses P: ", 0, 0, 'R');
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell(40, $this->cellHeight, number_format($walkInChargeSlip->total_expenses, 2));

        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(30, $this->cellHeight, "");
        $pdf->Cell(70, $this->cellHeight, "REF NUMBER:");

        $pdf->Ln($this->cellHeight);

        // TOTAL
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(33, $this->cellHeight, "TOTAL P: ", 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(40, $this->cellHeight, number_format($walkInChargeSlip->total_charges, 2));

        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(30, $this->cellHeight, "");
        $pdf->Cell(70, $this->cellHeight, "MODE PAYMENT:");


        $pdf->Ln();
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell(110, $this->cellHeight, 'DETAIL OF CHARGABLE EXPENSES:', 'B', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(90, $this->cellHeight, 'OR/SI Number', 'B');
        $pdf->Cell(20, $this->cellHeight, 'AMOUNT', 'B');
        $pdf->Ln();
        $pdf->showBorder = 0;
        $pdf->SetWidths([92, 40]);
        $pdf->SetFont('Arial', '', $this->fontSize);
        
        foreach ($walkInChargeSlip->transactionFees as $key => $tf) {
            if (FeeCategory::GENERAL == $tf->fee_cat_id) {
                $pdf->Row([$tf->fee->display_name, number_format($tf->amount, 2)]);
            }
        }

        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(90, $this->cellHeight, 'TOTAL CHARGABLE EXPENSES');
        $pdf->Cell(20, $this->cellHeight, number_format($walkInChargeSlip->total_expenses, 2), 'T', 0, 'C');


        $pdf->Ln($this->cellHeight + 5);

        $pdf->Cell(90, $this->cellHeight, 'Certified Correct');
        $pdf->Ln();
        $pdf->Cell(20, $this->cellHeight, '');
        $pdf->Cell(90, $this->cellHeight, '', 'B');


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

    // override the creation of watermark
    public function addBackgroundLogo($pdf)
    {
        $pdf->SetAlpha(0.15); // set alpha to semi-transparency
        $pdf->Image($pdf->getBase64Image(base_path('public/img/logo.png'), 'pdf.company.logo'), 65, 25, 90, 90, 'PNG');
        $pdf->SetAlpha(1); // set alpha to semi-transparency
    }
}
