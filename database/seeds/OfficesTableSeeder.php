<?php

use Illuminate\Database\Seeder;
use App\Office;

class OfficesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $offices = array(array('name'=>'Headquarters', 'description'=>''),
				array('name'=>'ARO & Briefing', 'description'=>''),
				array('name'=>'NOTAM', 'description'=>''),
		);
		
		for($i = 0; $i < count($offices); $i++){
			Office::firstOrCreate(array('name'=>$offices[$i]['name']), 
							array('name'=>$offices[$i]['name'],
								'description'=>$offices[$i]['description'],
							)
						);
		}
    }
}
