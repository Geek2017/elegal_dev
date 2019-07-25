<?php 

namespace Reports;

use Services\PDF_MC_Table;
use Services\FPDF_COLOR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ars;

class ParalegalAssignmentSheetReportController extends Controller
{
    private $baseX = 10;

    private $cellHeight = 8;

    private $fontSize = 10;

    private $color;

    public function __construct(FPDF_COLOR $color)
    {
        $this->color = $color;
    }

    public function index(Request $request)
    {
        $inputs = $request->except(['_token']);

        $maxStartPage = $inputs['fas_start'];

        if ($inputs['fas_pages'] > 999) {
            $maxStartPage += abs($inputs['fas_pages'] / 999);
        }
        
        $title = 'Paralegal Assignment Sheet';

        $pdf = new PDF_MC_Table('L');
        $pdf->SetTitle($title);
        
        for ($i=$inputs['fas_start']; $i <= $maxStartPage; $i++) {
            if ($inputs['fas_pages'] > 999) {
                $inputs['fas_pages'] -= 999;
                for ($j=1; $j <= 999; $j++) {
                    $pdf = $this->formattingPdf($pdf, $inputs, $i, $j);
                }
            } else {
                for ($j=1; $j <= $inputs['fas_pages']; $j++) {
                    $pdf = $this->formattingPdf($pdf, $inputs, $i, $j);
                }
            }
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

    private function formattingPdf($pdf, $inputs, $start, $page)
    {
        $pdf->AddPage();

        $rgb = $this->color->darkslateblue;
        $pdf->SetTextColor($rgb[0], $rgb[1], $rgb[2]);

        $pdf->SetY(5);

        // Title
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(25, $this->cellHeight, 'PARALEGAL ASSIGNMENT SHEET');

        // Sub-title
        $pdf->Ln($this->cellHeight - 4.5);
        $pdf->Cell(25, $this->cellHeight, 'CASE DATA');

        // Sub-title 1
        $pdf->Ln($this->cellHeight - 4.5);
        $pdf->Cell(25, $this->cellHeight, "Client's Record");

        // table 1
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Ln($this->cellHeight - 2);
        $pdf->Cell(130, $this->cellHeight + 2, "", 1);
        $pdf->Ln();
        $pdf->Cell(65, $this->cellHeight + 2, "", 'LRB');
        $pdf->Cell(65, $this->cellHeight + 2, "", 'LRB');

        // box-text
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Ln($this->cellHeight * -1.3);
        $pdf->Cell(43.33, $this->cellHeight, 'Client:');
        $pdf->Ln($this->cellHeight + .5);
        $pdf->Cell(65, $this->cellHeight, 'Case/Project:');
        $pdf->Cell(65, $this->cellHeight, 'Docket/Project No.:');

        // Instructions
        $pdf->Ln($this->cellHeight + 3);
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(65, $this->cellHeight, "INSTRUCTIONS:");
        
        // Work Assignment:
        $pdf->Ln($this->cellHeight - 2);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 1);
        $pdf->Cell(65, $this->cellHeight, "Work Assignment:");

        // Check Lists
        $pdf->Ln($this->cellHeight - 3);
        $pdf->Cell(5, $this->cellHeight - 3, '', 'B', 0);
        $pdf->Cell(65, $this->cellHeight, 'Service/Filling of Pleading');

        $pdf->Cell(5, $this->cellHeight - 3, '', 'B', 0);
        $pdf->Cell(55, $this->cellHeight, 'Investigation/Research');

        $pdf->Ln($this->cellHeight - 3);
        $pdf->Cell(5, $this->cellHeight - 3, '', 'B', 0);
        $pdf->Cell(65, $this->cellHeight, 'Mailing');

        $pdf->Cell(5, $this->cellHeight - 3, '', 'B', 0);
        $pdf->Cell(55, $this->cellHeight, 'Inquiry/Follow Up');

        $pdf->Ln($this->cellHeight - 3);
        $pdf->Cell(5, $this->cellHeight - 3, '', 'B', 0);
        $pdf->Cell(65, $this->cellHeight, 'Delivery');

        $pdf->Cell(5, $this->cellHeight - 3, '', 'B', 0);
        $pdf->Cell(55, $this->cellHeight, 'Payment');

        $pdf->Ln($this->cellHeight - 3);
        $pdf->Cell(5, $this->cellHeight - 3, '', 'B', 0);
        $pdf->Cell(65, $this->cellHeight, 'Collection');

        $pdf->Cell(5, $this->cellHeight - 3, '', 'B', 0);
        $pdf->Cell(55, $this->cellHeight, 'Others');

        // Details of Assignment
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Cell(65, $this->cellHeight, "DETAILS OF ASSIGNMENT:");
        $pdf->Ln();

        for ($i=1; $i < 7; $i++) { 
            $pdf->Cell(5, $this->cellHeight + 2, "{$i}.", 1);
            $pdf->Cell(127, $this->cellHeight + 2, '', 1, 1);
        }

        // Details of Assignment
        $pdf->Ln(1);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 1);
        $pdf->Cell(65, $this->cellHeight, "DESTINATION:");
        $pdf->Ln($this->cellHeight - 3);

        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Cell(20, $this->cellHeight, 'Person(s) :');
        $pdf->Cell(112, $this->cellHeight - 3, '', 'B', 1, 1);
        $pdf->Cell(20, $this->cellHeight, 'Office       :');
        $pdf->Cell(112, $this->cellHeight - 3, '', 'B', 1, 1);
        $pdf->Cell(20, $this->cellHeight, 'Address  :');
        $pdf->Cell(112, $this->cellHeight - 3, '', 'B', 1, 1);

        $pdf->Ln();
        $sHeight = $this->cellHeight + 2;
        $pdf->Cell(43.33, $sHeight, '', 1);
        $pdf->Cell(43.33, $sHeight, '', 1);
        $pdf->Cell(43.33, $sHeight + $sHeight, '', 1);
        $pdf->Ln($sHeight);
        $pdf->Cell(43.33, $sHeight, '', 'LRB');
        $pdf->Cell(43.33, $sHeight, '', 'LRB');

        // box-text
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Ln($sHeight * -1.2);
        $pdf->Cell(43.33, $this->cellHeight, 'Field Clerk:');
        $pdf->Cell(43.33, $this->cellHeight, 'Date Assigned:');
        $pdf->Cell(43.33, $this->cellHeight, 'FAS#:');
        $pdf->Ln($sHeight);
        $pdf->Cell(43.33, $this->cellHeight, 'Assigning Counsel:');
        $pdf->Cell(43.33, $this->cellHeight, 'Date/Time Deadline:');

        $pdf->Ln($this->cellHeight - 2);
        $pdf->SetFont('Arial', 'B', $this->fontSize + 3);
        $pdf->Cell(43.33, $this->cellHeight - 2, $inputs['assigning_counsel'], 0, 0, 'R');
        $pdf->Cell(43.33, $this->cellHeight - 2, '', 0, 0, 'R');
        $pdf->Ln(-4);
        $pdf->SetFont('Arial', 'B', $this->fontSize + 5);
        $txt = $start . '-' . $inputs['fas_code'] . '-' . str_pad($page, 2, '0', STR_PAD_LEFT);
        $pdf->Cell(43.33, $this->cellHeight + 2, '', 0, 0, 'C');
        $pdf->Cell(43.33, $this->cellHeight + 2, '', 0, 0, 'C');
        $pdf->Cell(43.33, $this->cellHeight + 2, $txt, 0, 0, 'C');

        // Set X and Y
        $startingX = 150;
        $pdf->SetXY($startingX, 5);

        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(43.33, $this->cellHeight, 'ACCOMPLISHMENT REPORT', 0, 1);
        $pdf->setX($startingX);
        $pdf->Cell(70, $this->cellHeight + 3, '', 1);
        $pdf->Cell(70, $this->cellHeight + 3, '', 1);

        // box-text
        $pdf->SetFont('Arial', 'B', $this->fontSize - 3);
        $pdf->Ln($this->cellHeight - 7.5);
        $pdf->setX($startingX);
        $pdf->Cell(70, $this->cellHeight, 'Field Clerk:');
        $pdf->Cell(70, $this->cellHeight, 'Date & Time Filed');
        
        // Result
        $pdf->Ln($this->cellHeight + 1 );
        $pdf->setX($startingX);

        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Cell(70, $this->cellHeight, 'Result:');

        $pdf->Ln($this->cellHeight * .5);
        $pdf->setX($startingX + 5);
        $pdf->Cell(5, $this->cellHeight - 3, '', 'B', 0);
        $pdf->Cell(65, $this->cellHeight, 'Fully Accomplished');

        $pdf->Cell(5, $this->cellHeight - 3, '', 'B', 0);
        $pdf->Cell(55, $this->cellHeight, 'Not Accomplished');

        $pdf->Ln($this->cellHeight * .5);
        $pdf->setX($startingX + 5);
        $pdf->Cell(5, $this->cellHeight - 3, '', 'B', 0);
        $pdf->Cell(65, $this->cellHeight, 'Partially Accomplished');

        $pdf->Cell(5, $this->cellHeight - 3, '', 'B', 0);
        $pdf->Cell(55, $this->cellHeight, 'Others:_____________________');


        // Details Travel
        $pdf->Ln($this->cellHeight - 2);
        $pdf->setX($startingX);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Cell(55, $this->cellHeight, 'Details of Travel:');

        // Details Travel Table
        $pdf->Ln($this->cellHeight -1);
        $pdf->setX($startingX);
        $tableHeight = $this->cellHeight - 3;
        $pdf->Cell(28, $tableHeight, '', 'LRT', 0, 'C');
        $pdf->Cell(28, $tableHeight, 'DEPARTURE', 'LRT', 0, 'C');
        $pdf->Cell(28, $tableHeight, 'DESTINATION', 'LRT', 0, 'C');
        $pdf->Cell(28, $tableHeight, 'RETURN', 'LRT', 0, 'C');
        $pdf->Cell(28, $tableHeight, 'DURATION', 'LRT', 0, 'C');

        $pdf->Ln($this->cellHeight - 3);
        $pdf->setX($startingX);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Cell(28, $tableHeight, 'Location', 'LRT', 0, 'L');
        $pdf->Cell(28, $tableHeight, '', 'LRT', 0, 'L');
        $pdf->Cell(28, $tableHeight, '', 'LRT', 0, 'L');
        $pdf->Cell(28, $tableHeight, '', 'LRT', 0, 'L');
        $pdf->Cell(28, $tableHeight, '', 'LRT', 0, 'L');

        $pdf->Ln($this->cellHeight - 3);
        $pdf->setX($startingX);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Cell(28, $tableHeight, 'Date/Time', 1, 0, 'L');
        $pdf->Cell(28, $tableHeight, '', 1, 0, 'L');
        $pdf->Cell(28, $tableHeight, '', 1, 0, 'L');
        $pdf->Cell(28, $tableHeight, '', 1, 0, 'L');
        $pdf->Cell(28, $tableHeight, '', 1, 0, 'L');


        // Details Report
        $pdf->Ln($this->cellHeight - 2);
        $pdf->setX($startingX);

        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(37, $this->cellHeight, 'DETAILS OF REPORT');
        $pdf->SetFont('Arial', 'B', $this->fontSize - 3);
        $pdf->Cell(55, $this->cellHeight, '(Use backside if necessary):');

        // Details Report table
        $pdf->Ln($this->cellHeight);
        $pdf->SetFont('Arial', 'B', $this->fontSize);

        for ($i=1; $i < 4; $i++) {
            $pdf->setX($startingX);
            $pdf->Cell(5, $this->cellHeight, "{$i}.");
            $pdf->Cell(135, $this->cellHeight - 2, '', 'B', 1);
        }


        // Chargable Expense
        $pdf->Ln(0);
        $pdf->setX($startingX);

        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(37, $this->cellHeight, 'CHARGABLE EXPENSES:');

        // Chargable Expense Table
        $pdf->Ln($this->cellHeight - 2.5);
        $pdf->setX($startingX);
        $tableHeight = $this->cellHeight - 3;
        $pdf->Cell(46.67, $tableHeight, 'EXTERNAL EXPENSES', 'LRT', 0, 'C');
        $pdf->Cell(46.67, $tableHeight, 'DETAILS', 'LRT', 0, 'C');
        $pdf->Cell(46.67, $tableHeight, 'AMOUNT', 'LRT', 0, 'C');

        $pdf->SetFont('Arial', 'B', $this->fontSize - 3);
        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $tableHeight = $this->cellHeight - 3;
        $pdf->Cell(46.67, $tableHeight, 'Postage', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln(0);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, '');
        $pdf->Cell(46.67, $tableHeight, 'RR#');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, 'Courier Exchange', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, 'Transportation', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, 'Meals', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, 'Legal Fees', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, 'Bank Charges', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $tableHeight = $this->cellHeight - 3;
        $pdf->Cell(46.67, $tableHeight, 'Others', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        // Next Table
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Ln($this->cellHeight - 2.5);
        $pdf->setX($startingX);
        $tableHeight = $this->cellHeight - 3;
        $pdf->Cell(46.67, $tableHeight, 'INTERNAL EXPENSES', 'LRT', 0, 'C');
        $pdf->Cell(46.67, $tableHeight, 'DETAILS', 'LRT', 0, 'C');
        $pdf->Cell(46.67, $tableHeight, 'AMOUNT', 'LRT', 0, 'C');

        $pdf->SetFont('Arial', 'B', $this->fontSize - 3);
        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $tableHeight = $this->cellHeight - 3;
        $pdf->Cell(46.67, $tableHeight, 'Copying Charge: Single', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        
        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, '                              Double', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, 'Supplies:', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, '   Letter envelope', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, '   Brown envelope', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, '   Office stationary', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, '   Expanding Envelope', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, '   Spiral binder', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, '   Folder', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(46.67, $tableHeight, 'Others:', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');
        $pdf->Cell(46.67, $tableHeight, '', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(70, $tableHeight, 'ADVANCES: Chargable to Client:', 'LRT');
        $pdf->Cell(70, $tableHeight, 'Charge Against Deposit:', 'LRT');

        $pdf->Ln($this->cellHeight - 3.5);
        $pdf->setX($startingX);
        $pdf->Cell(70, $tableHeight, 'TOTAL', 1);
        $pdf->Cell(70, $tableHeight, 'TOTAL', 1);


        return $pdf;
    }
}
