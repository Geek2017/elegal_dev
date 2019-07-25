<?php

namespace Reports\Services;

use Repositories\BillingRepository;

use Services\PDF_MC_Table;
use Services\FPDF_COLOR;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Billing;

use DB;
use Carbon\Carbon;
use App\Note;

class GenerateBillingReport
{
    private $baseX = 10;

    private $cellHeight = 8;

    private $fontSize = 12;

    private $color;

    public function generateReport($billings)
    {
        $latestBill = $billings->first();

        if ($latestBill->special_billing) {
            return $this->generateSpecialBill($billings);
        } else {
            return $this->generateRegularBill($billings);
        }
    }

    public function generateSpecialBill($billings)
    {
        $now = new Carbon();
        // Set a filename
        $filename = "AppName_Day__gen_".date("Y-m-d_H-i").".pdf";

        // get lates bill
        $latestBill = $billings->first();

        $title = 'Billing - ' . $latestBill->bill_date;

        $pdf = new PDF_MC_Table();
        $pdf->showLogoAsBackground = true;
        $pdf->AddPage('P');
        $pdf->SetTitle($title);

        $pdf->Ln(15);
        $pdf->SetX(121);
        // $pdf->SetTextColor(205,133,63);
        // $pdf->setTextColor(0,0,0);

        // Title
        $pdf->SetFont('Arial', 'B', $this->fontSize + 5);
        $pdf->Cell(75, $this->cellHeight + 5, 'PROFESSIONAL SERVICES');

        // Right data
        $pdf->Ln(15);
        $pdf->SetX(122);
        $pdf->SetFillColor(180, 216, 231);
        $pdf->SetFont('Arial', '', $this->fontSize);
        $pdf->Cell(30, $this->cellHeight - 3, 'Date', 0, 0, 'L');
        $pdf->SetFont('Arial', '', $this->fontSize - 1);
        $pdf->Cell(48, $this->cellHeight - 3, $latestBill->bill_date->format('m/d/Y'), 1, 1, 'C', true);
        // $pdf->SetTextColor(0, 0, 0);

        $pdf->SetX(122);
        $pdf->SetFont('Arial', '', $this->fontSize);
        $pdf->Cell(30, $this->cellHeight - 3, 'Invoice', 0, 0, 'L');
        $pdf->SetFont('Arial', '', $this->fontSize - 1);
        $pdf->Cell(48, $this->cellHeight - 3, $latestBill->bill_number, 1, 1, 'C');

        $pdf->SetX(122);
        $pdf->SetFont('Arial', '', $this->fontSize);
        $pdf->Cell(30, $this->cellHeight - 3, 'Client ID', 0, 0, 'L');
        $pdf->SetFont('Arial', '', $this->fontSize -1);
        $pdf->Cell(48, $this->cellHeight - 3, $latestBill->client_id, 1, 1, 'C');

        $pdf->SetX(122);
        $startDay = $latestBill->bill_date->startOfMonth()->format('d');
        $endDay = $latestBill->bill_date->endOfMonth()->format('d');
        $completeDay = $latestBill->bill_date->format('F') . " {$startDay} - $endDay, " . $latestBill->bill_date->format('Y');
        $pdf->Cell(30, $this->cellHeight - 3, 'Billing Period', 0, 0, 'L');
        $pdf->Cell(48, $this->cellHeight - 3, $completeDay , 1, 1, 'C');
        $pdf->Ln();

        // Client Information
        // $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Cell(90, $this->cellHeight - 3, 'TO:', 1, 2, 'C', true);
        $pdf->SetFont('Arial', '', $this->fontSize);
        $pdf->Ln(1);
        $pdf->Cell(90, $this->cellHeight - 3, $latestBill->client->profile->full_name , 0, 1, 'L');
        $pdf->SetFillColor(255, 255, 255);
        $businessName = ($latestBill->client->business) ? $latestBill->client->business->name : $latestBill->client->billingAddress->name;
        if ($businessName) {
            $pdf->Cell(90, $this->cellHeight - 3, $businessName , 0, 0, 'L');
            $pdf->Ln(4);
        }
        $address = ($latestBill->client->business) ? $latestBill->client->business->address->description : $latestBill->client->billingAddress->address->description;
        $pdf->Cell(90, $this->cellHeight, $address, 0, 0);

        // $pdf->SetFillColor(205,133,63);
        // $mobile = ($latestBill->client->business) ? $latestBill->client->business->mobile->description : $latestBill->client->billingAddress->mobile->description;
        // if ($mobile) {
        //     $pdf->Cell(90, $this->cellHeight - 3, $mobile , 0, 1, 'L');
        // }


        // Client Information
        $rectWidth = 190;

        // Billing Summary
        $pdf->Ln(15);
        $pdf->Rect($pdf->getX(), $pdf->getY(), $rectWidth, 100);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->SetFillColor(180, 216, 231);
        // $pdf->SetTextColor(255,255,255);
        $pdf->Cell($rectWidth, $this->cellHeight - 2, 'SPECIAL BILLING', 1, 0, 'C', true);
        // $pdf->SetTextColor(0,0,0);
        // $pdf->Ln();
        // $pdf->SetFont('Arial', 'I', $this->fontSize);
        // $pdf->Cell($rectWidth, $this->cellHeight - 2, 'Special Billing', 0, 0);

        $pdf->Ln(10);

        // Current Charges
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Ln(2);
        $pdf->Cell( ($rectWidth - 185), $this->cellHeight - 2, '', 0, 0);
        $totalCurrenCharges = $latestBill->special + $latestBill->general + $latestBill->excess;
        $pdf->Cell( ($rectWidth - 40), $this->cellHeight - 2, 'CURRENT CHARGES', 'B', 0);
        $pdf->Cell( ($rectWidth - 160), $this->cellHeight - 2, number_format($totalCurrenCharges,2), 'B', 0, 'R');

        $pdf->Ln();
        $pdf->Ln();

        // list of Case
        $pdf->SetWidths([($rectWidth - 180), ($rectWidth - 30)]);
        $pdf->SetAligns(['L', 'L']);
        $pdf->showBorder = 0;
        foreach ($latestBill->transactionFeeDetails as $key => $tfd) {
            $caseTitle = $tfd->cases->title ? "{$tfd->cases->title} " : "No Case Name";
            $caseTitle .= ($tfd->cases->number) ? ' [' .$tfd->cases->number.']' : '';
            $pdf->row(['', $caseTitle]);
        }

        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell( ($rectWidth - 180), $this->cellHeight - 2, '', 0, 0);
        $pdf->Cell( ($rectWidth - 60), $this->cellHeight - 2, '[ Case Information ]', 'T', 0);

        // list of fees
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->SetWidths([($rectWidth - 180), ($rectWidth - 120), ($rectWidth - 130)]);
        $pdf->SetAligns(['L', 'L', 'R']);
        $pdf->showBorder = 0;
        foreach ($latestBill->transactionFeeDetails as $key => $tfd) {
            $pdf->row(['', $tfd->fee->display_name, number_format($tfd->amount, 2)]);
        }


        // Total Charges
        // $pdf->Ln();
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Cell( ($rectWidth - 170), $this->cellHeight - 2, '', 0, 0);
        $pdf->Cell( ($rectWidth - 130), $this->cellHeight - 2, 'TOTAL CURRENT CHARGES', 0, 0);
        $pdf->Cell( ($rectWidth - 130), $this->cellHeight - 2, number_format($totalCurrenCharges,2), 'T', 0, 'R');

        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Ln(8);
        $pdf->Cell($rectWidth, $this->cellHeight - 2, '', 'B', 0, 'C');

        $pdf->setY(200);
        // Notes
        $rectSize = ($rectWidth / 2);
        $pdf->Ln(10);
        $pdf->SetFillColor(180, 216, 231);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2.5);
        $pdf->Cell($rectSize, $this->cellHeight - 3, 'NOTES:', 1, 2, 'L', true);
        $pdf->SetFont('Arial', '', $this->fontSize - 4);

