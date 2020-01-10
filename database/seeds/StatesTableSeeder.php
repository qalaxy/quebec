<?php

use Illuminate\Database\Seeder;

use App\State;
class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = array(array('name'=>'Captured', 'code'=>1, 'description'=>''),
				array('name'=>'Rejected', 'code'=>2, 'description'=>''),
				array('name'=>'Pending', 'code'=>3, 'description'=>''),
				array('name'=>'Closed', 'code'=>4, 'description'=>''),
		);
		
		for($i = 0; $i < count($states); $i++){
			State::firstOrCreate(array('name'=>$states[$i]['name']), 
							array('uuid'=>Uuid::generate(),
								'name'=>$states[$i]['name'],
								'code'=>$states[$i]['code'],
								'description'=>$states[$i]['description'],
							)
						);
		}
    }
}
