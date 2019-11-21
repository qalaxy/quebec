<?php

use Illuminate\Database\Seeder;
use App\Func;
class FunctionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $functions = array(array('name'=>'AIS Products Management', 'abbreviation'=>'AIMP', 'description'=>''),
						array('name'=>'Flight Planning Management', 'abbreviation'=>'FPL', 'description'=>''),
						array('name'=>'Maps and Charts Management', 'abbreviation'=>'MC', 'description'=>''),
						array('name'=>'Terrain and Obstacle Data Management', 'abbreviation'=>'TOD', 'description'=>''),
						array('name'=>'Instrument/Visual flight procedure design', 'abbreviation'=>'FPD', 'description'=>''),
						array('name'=>'Technical library', 'abbreviation'=>'TL', 'description'=>''),
			);
			
		for($i = 0; $i < count($functions); $i++){
			Func::firstOrCreate(array('name'=>$functions[$i]['name']), 
								array('uuid'=>Uuid::generate(),
									'name'=>$functions[$i]['name'],
									'abbreviation'=>$functions[$i]['abbreviation'],
									'description'=>$functions[$i]['description'],
								));
		}
    }
}
