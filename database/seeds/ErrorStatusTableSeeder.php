<?php

use Illuminate\Database\Seeder;
//use Uuid;
use App\ErrorStatus;
class ErrorStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = array(array('name'=>'created', 'code'=>1, 'description'=>''),
				array('name'=>'seen', 'code'=>2, 'description'=>''),
				array('name'=>'acted', 'code'=>3, 'description'=>''),
				array('name'=>'rejected', 'code'=>4, 'description'=>''),
				array('name'=>'redirected', 'code'=>5, 'description'=>''),
				array('name'=>'pending', 'code'=>6, 'description'=>''),
		);
		
		for($i = 0; $i < count($status); $i++){
			ErrorStatus::firstOrCreate(array('name'=>$status[$i]['name']), 
							array('uuid'=>Uuid::generate(),
								'name'=>$status[$i]['name'],
								'code'=>$status[$i]['code'],
								'description'=>$status[$i]['description'],
							)
						);
		}
    }
}