        $checkInfo = Note::where('name', 'special-billing-notes')->first();
        $specialBillingNotes = \Html2Text\Html2Text::convert($checkInfo->description);

        $pdf->SetWidths([($rectSize)]); //
        $pdf->SetAligns(['L']);
        $pdf->showBorder = 0;

        $ctr = 1;
        $y = 0;
        foreach (explode("\n", $specialBillingNotes) as $key => $str) {
            if (strtolower(substr($str, 0, 5)) == 'note:') {
                $pdf->row([$str]);
            }else if ($str != '') {
                $str = substr_replace($str, "{$ctr}.", 0, 1);
                $pdf->row(["{$str}"]);
                $ctr++;
                $y += 12;
            }
        }


        $pdf->SetXY($rectSize + 12, $pdf->GetY() - $y);

        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->showBorder = 0;
        $pdf->SetWidths([42.5, 12.5, 40]); // TEXT, ₱, amount
        $pdf->SetAligns(['L', 'R', 'R']);
        $pdf->row(['Professional Fees', 'Php', number_format($totalCurrenCharges, 2)]);
        $pdf->SetXY($rectSize + 12, $pdf->GetY() + 1);
        $pdf->row(['% Tax on PF', 'Php', number_format($latestBill->tax_amount, 2)]);
        $pdf->SetXY($rectSize + 12, $pdf->GetY() + 1);

