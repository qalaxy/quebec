<?php

use Illuminate\Database\Seeder;
use App\Email;
use App\Station;

class StationEmailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stn_emails = array(array('name'=>'Headquarters', 'email'=>'ekorir@kcaa.or.ke'),
					//array('name'=>'Malindi Airport', 'email'=>'kibeteliask@yahoo.com')
				);
        
		$stations = Station::all();
		$emails = Email::all();
		
		for($i = 0; $i < count($stn_emails); $i++){
			foreach($stations as $station){
				if($stn_emails[$i]['name'] == $station->name){
					foreach($emails as $email){
						if($stn_emails[$i]['email'] == $email->address){
							$station->email()->attach($email);
						}
					}
				}
			}
		}
    }
}
