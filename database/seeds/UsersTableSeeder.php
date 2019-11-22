<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Level;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$users = array(array('name'=>'Elias','email'=>'ekorir@kcaa.or.ke', 'password'=>'12345678', 'status'=>1, 'level'=>'system_admin'),
					array('name'=>'Kibet','email'=>'kibeliask@gmail.com', 'password'=>'12345678', 'status'=>1, 'level'=>'super_admin'),
					array('name'=>'Korir','email'=>'kibeteliask@yahoo.com', 'password'=>'12345678', 'status'=>1, 'level'=>'super_admin'),
					array('name'=>'John','email'=>'jnjoroge@kcaa.or.ke', 'password'=>'12345678', 'status'=>1, 'level'=>'super_admin'),
				);
		
		$levels = Level::all();
		
		for($i = 0; $i < count($users); $i++){
			foreach($levels as $level){
				if($users[$i]['level'] == $level->name){
					User::firstOrCreate(['email' => $users[$i]['email']],
					[
						'uuid' => Uuid::generate(),
						'name' => $users[$i]['name'],
						'email' => $users[$i]['email'],
						'password' => Hash::make($users[$i]['password']),
						'status' => $users[$i]['status'],
						'level_id' => $level->id,
					]);
				}
			}
		}
    }
}
