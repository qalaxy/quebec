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
        $functions = array(array('name'=>'AIS Products Management', 'description'=>''),
						array('name'=>'Flight Planning Management', 'description'=>''),
						array('name'=>'Maps and Charts Management', 'description'=>''),
						array('name'=>'Terrain and Obstacle Data Management', 'description'=>''),
						array('name'=>'Instrument/Visual flight procedure design', 'description'=>''),
						array('name'=>'Technical library', 'description'=>''),
			);
			
		for($i = 0; $i < count($functions); $i++){
			Func::firstOrCreate(array('name'=>$functions[$i]['name']), 
								array('uuid'=>Uuid::generate(),
									'name'=>$functions[$i]['name'],
									'description'=>$functions[$i]['description'],
								));
		}
    }
}
