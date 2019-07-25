<?php

use Illuminate\Database\Seeder;
use App\FeeCategory;
use App\Fee;
use App\FeeDescription;

class FeeCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lists = array(
            array('Special', array(
                array('Acceptance Fee', null),
                array('Acceptance Fee / Initial Fee', null),
                array('Appearance Fee', array(
                    array('Arraignment', 0, null),
                    array('Hearing', 0, null),
                    array('Mandatory', 0, null),
                    array('Pre-trial', 0, null),
                    array('Judicial Dispute Resolution', 0, null),
                    array('Preliminary', 0, null),
                    array('Trial', 0, null),
                )),
                array('Documentation (Page) Fee', null),
                array('Contingent Fee', null),
                array('Success Fee', null),
                array('Other Fee', null),
            )),
            array('General', array(
                array('Assistance to Successfully Defeat TRO', null),
                array('Monthly Retainer', null),
                array('PF - Court of Appeals', null),
                array('PF - Supreme Court', null),
                array('Project Fee', null),
                array('Settlement Fee', null),
            )),
            array('Special and General', array(
                array('Annotation of Certificate of Sale', null),
                array('Appearance Fee: Arraignment', null),
                array('Commission', null),
                array('Completion Fee', null),
                array('Completion: Compromise Agreement', null),
                array('Contingent Fee', null),
                array('Documentation (Responsive Pleading)', null),
                array('Documentation (Time) Fee', null),
                array('Incentive Fee: Case Dismissal', null),
                array('Merger Execution', null),
                array('Miscellaneous Fees', null),
                array('Notarial Fee', array(
                    array('1', 100, null),
                    array('2', 250, null),
                    array('3', 350, null),
                    array('4', 500, null),
                )),
                array('One Time Fee', null),
                array('Paralegal Charge', array(
                    array('Assist Lawyer', 0, null),
                    array('Courier', 0, null),
                    array('Delivery', 0, null),
                    array('Document Copying / Sorting', 0, null),
                    array('Document Organizing', 0, null),
                    array('Fax / Scan', 0, null),
                    array('Follow - up', 0, null),
                    array('Inquiry', 0, null),
                    array('Investigation', 0, null),
                    array('Mailing', 0, null),
                    array('Mailing / Courier', 0, null),
                    array('Others', 0, null),
                    array('Pickup', 0, null),
                    array('Research', 0, null),
                    array('Service / Filing', 0, null),
                    array('Transaction', 0, null),
                )),
                array('Sale Completion', null),
                array('Time Service Fee', array(
                    array('Conference / Meeting', 0, 'Advice'),
                    array('Conference / Meeting', 0, 'Briefing'),
                    array('Conference / Meeting', 0, 'Interview'),
                    array('Conference / Meeting', 0, 'Negotiation'),
                    array('Conference / Meeting', 0, 'Transaction'),
                    array('Conference / Meeting', 0, '[M] Advice'),
                    array('Conference / Meeting', 0, '[M] Briefing'),
                    array('Conference / Meeting', 0, '[M] Interview'),
                    array('Conference / Meeting', 0, '[M] Negotiation'),
                    array('Conference / Meeting', 0, '[T] Advice'),
                    array('Conference / Meeting', 0, '[T] Briefing'),
                    array('Conference / Meeting', 0, '[T] Follow-up'),
                    array('Conference / Meeting', 0, '[T] Interview'),
                    array('Conference / Meeting', 0, '[T] Negotiation'),
                    array('Conference / Meeting', 0, '[T] Transaction'),
                    array('Documentation (Time)', 0, null),
                    array('Documentation (page)', 0, null),
                    array('Fieldwork', 0, null),
                    array('Others', 0, null),
                    array('Study', 0, 'Computation'),
                    array('Study', 0, 'Evaluation of Documents Presented'),
                    array('Study', 0, 'Evaluation (Subject Matter)'),
                    array('Study', 0, 'Examine (Data, Facts or Information Studied)'),
                    array('Study', 0, 'Research'),
                    array('Study', 0, 'Review'),
                    array('Study', 0, 'Revision'),
                    array('Study', 0, 'Trial Preparation'),
                )),
            )),
            array('Chargeable Expense', array(
                array('Communications Expenses', null),
                array('Computer Printing', null),
                array('Copying Charge', array(
                    array('Blue Print', 0, null),
                    array('Colored Printing', 0, null),
                    array('Dox (double side)', 0, null),
                    array('Dox (single side)', 0, null),
                    array('ID picture', 0, null),
                    array('Photo Printing', 0, null),
                    array('Picture / Evidence', 0, null),
                    array('Reduce', 0, null),
                    array('Subdivision Plan', 0, null),
                    array('Technical Plans', 0, null),
                    array('White Print', 0, null),
                )),
                array('Courier Charge', array(
                    array('LBC', 0, null),
                    array('JRS', 0, null),
                    array('2GO', 0, null),
                )),
                array('E-Load', null),
                array('Expenses', array(
                    array('Expenses', 0, 'Audio Recording'),
                    array('Expenses', 0, 'Cargo Transport'),
                    array('Expenses', 0, 'Internet Services'),
                    array('Expenses', 0, 'Meals'),
                    array('Expenses', 0, 'Miscellaneous Services'),
                    array('Expenses', 0, 'Publication'),
                    array('Expenses', 0, 'Reservation Fee'),
                    array('Expenses', 0, 'Security Services'),
                    array('Expenses', 0, 'Welding Services'),
                    array('Expenses', 0, 'Lamination Fee'),
                )),
                array('Gasoline Allowance', null),
                array('Legal Fee', array(
                    array('Barangay Clearance', 0, null),
                    array('Business Plate', 0, null),
                    array('Capital Gains Tax', 0, null),
                    array('Lot Plan', 0, null),
                    array('Police Clearance', 0, null),
                    array('SEC Name Reservation', 0, null),
                    array('SEC Registration Fee', 0, null),
                    array('Administration Oath Fee', 0, null),
                    array('Alien Permit', 0, null),
                    array('Annotation [ROD]', 0, null),
                    array('Annotation Fee', 0, null),
                    array('Appeal Fee', 0, null),
                    array('Birth Certificate', 0, null),
                    array('Cadastral Map', 0, null),
                    array('Certificate of Finality', 0, null),
                    array('Certificate of Land holdings', 0, null),
                    array('Certification Fee', 0, null),
                    array('Certification Fee & Documentary Stamp', 0, null),
                    array('Certified Copy', 0, null),
                    array('Certified Copy TCT', 0, null),
                    array('Certified Copy TD', 0, null),
                    array('Clearance', 0, null),
                    array('Community Tax Certificate', 0, null),
                    array('Death Certificate', 0, null),
                    array('Deposit Fee', 0, null),
                    array('Documentary Stamp', 0, null),
                    array('Docket Fee', 0, null),
                    array('Documentary Stamp Tax', 0, null),
                    array('Documentation', 0, null),
                    array('Donor\'s Tax', 0, null),
                    array('Entry Fee', 0, null),
                    array('Entry of Judgement', 0, null),
                    array('Estate Tax', 0, null),
                    array('Execution Fee', 0, null),
                    array('Fire Safety Inspection Fee', 0, null),
                    array('Lis Pendens', 0, null),
                    array('Locational Fee', 0, null),
                    array('Mediation Fee', 0, null),
                    array('Mortgage Cancellation', 0, null),
                    array('Notarial Fee', 0, null),
                    array('NSO Fee', 0, null),
                    array('Penalty Chargy', 0, null),
                    array('Permit Fee', 0, null),
                    array('Postponement Fee', 0, null),
                    array('Processing Fee', 0, null),
                    array('Real Estate Tax', 0, null),
                    array('Registration Fee', 0, null),
                    array('Research Fee', 0, null),
                    array('Service Fee', 0, null),
                    array('Sheriff\'s Fee', 0, null),
                    array('Tax Clearance', 0, null),
                    array('Tax Declaration', 0, null),
                    array('Tax Declaration & Certification', 0, null),
                    array('Title Registration', 0, null),
                    array('Transfer Fee', 0, null),
                    array('Transfer Tax', 0, null),
                    array('TSN', 0, null),
                    array('Verification Fee', 0, null),
                    array('Writ of Execution', 0, null),
                    array('LTO Fee', 0, null)
                )),
                array('Medical Certificate', null),
                array('Office Stationery', null),
                array('Others', null),
                array('Photocopy', null),
                array('Photocopying [ Annexes / Exhibits ]', null),
                array('Postage', null),
                array('Postage & Transportation', null),
                array('Postal Charge', array(
                    array('Cargo', 0, null),
                    array('PMO', 0, null),
                    array('PMO Validation', 0, null)
                )),
                array('Processing Fee', array(
                    array('Business Permit', 0, null)
                )),
                array('Professional Fee', array(
                    array('Appraisal', 0, null),
                    array('Consultant', 0, null),
                    array('Notary Public', 0, null),
                    array('Stenographer', 0, null)
                )),
                array('Representation Expenses', null),
                array('Supplies', array(
                    array('Bond Paper (L)', 0, null),
                    array('Bond Paper (S)', 0, null),
                    array('Bond Paper (A4)', 0, null),
                    array('Bond Paper (3pcs)', 0, null),
                    array('Battery AA', 0, null),
                    array('Battery AAA', 0, null),
                    array('Binder', 0, null),
                    array('Binder Clip (S)', 0, null),
                    array('Binder Clip (L)', 0, null),
                    array('Clear Sheet Protector', 0, null),
                    array('Clear Book', 0, null),
                    array('Columnar Book', 0, null),
                    array('Compact Disc', 0, null),
                    array('Dox Envelope (Brown)', 0, null),
                    array('Dox Envelope (Expanding)', 0, null),
                    array('Dox Envelope (Plastic)', 0, null),
                    array('Dox Envelope (Plain)', 0, null),
                    array('Dox Envelope (Stationery)', 0, null),
                    array('Folder', 0, null),
                    array('Folder [Binding cover]', 0, null),
                    array('Folder [Legal size]', 0, null),
                    array('Folder [Short]', 0, null),
                    array('Office Stationery', 0, null),
                    array('Packing Tape', 0, null),
                    array('Ring Binder', 0, null),
                    array('Stamp Pad', 0, null),
                    array('Thermal Paper', 0, null),
                    array('Printer Drum', 0, null),
                    array('Printer Toner', 0, null),
                    array('Printer Ink', 0, null)
                )),
                array('Telephone Tolls', array(
                    array('Mobile', 0, null)
                )),
                array('TMG Clearance', 0, null),
                array('Transportation', array(
                    array('Bill of Lading', 0, null),
                    array('Fare', 0, null),
                    array('Gasoline', 0, null),
                    array('Parking Fee', 0, null),
                    array('PUV Fare', 0, null),
                    array('Taxi Fare', 0, null)
                )),
                array('Travel Allowance', null),
                array('Travel Expense', array(
                    array('Road Toll', 0, null),
                    array('Parking', 0, null),
                    array('Air Fare', 0, null),
                    array('Boat Fare', 0, null),
                    array('Bus Fare', 0, null),
                    array('Gasoline', 0, null),
                    array('Inland Transportation', 0, null),
                    array('Lodging', 0, null),
                    array('Meals', 0, null),
                    array('Miscellaneous', 0, null),
                    array('Terminal Fee', 0, null),
                    array('Ticket Re-booking', 0, null),
                    array('Vehicle Rental', 0, null)
                )),
            ))
        );

        foreach ($lists as $list){
            $string = strtolower($list[0]); // Replaces all spaces with hyphens.
            $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
            $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
            $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

            $data = new FeeCategory();
            $data->name = $string;
            $data->display_name = $list[0];
            if($data->save()){
                foreach ($list[1] as $fee){
                    $string = strtolower($fee[0]); // Replaces all spaces with hyphens.
                    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
                    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
                    $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
                    $count = Fee::count();
                    $count += 1;
                    $data1 = new Fee();
                    $data1->category_id = $data->id;
                    $data1->name = $string;
                    $data1->display_name = $fee[0];
                    $data1->code = str_pad($count, 3, '0', STR_PAD_LEFT);
                    if($data1->save()){
                        if($fee[1] != null){
                            foreach ($fee[1] as $desc){
                                $string = strtolower($desc[0]); // Replaces all spaces with hyphens.
                                $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
                                $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
                                $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

                                $data2 = new FeeDescription();
                                $data2->fee_id = $data1->id;
                                $data2->name = $string;
                                $data2->display_name = $desc[0];
                                $data2->description = $desc[2];
                                $data2->default_amount = $desc[1];
                                $data2->save();
                            }
                        }
                    }

                }
            }
        }

    }
}
