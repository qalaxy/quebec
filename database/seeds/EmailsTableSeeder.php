<?php

use Illuminate\Database\Seeder;
use App\Email;

class EmailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emails = array(array('address'=>'ekorir@kcaa.or.ke'),
					array('address'=>'kibeteliask@yahoo.com'),
		);
		
		for($i = 0; $i < count($emails); $i++){
			Email::firstOrCreate(array('address'=>$emails[$i]['address']), 
					array('uuid'=>Uuid::generate(), 'address'=>$emails[$i]['address'])
				);
		}
    }
}
