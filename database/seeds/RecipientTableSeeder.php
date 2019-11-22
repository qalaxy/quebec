<?php

use Illuminate\Database\Seeder;

use App\Recipient;
use App\Station;
use App\User;

class RecipientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recipients = array(array('station'=>'Headquarters', 'user'=>'Korir'));
		
		$stations = Station::all();
		$users = User::all();
		
		for($i = 0; $i < count($recipients); $i++){
			foreach($stations as $station){
				if($recipients[$i]['station'] == $station->name){
					foreach($users as $user){
						if($recipients[$i]['user'] == $user->name){
							Recipient::firstOrCreate(array('user_id'=>$user->id, 'station_id'=>$station->id), 
										array('uuid'=>Uuid::generate(), 'user_id'=>$user->id, 'station_id'=>$station->id));
						}
					}
				}
			}
		}
    }
}