        $billOverAllTotal = $totalCurrenCharges + $latestBill->tax_amount;

        // $pdf->SetFont('Arial', 'B', $this->fontSize -2);
        $pdf->row(['Total Amount Due', 'Php', number_format($billOverAllTotal, 2)]);

        $pdf->SetXY($rectSize + 12, $pdf->GetY() + 5);

        $pdf->SetFont('Arial', 'B', $this->fontSize - 3);
        // $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell($rectSize, $this->cellHeight - 3, ' PAYABLE TO', 1, 2, 'C', true);
        // $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', $this->fontSize );
        $checkInfo = Note::where('name', 'checking-info')->first();
        $text = \Html2Text\Html2Text::convert($checkInfo->description);
        $pdf->Cell($rectSize, $this->cellHeight, $text, 0, 2, 'C');

        $pdfContent = $pdf->Output("{$title}.pdf", 'S');

        $latestBill->pdf = base64_encode($pdfContent);
        $latestBill->save();


        return [
            'title' => $title,
            'content' => $pdfContent,
        ];
    }

    public function generateRegularBill($billings)
    {
        $now = new Carbon();
        // Set a filename
        $filename = "AppName_Day__gen_".date("Y-m-d_H-i").".pdf";

        // get lates bill
        $latestBill = $billings->first();

        $title = 'Billing - ' . $latestBill->created_at;

        $pdf = new PDF_MC_Table();
        $pdf->showLogoAsBackground = true;
        $pdf->AddPage('P');
        $pdf->SetTitle($title);

        $pdf->Ln(15);
        $pdf->SetX(121);
        // $pdf->SetTextColor(205,133,63);
        // $pdf->setTextColor(0,0,0);

        // Title
        $pdf->SetFont('Arial', 'B', $this->fontSize + 5);
        $pdf->Cell(75, $this->cellHeight + 5, 'PROFESSIONAL SERVICES');

        // Right data
        $pdf->SetFont('Arial', '', $this->fontSize);
        $pdf->Ln(15);

        $pdf->SetX(122);
        $pdf->SetFillColor(180, 216, 231);
        $pdf->Cell(30, $this->cellHeight - 3, 'Date', 0, 0, 'L');
        $pdf->Cell(48, $this->cellHeight - 3, $latestBill->bill_date->format('m/d/Y'), 1, 1, 'C', true);

        $pdf->SetX(122);
        $pdf->Cell(30, $this->cellHeight - 3, 'Invoice', 0, 0, 'L');
        $pdf->Cell(48, $this->cellHeight - 3, $latestBill->bill_number, 1, 1, 'C');

        $pdf->SetX(122);
        $pdf->Cell(30, $this->cellHeight - 3, 'Client ID', 0, 0, 'L');
        $pdf->Cell(48, $this->cellHeight - 3, $latestBill->client_id, 1, 1, 'C');

        $pdf->SetX(122);
        $startDay = $latestBill->bill_date->startOfMonth()->format('d');
        $endDay = $latestBill->bill_date->endOfMonth()->format('d');
        $completeDay = $latestBill->bill_date->format('F') . " {$startDay} - $endDay, " . $latestBill->bill_date->format('Y');
        $pdf->Cell(30, $this->cellHeight - 3, 'Billing Period', 0, 0, 'L');
        $pdf->Cell(48, $this->cellHeight - 3, $completeDay , 1, 1, 'C');

        // Client Information
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Cell(90, $this->cellHeight - 3, 'TO:', 1, 2, 'C', true);
        $pdf->SetFont('Arial', '', $this->fontSize);

        $pdf->Cell(90, $this->cellHeight - 3, $latestBill->client->profile->full_name , 0, 1, 'L');
        $businessName = ($latestBill->client->business) ? $latestBill->client->business->name : $latestBill->client->billingAddress->name;
        if ($businessName){
            $pdf->Cell(90, $this->cellHeight - 3, $businessName , 0, 0, 'L');
        }
        $address = ($latestBill->client->business) ? $latestBill->client->business->address->description : $latestBill->client->billingAddress->address->description;
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln();
        $pdf->Cell(90, $this->cellHeight - 3, $address , 0, 0, 'L', false);
        $pdf->Ln(5);
        // $mobile = ($latestBill->client->business) ? $latestBill->client->business->mobile->description : $latestBill->client->billingAddress->mobile->description;
        // if ($mobile) {
        //     $pdf->Cell(90, $this->cellHeight - 3, $mobile , 0, 1, 'L');
        // }


        // Client Information
        $rectWidth = 190;

        // Billing Summary
        $pdf->Ln();
        $recStartingY = $pdf->getY();
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->SetFillColor(180, 216, 231);
        $pdf->Cell($rectWidth, $this->cellHeight - 2, 'BILLING SUMMARY', 1, 0, 'C', true);
        // $pdf->SetTextColor(0,0,0);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'I', $this->fontSize - 2);
        $pdf->Cell($rectWidth, $this->cellHeight - 2, 'I. Items Subject to Tax [ PROFESSIONAL FEES ]', 0, 0);



        $totalUnPaid = 0;
        foreach ($billings as $key => $bill) {
            if ($bill->id == $latestBill->id) {
                continue;
            }
            $latestBalance = (!$bill->paid)? $bill->total: $bill->balance;
            $totalUnPaid += $latestBalance;
        }
        if ($totalUnPaid) {
            // UNSETTLED BILL
            $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
            $pdf->Ln(5);
            $pdf->Cell( ($rectWidth - 185), $this->cellHeight - 2, '', 0, 0);
            $pdf->Cell( ($rectWidth - 40), $this->cellHeight - 2, 'UNPAID BILL', 'B', 0);
            $pdf->Cell( ($rectWidth - 160), $this->cellHeight - 2, number_format($totalUnPaid, 2), 'B', 0, 'R');
        } else {
            // $pdf->Cell( ($rectWidth - 160), $this->cellHeight - 2, '', 'B', 0, 'R');
        }

        // TABLE for UNSETTLED BILL
        $pdf->Ln(10);

        if ($totalUnPaid) {
            $pdf->SetFont('Arial', '', $this->fontSize - 2);
            $pdf->setX(35);
            $pdf->SetWidths([10, 40, 30, 30, 30]); // #, Bill, Total, Amont Paid, Latest Balance
            $pdf->SetAligns(['L', 'L', 'R', 'R', 'R']);
            $pdf->row(['#', 'Bill', 'Total', 'Amount Paid', 'Latest Balance']);
            $total = 0;
            $totalPaid = 0;
            $index = 1;
            $pdf->showBorder = 0;
            foreach ($billings as $key => $bill) {
                if ($bill->id == $latestBill->id) {
                    continue;
                }
                $pdf->setX(35);
                $totalAmountPaid = (!$bill->paid) ? '-' : ($bill->total - $bill->balance);
                $latestBalance = (!$bill->paid)? $bill->total: $bill->balance;
                $pdf->Row([$index++, $bill->bill_number, $bill->total, $totalAmountPaid, $latestBalance]);

                $total += $bill->total;
                $totalPaid += (!$bill->paid) ? 0 : $totalAmountPaid;
            }

            // Show tatol
            $pdf->setX(35);
            $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
            $pdf->SetWidths([50, 30, 30, 30]); // #, Bill, Total, Amont Paid, Latest Balance
            $pdf->SetAligns(['L', 'R', 'R', 'R']);
            $pdf->Row(['', number_format($total,2), number_format($totalPaid, 2), number_format($totalUnPaid, 2)]);
        } else {
            // No Unsettled Bill
            // $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
            // $pdf->Ln(2);
            // $pdf->Cell($rectWidth, $this->cellHeight - 2, 'No Unsettled Bill', 0, 0, 'C');
            // $pdf->Ln(5);
        }

        // Current Charges
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Ln(2);
        $pdf->Cell( ($rectWidth - 185), $this->cellHeight - 2, '', 0, 0);
        $totalCurrenCharges = $latestBill->special + $latestBill->general + $latestBill->excess;
        $pdf->Cell( ($rectWidth - 40), $this->cellHeight - 2, 'CURRENT CHARGES', 'B', 0);
        $pdf->Cell( ($rectWidth - 160), $this->cellHeight - 2, number_format($totalCurrenCharges,2), 'B', 0, 'R');

        // Previous Balance
        $pdf->Ln();
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell( ($rectWidth - 180), $this->cellHeight - 2, '', 0, 0);
        $pdf->Cell( ($rectWidth - 120), $this->cellHeight - 2, 'Special Retainers', 0, 0);
        $pdf->Cell( ($rectWidth - 130), $this->cellHeight - 2, number_format($latestBill->special, 2), 0, 0, 'R');
        // Special Retainers
        $pdf->Ln();
        $pdf->Cell( ($rectWidth - 180), $this->cellHeight - 2, '', 0, 0);
        $pdf->Cell( ($rectWidth - 120), $this->cellHeight - 2, 'Fixed General Retainers', 0, 0);
        $pdf->Cell( ($rectWidth - 130), $this->cellHeight - 2, number_format($latestBill->general, 2), 0, 0, 'R');
        // Excess General Retainers
        $pdf->Ln();
        $pdf->Cell( ($rectWidth - 180), $this->cellHeight - 2, '', 0, 0);
        $pdf->Cell( ($rectWidth - 120), $this->cellHeight - 2, 'Excess General Retainers', 0, 0);
        $pdf->Cell( ($rectWidth - 130), $this->cellHeight - 2, number_format($latestBill->excess, 2), 0, 0, 'R');
        // Total Charges
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Cell( ($rectWidth - 170), $this->cellHeight - 2, '', 0, 0);
        $pdf->Cell( ($rectWidth - 130), $this->cellHeight - 2, 'TOTAL CURRENT CHARGES', 0, 0);
        $pdf->Cell( ($rectWidth - 130), $this->cellHeight - 2, number_format($totalCurrenCharges,2), 'T', 0, 'R');


        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Ln(8);
        $pdf->Cell( ($rectWidth - 185), $this->cellHeight - 2, '', 0, 0);
        $pdf->Cell( ($rectWidth - 40), $this->cellHeight - 2, 'SUB TOTAL', 'B', 0);
        $pdf->Cell( ($rectWidth - 160), $this->cellHeight - 2, number_format(($totalCurrenCharges + $totalUnPaid),2), 'B', 0, 'R');

        // Trust Fund
        $pdf->Ln(10);
        // $pdf->Rect($pdf->getX(), $pdf->getY(), $rectWidth, 60);
        $pdf->SetFont('Arial', 'I', $this->fontSize - 2);
        $pdf->Cell($rectWidth, $this->cellHeight - 2, 'II. Items Not Subject to Tax [ CHARGEABLE EXPENSES ]', 0, 0);

        // UNSETTLED OPERATIONAL FUND

        $OfTotalUnPaid = 0;
        foreach ($billings as $key => $bill) {
            // ignore latest balance
            if ($bill->id == $latestBill->id) {
                continue;
            }
            // ignore billing w/out Operational Fund
            if (!$bill->operationalFund) {
                continue;
            }
            // ignore fully paid Operational Fund
            if ( !($bill->operationalFund->amount - $bill->operationalFund->total_amount_paid) ) {
                continue;
            }
            $operationalFund = $bill->operationalFund;

            $latestBalance = ($bill->operationalFund->amount - $bill->operationalFund->total_amount_paid);
            $OfTotalUnPaid += $latestBalance;

        }
        if ($OfTotalUnPaid) {
            $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
            $pdf->Ln(5);
            $pdf->Cell( ($rectWidth - 185), $this->cellHeight - 2, '', 0, 0);
            $pdf->Cell( ($rectWidth - 40), $this->cellHeight - 2, 'UNPAID OPERATIONAL FUND', 'B', 0);
            $pdf->Cell( ($rectWidth - 160), $this->cellHeight - 2, number_format($OfTotalUnPaid, 2), 'B', 0, 'R');
        } else {
            // $pdf->Cell( ($rectWidth - 160), $this->cellHeight - 2, '', 'B', 0, 'R');
        }


        // TABLE for UNSETTLED OPERATIONAL FUND
        $pdf->Ln(10);

        if ($OfTotalUnPaid) {
            $pdf->SetFont('Arial', '', $this->fontSize - 2);
            $pdf->showBorder = 1;
            $pdf->setX(35);
            $pdf->SetWidths([10, 40, 30, 30, 30]); // #, Bill, Total, Amont Paid, Latest Balance
            $pdf->SetAligns(['L', 'L', 'R', 'R', 'R']);
            $pdf->row(['#', 'Bill', 'Total', 'Amount Paid', 'Latest Balance']);
            $pdf->showBorder = 0;

            $OfTotal = 0;
            $OfTotalPaid = 0;
            $index = 1;
            foreach ($billings as $key => $bill) {

                // ignore latest balance
                if ($bill->id == $latestBill->id) {
                    continue;
                }
                // ignore billing w/out Operational Fund
                if (!$bill->operationalFund) {
                    continue;
                }
                // ignore fully paid Operational Fund
                if ( !($bill->operationalFund->amount - $bill->operationalFund->total_amount_paid) ) {
                    continue;
                }

                $pdf->setX(35);
                $operationalFund = $bill->operationalFund;
                $OfTotalAmountPaid = (!$bill->paid) ? '-' : ($operationalFund->amount - $operationalFund->balance);
                $latestBalance = ($bill->operationalFund->amount - $bill->operationalFund->total_amount_paid);
                $pdf->Row([$index++, $bill->bill_number, $operationalFund->amount, $OfTotalAmountPaid, $latestBalance]);

                $OfTotal += $operationalFund->amount;
                $OfTotalPaid += (!$bill->paid) ? 0 : $OfTotalAmountPaid;
            }

            // Show tatol
            $pdf->setX(35);
            $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
            $pdf->SetWidths([50, 30, 30, 30]); // #, Bill, Total, Amont Paid, Latest Balance
            $pdf->SetAligns(['L', 'R', 'R', 'R']);
            $pdf->Row(['', number_format($OfTotal, 2), number_format($OfTotalPaid, 2), number_format($OfTotalUnPaid, 2)]);
            $pdf->Ln(5);
        } else {
            // No Unsettled Bill
            // $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
            // $pdf->Cell($rectWidth, $this->cellHeight - 2, 'No Unpaid Operational Fund From Previous Bill', 0, 0, 'C');
        }

        $pdf->SetFont('Arial', '', $this->fontSize - 2);

        $totalChargeables = 0;

        if (sizeof($latestBill->serviceReports)) {
            foreach ($latestBill->serviceReports as $key => $sr) {
                foreach ($sr->chargeables as $key => $c) {
                    $totalChargeables += $c->total;
                }
            }
        }


        $currentCharge = ($totalChargeables) ? number_format($totalChargeables, 2) : '0.00';
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);

        $pdf->Cell( ($rectWidth - 185), $this->cellHeight - 2, '', 0, 0);
        $pdf->Cell( ($rectWidth - 40), $this->cellHeight - 2, 'CURRENT CHARGES', 'B', 0);
        $pdf->Cell( ($rectWidth - 160), $this->cellHeight - 2, $currentCharge, 'B', 0, 'R');

        // Current Charges
        $pdf->Ln(10);
        $pdf->Cell( ($rectWidth - 180), $this->cellHeight - 2, '', 0, 0);
        $pdf->Cell( ($rectWidth - 120), $this->cellHeight - 2, 'Current Trust Fund Balance', 0, 0);

        $latestTrustFund = 0;
        if ($latestBill->client->latestTrustFund) {
            // if latest trust fund balance > 0
            if ($latestBill->client->latestTrustFund->balance) {
                $latestTrustFund = $latestBill->client->latestTrustFund->balance + $latestBill->client->latestTrustFund->credit;
            } else {
                $latestTrustFund = $latestBill->client->latestTrustFund->credit;
            }

            $pdf->Cell(($rectWidth - 130), $this->cellHeight - 2, number_format($latestTrustFund, 2), 0, 0, 'R');
        } else {
            $pdf->Cell(($rectWidth - 130), $this->cellHeight - 2, '-', 0, 0, 'R');
        }

        // $currentCharge = ($latestBill->operationalFund) ? number_format($latestBill->operationalFund->amount, 2) : '-';

        // Deposit/PAID
        $pdf->Ln();
        $pdf->Cell( ($rectWidth - 180), $this->cellHeight - 2, '', 0, 0);
        $pdf->Cell( ($rectWidth - 120), $this->cellHeight - 2, 'Less: Chargeable Expenses', 0, 0);
        $pdf->Cell( ($rectWidth - 130), $this->cellHeight - 2, $currentCharge, 0, 0, 'R');




        // $computationOftotalAmountForReimbursement = (($latestBill->operationalFund)? $latestBill->operationalFund->amount : 0) - $latestTrustFund;
        // $totalAmountForReimbursement = ($computationOftotalAmountForReimbursement > 0) ? 0 : $computationOftotalAmountForReimbursement;

        // TOTAL AMOUNT FOR REIMBURSEMENT
        $totalAmountForReimbursement = $latestTrustFund - $totalChargeables;

        $pdf->Ln();
        $pdf->Cell( ($rectWidth - 170), $this->cellHeight - 2, '', 0, 0);
        // $pdf->Cell( ($rectWidth - 130), $this->cellHeight - 2, 'TOTAL AMOUNT FOR REIMBURSEMENT', 0, 0);
        $pdf->Cell( ($rectWidth - 130), $this->cellHeight - 2, 'REMAINING T.F. DEPOSIT', 0, 0);
        $pdf->Cell( ($rectWidth - 130), $this->cellHeight - 2,  ($totalAmountForReimbursement < 0) ? '('.number_format(abs($totalAmountForReimbursement), 2).')' : number_format($totalAmountForReimbursement, 2), 'T', 0, 'R');

        // NOTE
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', $this->fontSize - 3);
        // $pdf->Cell( ($rectWidth - 170), $this->cellHeight - 2, '', 0, 0);
        $pdf->Cell($rectWidth, $this->cellHeight - 2, 'PLEASE IMMEDIATELY reimburse deficit amount of your Trust Fund Account.', 'B', 0, 'L');

        $pdf->Ln();
        $pdf->Rect($pdf->getX(), $recStartingY, $rectWidth, ($pdf->getY() - $recStartingY));

        // Notes
        $rectSize = ($rectWidth / 2);
        $pdf->Ln(10);
        $pdf->SetFillColor(180, 216, 231);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2.5);
        $pdf->Cell($rectSize, $this->cellHeight - 3, 'NOTES:', 1, 2, 'L', true);
        // $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', $this->fontSize - 4);

        // $arrStr = [
        //     'NOTE:',
        //     '1. Bills are due and payable upon receipt.',
        //     // '2. Check payment should be payable to ATTY. PETER LEO M. RALLA.',
        //     '2. Payment made after billing period is not included in this statement.',
        //     '3. The Trust Fund appearing under item II, represents the deposits made to the firm to cover all chargeable expenses.',
        //     '4. Kindly reimburse immediately the deficit amount reflected under the Trust Fund Balance and replenish your deposit account with the firm to cover future expenses. Thank you.'
        // ];


        $checkInfo = Note::where('name', 'billing-note')->first();
        $billigNote = \Html2Text\Html2Text::convert($checkInfo->description);

        $pdf->SetWidths([($rectSize)]); //
        $pdf->SetAligns(['L']);
        $pdf->showBorder = 0;

        $ctr = 1;
        $y = 0;
        foreach (explode("\n", $billigNote) as $key => $str) {
            if (strtolower(substr($str, 0, 5)) == 'note:') {
                $pdf->row([$str]);
            }else if ($str != '') {
                $str = substr_replace($str, "{$ctr}.", 0, 1);
                $pdf->row(["{$str}"]);
                $ctr++;
                $y += 10;
            }
        }


        $pdf->SetXY($rectSize + 12, $pdf->GetY() - $y);

        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->showBorder = 0;
        $pdf->SetWidths([42.5, 12.5, 40]); // TEXT, ₱, amount
        $pdf->SetAligns(['L', 'R', 'R']);
        // $pdf->row(['Professional Fees', 'Php', number_format(($totalCurrenCharges + $totalUnPaid), 2)]);
        $pdf->row(['Professional Fees', 'Php', number_format(($totalCurrenCharges), 2)]);
        $pdf->SetXY($rectSize + 12, $pdf->GetY() + 1);
        $pdf->row(['% Tax on PF', 'Php', number_format($latestBill->tax_amount, 2)]);
        $pdf->SetXY($rectSize + 12, $pdf->GetY() + 1);
        $pdf->row(['Chargeables', 'Php', ($totalAmountForReimbursement < 0) ? number_format(abs($totalAmountForReimbursement), 2) : 0 ]);
        $pdf->SetXY($rectSize + 12, $pdf->GetY() + 1);

        if ($totalAmountForReimbursement > 0) {
            // $billOverAllTotal = $totalCurrenCharges + $totalUnPaid + $latestBill->tax_amount;
            $billOverAllTotal = $totalCurrenCharges + $latestBill->tax_amount;
        } else {
            // $billOverAllTotal = abs($totalAmountForReimbursement) + $totalCurrenCharges + $totalUnPaid + $latestBill->tax_amount;
            $billOverAllTotal = abs($totalAmountForReimbursement) + $totalCurrenCharges + $latestBill->tax_amount;
        }

        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->row(['Total Amount Due', 'Php', number_format($billOverAllTotal, 2)]);

        $pdf->SetXY($rectSize + 12, $pdf->GetY() + 5);

        $pdf->SetFont('Arial', 'B', $this->fontSize - 2.5);
        // $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell($rectSize, $this->cellHeight - 3, 'MAKE ALL CHECKS PAYABLE TO', 1, 2, 'C', true);
        // $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', $this->fontSize + 1);
        $checkInfo = Note::where('name', 'checking-info')->first();
        $text = \Html2Text\Html2Text::convert(strtoupper($checkInfo->description));
        $pdf->Cell($rectSize, $this->cellHeight, $text, 0, 2, 'C');


        $pdf->AddPage();
        // $this->addBackgroundLogo($pdf);
        // $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Cell($rectWidth, $this->cellHeight - 2, 'DETAILS OF SERVICE REPORT', 1, 0, 'C', true);

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 4);
        $pdf->Cell(25, $this->cellHeight + 5, 'No', 1, 0, 'C', true);
        $pdf->Cell(60, $this->cellHeight + 5, 'PROF. SERVICES', 1, 0, 'C', true);
        $pdf->Cell(20, $this->cellHeight + 5, 'FEES', 1, 0, 'C', true);

        $pdf->Cell(5, $this->cellHeight + 5, '', 0, 0, 'C'); // Sperator

        $pdf->Cell(($rectWidth / 2) - 15, $this->cellHeight - 2, 'CHARGEABLE EXPENSES', 1, 0, 'C', true);
        $pdf->Ln();
        $pdf->SetX(($rectWidth / 2) + 25);
        $pdf->Cell(55, $this->cellHeight - 2, 'DESCRIPTION', 1, 0, 'C', true);
        $pdf->Cell(25, $this->cellHeight - 2, 'AMOUNT', 1, 0, 'C', true);

        // die($latestBill->serviceReports);
        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Ln(10);
        $pdf->SetWidths([ 25,  60,  20,  5, 65, 20]);
        $pdf->SetAligns(['L', 'L', 'R', 'C', 'L', 'C']);

        $totalFees =0;
        $totalChargeableFees =0;
        $pdf->includeBGLogo = true;
        $pdf->SetTextColor(0,0,0);
        $pdf->showBorder = 0;
        foreach ($latestBill->serviceReports as $key => $sr) {
            $desc = ($sr->description) ? "({$sr->description})":"";
            $totalFees += $sr->total;
            $feeName = ucwords(str_replace('-', ' ', $sr->feeDetail->fee->name));
            $pdf->Row([
                "{$sr->date->format('m/d/Y')}",
                "{$feeName} {$desc}",
                number_format($sr->total, 2),
                "","","",
            ]);

            foreach ($sr->chargeables as $key => $c) {
                $totalChargeableFees += $c->total;
                $feeName = ucwords(str_replace('-', ' ', $c->fee->name));
                $desc = ucwords($c->description);
                $pdf->Row([
                    "","","","",
                    "{$feeName}: {$desc}",
                    number_format($c->total,2),
                ]);
            }

            // $pdf->Cell($rectWidth, $this->cellHeight, '', 'T');
            $pdf->Ln(1);
        }

        $pdf->Ln(7);
        $pdf->SetFont('Arial', 'B', $this->fontSize - 2);
        $pdf->Row([
            "Total Fees","",
            number_format($totalFees, 2),
            "",
            "Total Chargeable Expenses",
            number_format($totalChargeableFees, 2),
        ]);

        $pdf->SetFont('Arial', '', $this->fontSize - 2);
        $pdf->Cell($rectWidth, $this->cellHeight, '', 'T');
        $pdf->Ln(7);

        $pdf->SetWidths([ 60,  40]);
        $pdf->SetAligns(['L', 'R']);

        $pdf->Row([
            "PF (Special Retainers)",
            number_format($latestBill->special, 2),
        ]);
        $pdf->Row([
            "PF (Excess General Retainer)",
            number_format($latestBill->general, 2),
        ]);

        $pdfContent = $pdf->Output("{$title}.pdf", 'S');

        $latestBill->pdf = base64_encode($pdfContent);
        $latestBill->save();


        return [
            'title' => $title,
            'content' => $pdfContent,
        ];
    }
}
