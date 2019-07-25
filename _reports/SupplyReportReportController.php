<?php

namespace Reports;



use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Settings\SupplyCategories;
use Repositories\SupplyRepository;
use Services\PDF_MC_Table;
use Services\FPDF_COLOR;

class SupplyReportReportController extends Controller
{
	private $baseX = 10;

    private $cellHeight = 8;

    private $fontSize = 12;

    private $supplyRepository;
    private $supplyCategory;

    public function __construct(FPDF_COLOR $color, SupplyRepository $supplyRepository, SupplyCategories $supplyCategory)
    {
        $this->color = $color;

        $this->supplyRepository = $supplyRepository;
        $this->supplyCategory = $supplyCategory;
    }

    public function index(Request $request)
    {
        $now = new Carbon();

    	$title = 'Office Supply Report';

        // Set a filename
        $filename = "AppName_Day__gen_".date("Y-m-d_H-i").".pdf";

        $pdf = new PDF_MC_Table();
        $pdf->AddPage();
        $pdf->SetTitle($title);

        $categories = $this->groupSupplyByCategory($this->supplyRepository->getAll());


        // Title
        $pdf->SetFont('Arial', 'B', $this->fontSize + 2);
        $pdf->Cell(190, $this->cellHeight + 2, 'Office Supply '. $now->format('Y') .' Report', 0, 0, 'C');



        // Table Title
        $pdf->Ln(20);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 3);
        // $pdf->SetFillColor(176,196,222);
        $pdf->SetFillColor(180, 216, 231);
        $pdf->Cell(10, $this->cellHeight - 2, '#', 1, 0, 'L', true);
        $pdf->Cell(60, $this->cellHeight - 2, 'NAME', 1, 0, 'L', true);
        $pdf->Cell(30, $this->cellHeight - 2, 'IN', 1, 0, 'R', true);
        $pdf->Cell(30, $this->cellHeight - 2, 'OUT', 1, 0, 'R', true);
        $pdf->Cell(30, $this->cellHeight - 2, 'BALANCE', 1, 0, 'R', true);
        $pdf->Cell(30, $this->cellHeight - 2, 'SHORT', 1, 0, 'R', true);

        $pdf->Ln();


        $pdf->SetWidths([10, 60, 30, 30, 30, 30]);
        $pdf->SetAligns(['L', 'L', 'R', 'R', 'R', 'R']);

        foreach ($categories as $key => $c) {
            // dd($c);
            $pdf->SetFont('Arial', 'B', $this->fontSize);
            $pdf->cell(180, $this->cellHeight, $c['title'], 0, 2);
            $pdf->SetFont('Arial', 'B', $this->fontSize - 3);

            foreach ($c['supplies'] as $sKey => $s) {
                $balance = $s->total_in - $s->total_out;
                $pdf->Row([
                    $sKey +1,
                    $s->name,
                    $s->total_in,
                    $s->total_out,
                    ($balance >= 0) ? $balance : '',
                    ($balance < 0) ? $balance : '',
                ]);
            }
        }


        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', $this->fontSize - 3);
        $pdf->Cell(80, $this->cellHeight - 2, 'Supplies to be replenish:', 0, 0, 'L');

        $pdf->Ln(1);
        $pdf->Cell(80, $this->cellHeight - 2, '', 0, 2, 'L');
        $pdf->Cell(80, $this->cellHeight - 2, '', 'B', 2, 'L');
        $pdf->Cell(80, $this->cellHeight - 2, '', 'B', 2, 'L');
        $pdf->Cell(80, $this->cellHeight - 2, '', 'B', 2, 'L');
        $pdf->Cell(80, $this->cellHeight - 2, '', 'B', 2, 'L');


        $pdf->Ln(15);
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell(80, $this->cellHeight - 2, 'Prepared By:', 0, 0, 'L');

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', $this->fontSize + 2);
        $pdf->Cell(80, $this->cellHeight - 2, 'Laurence Bernadette B. Tabuerna', 0, 2, 'L');
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell(80, $this->cellHeight - 2, 'Executive Assistant', 0, 0, 'L');

        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell(80, $this->cellHeight - 2, 'Approved By:', 0, 0, 'L');


        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', $this->fontSize + 2);
        $pdf->Cell(80, $this->cellHeight - 2, 'Marli A. Ralla', 0, 2, 'L');
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell(80, $this->cellHeight - 2, 'Office Manager', 0, 0, 'L');

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


    private function groupSupplyByCategory($supplies)
    {
        $caterogies = array_unique($supplies->pluck('category')->toArray());

        $data = [];

        foreach ($caterogies as $cKey => $c) {
            $c = $this->supplyCategory->findCetegory($c);
            $data[$c['code']] = array_merge($c, ['supplies' => []]);
        }

        foreach ($supplies as $sKey => $s) {
            $data[$s->category]['supplies'][] = $s;
        }


        return $data;
    }
}
