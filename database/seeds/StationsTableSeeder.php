<?php

use Illuminate\Database\Seeder;
use App\Station;
use App\Office;

class StationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stations = array(array('name'=>'Headquarters', 'abbreviation'=>'HQ', 'office'=>'Headquarters'),
				array('name'=>'Jomo Kenyatta International Airport', 'abbreviation'=>'HKJK', 'office'=>'ARO & Briefing'),
				array('name'=>'Jomo Kenyatta International Airport NOF', 'abbreviation'=>'HKJK', 'office'=>'NOTAM'),
				array('name'=>'Nairobi Wilson Airport', 'abbreviation'=>'HKNW', 'office'=>'ARO & Briefing'),
				array('name'=>'Moi International Airport', 'abbreviation'=>'HKMO', 'office'=>'ARO & Briefing'),
				array('name'=>'Eldoret International Airport', 'abbreviation'=>'HKEL', 'office'=>'ARO & Briefing'),
				array('name'=>'Kisumu Airport', 'abbreviation'=>'HKKI', 'office'=>'ARO & Briefing'),
				array('name'=>'Malindi Airport', 'abbreviation'=>'HKML', 'office'=>'ARO & Briefing'),
				array('name'=>'Wajir Airport', 'abbreviation'=>'HKWJ', 'office'=>'ARO & Briefing'),
				array('name'=>'Lokochoggio Airport', 'abbreviation'=>'HKLK', 'office'=>'ARO & Briefing'),
				
		);
		
		$offices = Office::all();
		
		
		for($i = 0; $i < count($stations); $i++){
			foreach($offices as $office){
				if($office->name == $stations[$i]['office']){
					Station::firstOrCreate(array('name'=>$stations[$i]['name']), 
							array('uuid'=>Uuid::generate(),
								'name'=>$stations[$i]['name'],
								'abbreviation'=>$stations[$i]['abbreviation'],
								'office_id'=>$office->id,
							)
						);
				}
			}
		}
    }
}
