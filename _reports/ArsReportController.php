<?php 

namespace Reports;

use Services\PDF_MC_Table;

use App\Http\Controllers\Controller;
use App\Ars;

class ArsReportController extends Controller
{
    private $baseX = 10;

    private $cellHeight = 8;

    private $fontSize = 12;

    public function index($id)
    {
        $ars = Ars::with(['ads', 'oos', 'fads', 'client.profile'])->findOrFail($id);
        $title = 'Activity Report Sheet - ' . $ars->ars_no;

        // Set a filename
        $filename = "AppName_Day__gen_".date("Y-m-d_H-i").".pdf";

        $pdf = new PDF_MC_Table();
        $pdf->addMargin = true;
        $pdf->AddPage();
        $pdf->isNeedMargin = true;
        $pdf->SetTitle($title);
        $pdf->SetMargins(20,20);

        $pdf->SetY(25.4);
        
        // Title
        $pdf->SetFont('Arial', 'B', $this->fontSize + 5);
        $pdf->Cell(75, $this->cellHeight + 5, 'ACTIVITY REPORT SHEET');
        $pdf->Ln();

        // ars date
        $pdf->SetX(155);
        $pdf->SetFont('Arial', '', $this->fontSize);
        $pdf->Cell(13, $this->cellHeight, 'Date');
        $pdf->Cell(2, $this->cellHeight, ':', 0, 0, 'C');
        $pdf->Cell(25, $this->cellHeight, $ars->ars_date->format('m/d/Y'));

        // Ars No
        $pdf->Ln(6);
        $pdf->SetX(155);
        $pdf->Cell(13, $this->cellHeight, 'No:');
        $pdf->Cell(2, $this->cellHeight, ':', 0, 0, 'C');
        $pdf->SetFont('Arial', 'U', $this->fontSize);
        $pdf->Cell(8, $this->cellHeight, $ars->ars_no);

        $pdf->Ln();
        // $pdf->Ln();

        // // GR TITLE
        // $pdf->SetFont('Arial', 'B', $this->fontSize);
        // $pdf->Cell(20, $this->cellHeight, 'GR Title (');
        // $pdf->SetFont('Arial', 'B', $this->fontSize - 3);
        // $pdf->Cell(51, $this->cellHeight, 'For General Retainer case/project');
        // $pdf->SetFont('Arial', 'B', $this->fontSize);
        // $pdf->Cell(5, $this->cellHeight, '):');
        // $pdf->SetFont('Arial', '', $this->fontSize);
        // $pdf->Cell(100, $this->cellHeight, $ars->gr_title);

        // Case/Project Name
        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(40, $this->cellHeight, 'Case/Project Name', 0, 0, 'L');
        $pdf->Cell(3, $this->cellHeight, ':', 0, 0, 'C');
        $pdf->SetFont('Arial', '', $this->fontSize);
        $pdf->Cell(100, $this->cellHeight, $ars->case_project_name);
        
        // Case/Project Name
        $pdf->Ln(6);
       $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(20, $this->cellHeight, 'GR Title (');
        $pdf->SetFont('Arial', 'B', $this->fontSize - 3);
        $pdf->Cell(51, $this->cellHeight, 'For General Retainer case/project');
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(5, $this->cellHeight, '):');
        $pdf->SetFont('Arial', '', $this->fontSize);
        $pdf->Cell(100, $this->cellHeight, $ars->gr_title);
        
        // Docket No./Venu
        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(40, $this->cellHeight, 'Docket No./Venue', 0, 0, 'L');
        $pdf->Cell(3, $this->cellHeight, ':', 0, 0, 'C');
        $pdf->SetFont('Arial', '', $this->fontSize);
        $pdf->Cell(100, $this->cellHeight, $ars->docket_no_venue);
        

        // Client
        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(40, $this->cellHeight, 'Client', 0, 0, 'L');
        $pdf->Cell(3, $this->cellHeight, ':', 0, 0, 'C');
        $pdf->SetFont('Arial', '', $this->fontSize);
        $pdf->Cell(100, $this->cellHeight, $ars->client->profile->full_name);

        // Reporter
        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(40, $this->cellHeight, 'Reporter', 0, 0, 'L');
        $pdf->Cell(3, $this->cellHeight, ':', 0, 0, 'C');
        $pdf->SetFont('Arial', '', $this->fontSize);
        // $pdf->Cell(100, $this->cellHeight,  strtoupper($ars->reporter));
        $pdf->Cell(100, $this->cellHeight,  "PMR");


       


        // Description
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'BU', $this->fontSize);
        $pdf->Cell(43, $this->cellHeight, 'Activity Description', '', 2);

