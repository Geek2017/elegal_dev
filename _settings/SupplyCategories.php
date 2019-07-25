<?php 

namespace Settings;

class SupplyCategories {

	const CATEGORIES = [
        ['code' => 'P', 'title' => 'Papers'],
        ['code' => 'T', 'title' => 'Tape (Masking Tape)'],
        ['code' => 'F', 'title' => 'Folders and Envelopes'],
        ['code' => 'O', 'title' => 'Others'],
    ];


    public function findCetegory($code)
    {
    	foreach (self::CATEGORIES as $key => $c) {
    		if ($code == $c['code']) {
    			return $c;
    		}
    	}
    }
}
