<?php

use Illuminate\Database\Seeder;
use App\Note;

class NoteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $texts = array(
            array(
                'Billing note',
                '<h4 style="color: rgb(103, 106, 108); margin-top: 5px;">Note:</h4><ol style="color: rgb(103, 106, 108);"><li>Bills are due and payable upon receipt.</li><li>Check payment should be payable to ATTY. PETER LEO M. RALLA.</li><li>Payment made after billing period is not included in this statement.</li><li>The Trust Fund appearing under item II, represents the deposits made to the firm to cover all chargeable expenses.</li><li>Kindly reimburse immediately the deficit amount reflected under the Trust Fund Balance and replenish your deposit account with the firm to cover future expenses. Thank you</li></ol>'
            ),
            array(
                'Billing footer note',
                '<p style="text-align: center; ">If you have any questions about this invoice, please contact.</p><p style="text-align: center; ">[ Name, Phone #, E-mail ]</p><h2 style="text-align: center; ">Thank You For Your Business!</h2>'
            ),
            array(
                'Checking info',
                '<p style="text-align: center; "><b>Atty. Peter Leo M. Ralla</b></p>'
            ),
            array(
                'Special Billing Notes',
                '<h4 style="color: rgb(103, 106, 108); margin-top: 5px;">Note:</h4><ol style="color: rgb(103, 106, 108);"><li>The above-stated professional fee(s) must be paid immediately upon receipt of this Special Billing Statement.</li><li>Check payment should be payable to ATTY. PETER LEO M. RALLA.</li></ol>'
            )
        );

        foreach ($texts as $text){
            $string = strtolower($text[0]); // Replaces all spaces with hyphens.
            $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
            $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
            $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

            $data = new Note();
            $data->name = $string;
            $data->display_name = $text[0];
            $data->description = $text[1];
            $data->save();
        }

    }
}