        $pdf->SetFont('Arial', '', ($this->fontSize - 4) );
        $pdf->Cell(150, $this->cellHeight, '(Describe in outline format the activity performed with sufficient details as to person, date, location, subject matter, and purpose)');

        // list of Description
        $pdf->Ln();
        $pdf->showBorder = 0;
        $pdf->SetWidths([10,10, 180]);
        $pdf->SetFont('Arial', '', $this->fontSize);
        foreach ($ars->ads as $key => $ad) {
            $pdf->Row(["", $key+1 . '.', $ad->description]);
        }

        $pdf->Ln();

        // Time Start
        $pdf->SetFont('Arial', 'B', ($this->fontSize - 2));
        $pdf->Cell(20, $this->cellHeight, 'Time Start:');
        $pdf->SetFont('Arial', '', ($this->fontSize - 2));
        $pdf->Cell(20, $this->cellHeight, $ars->time_start);

        // Time Finish
        $pdf->SetFont('Arial', 'B', ($this->fontSize - 2));
        $pdf->Cell(22, $this->cellHeight, 'Time Finish:');
        $pdf->SetFont('Arial', '', ($this->fontSize - 2));
        $pdf->Cell(20, $this->cellHeight, $ars->time_finnish);

        // Duration
        $pdf->SetFont('Arial', 'B', ($this->fontSize - 2));
        $pdf->Cell(17, $this->cellHeight, 'Duration:');
        $pdf->SetFont('Arial', '', ($this->fontSize - 2));
        $pdf->Cell(35, $this->cellHeight, $ars->duration);

        // Duration
        $pdf->SetFont('Arial', 'B', ($this->fontSize - 2));
        $pdf->Cell(15, $this->cellHeight, 'SR No.:');
        $pdf->SetFont('Arial', 'B', $this->fontSize);
        $pdf->Cell(35, $this->cellHeight - 1, $ars->sr_no, 'B');

        // create DASH
        $pdf->Ln(2);
        $pdf->Ln();
        $pdf->SetLineWidth(0.1);
        $pdf->SetDash(2, 2); //2mm on, 2mm off
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Line($x, $y, 200, $y);

        // Billing Instruction
        $pdf->SetDash(); //restores no dash
        $pdf->SetFont('Arial', 'BU', $this->fontSize - 2);
        $pdf->Cell(43, $this->cellHeight, 'Billing Instruction');
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell(43, $this->cellHeight, '(to be accomplished by Supervising Partner only)');

