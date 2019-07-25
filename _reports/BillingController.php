<?php 

namespace Reports;

use Repositories\BillingRepository;
use Reports\Services\GenerateBillingReport;

use Services\PDF_MC_Table;
use Services\FPDF_COLOR;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Billing;

use DB;
use Carbon\Carbon;

class BillingController extends Controller
{
    private $billingRepository;
    private $billingReport;

    public function __construct(FPDF_COLOR $color, BillingRepository $billingRepository, GenerateBillingReport $billingReport)
    {
        $this->color = $color;

        $this->billingRepository = $billingRepository;
        $this->billingReport = $billingReport;
    }


    public function index(Request $request)
    {
        $inputs = $request->except('_token');
        
        $billings = $this->billingRepository->getUnpaidBills($inputs['client_id']);

        $latestBill = $billings->first();

        if (!$latestBill) {
            echo "No Data Found";
            return;
        }
        
        $billingReport = $this->billingReport->generateReport($billings);

        return response($billingReport['content'], 200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Length'      =>  strlen($billingReport['content']),
                'Content-Disposition' => 'inline; filename="' . $billingReport['title'] . '.pdf"',
                'Cache-Control'       => 'private, max-age=0, must-revalidate',
                'Pragma'              => 'public'
            ]
        );
    }


    public function previewBillingPdf($billingId)
    {
        $billing = $this->billingRepository->getBill($billingId);
        $data = base64_decode($billing->pdf);
        header('Content-Type: application/pdf');
        echo $data;
    }

    public function getSuggestedFontSize($pdf, $str, $cellWidth, $fontWeight, $startingFontSize)
    {
        /* I know that the font size starts with 11, so i set a variable at this size */
        $x = $startingFontSize;    // Will hold the font size
        /* I will cycle decreasing the font size until it's width is lower than the max width */
        while( $pdf->GetStringWidth( utf8_decode( $str ) ) > $cellWidth ){
            $x--;   // Decrease the variable which holds the font size
            $pdf->SetFont('Arial', $fontWeight, $x); // Set the new font size
        }
    }
}
