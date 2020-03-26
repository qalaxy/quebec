<?php

use App\System;
use Illuminate\Database\Seeder;

class SystemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $systems = array(
        				array('name'=>'Cronos', 'abbreviation'=>'CRNS', 'description'=>'The FPL, PIB, NOTAM and MET system'),
        				array('name'=>'ATALIS', 'abbreviation'=>'ATLS', 'description'=>'The FPL, PIB, and NOTAM system. Now redundant'),
        				array('name'=>'eAIP', 'abbreviation'=>'EAIP', 'description'=>'The online AIP system'),
        				array('name'=>'Dynamic NAV', 'abbreviation'=>'DNAV', 'description'=>'For preparation of CRSF'),
        				array('name'=>'Advance Air Traffic Information System', 'abbreviation'=>'AATIS', 'description'=>'For aircraft clearances'),
    			);

        foreach($systems as $system){
        	System::create($system);
        }
    }
}