        // billing Type
        $pdf->Ln();
        $pdf->SetFont('ZapfDingbats','', 10);
        $check = ($ars->billing_instruction_type == 'Non-Billable') ? 4: '';
        $pdf->Cell(5, $this->cellHeight - 2, $check, 'B', 0);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 4);
        $pdf->Cell(23, $this->cellHeight, 'Non-Billable');

        // Explanation
        $pdf->Cell(20, $this->cellHeight, 'Explanation:');
        $pdf->SetFont('Arial', '', $this->fontSize - 4);
        $pdf->Cell(138, $this->cellHeight - 2, $ars->billing_instruction, 'B');

        // Billing Type
        $pdf->Ln();
        $pdf->SetFont('ZapfDingbats','', 10);
        $check = ($ars->billing_instruction_type == 'Billable') ? 4: '';
        $pdf->Cell(5, $this->cellHeight - 2, $check, 'B', 0);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 4);
        $pdf->Cell(27, $this->cellHeight, 'Billable');

        $pdf->SetFont('ZapfDingbats','', 10);
        $check = ($ars->billing_instruction_type == 'Appearance') ? 4: '';
        $pdf->Cell(5, $this->cellHeight - 2, $check, 'B', 0);
        $pdf->SetFont('Arial', '', $this->fontSize - 4);
        $pdf->Cell(35, $this->cellHeight, 'Appearance (Fixed)');

        $pdf->SetFont('ZapfDingbats','', 10);
        $check = ($ars->billing_instruction_type == 'Appearance') ? 4: '';
        $pdf->Cell(5, $this->cellHeight - 2, $check, 'B', 0);
        $pdf->SetFont('Arial', '', $this->fontSize - 4);
        $pdf->Cell(37, $this->cellHeight, 'Documentation (Page)');

        // Explanation
        $pdf->Cell(13, $this->cellHeight, 'Others:');
        $pdf->Cell(59, $this->cellHeight - 2, $ars->billing_instruction, 'B');

        // Billing Type
        $pdf->Ln();
        $pdf->SetX(42);
        $pdf->SetFont('ZapfDingbats','', 10);
        $check = ($ars->billing_instruction_type == 'Time-Trate') ? 4: '';
        $pdf->Cell(5, $this->cellHeight - 2, $check, 'B', 0);
        $pdf->SetFont('Arial', '', $this->fontSize - 4);
        $pdf->Cell(92, $this->cellHeight, 'Time-Trate (Appearance/Documentation/Study/Meeting/Teleconference)');

        // Billing hrs
        $pdf->Cell(20, $this->cellHeight, 'Billable Hours:');
        $pdf->Cell(20, $this->cellHeight - 2, ($ars->billable_time)? $ars->billable_time: '', 'B', 0, 'C');
        $pdf->Cell(5, $this->cellHeight, 'mins.');

        // Billing Entry
        $pdf->Ln();
        $pdf->SetFont('Arial', '', $this->fontSize -2);
        $pdf->Cell(21, $this->cellHeight, 'Billing Entry:');
        $pdf->Cell(166, $this->cellHeight - 2, $ars->billing_entry, 'B');

        // Billing Entry
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell(47, $this->cellHeight, 'Approval:', 1);
        $pdf->Cell(47, $this->cellHeight, 'SR Encoder:', 1);
        $pdf->Cell(47, $this->cellHeight, 'CE Encoder:', 1);
        $pdf->Cell(47, $this->cellHeight, 'Reviewer:', 1);

        // OUTPUT
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'BU', $this->fontSize);
        $pdf->Cell(43, $this->cellHeight -2, 'Outcome/Output:');
        $pdf->Ln();
        $pdf->SetFont('Arial', '', $this->fontSize - 4);
        $pdf->Cell(43, $this->cellHeight - 2, '(Enumerate outcome and/or output product for client');

        // List of Output
        $pdf->Ln();
        $pdf->Ln();
        $pdf->showBorder = 0;
        $pdf->SetWidths([10, 10, 170]);
        $pdf->SetFont('Arial', '', $this->fontSize);
        foreach ($ars->oos as $key => $o) {
            $pdf->Row(['', $key+1 . '.', $o->description]);
        }

        // OUTPUT
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'BU', $this->fontSize);
        $pdf->Cell(43, $this->cellHeight -2, 'Future Activity/Date:');
        $pdf->Ln();
        $pdf->SetFont('Arial', '', $this->fontSize - 4);
        $pdf->Cell(43, $this->cellHeight - 2, '(Briefly state expected succeeding activity and due date');

        // List of Feature Activities
        $pdf->Ln();
        $pdf->showBorder = 0;
        $pdf->SetWidths([10, 10, 170]);
        $pdf->SetFont('Arial', '', $this->fontSize);
        foreach ($ars->fads as $key => $fad) {
            $pdf->Row(['', $key+1 . '.', $fad->description]);
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
}
