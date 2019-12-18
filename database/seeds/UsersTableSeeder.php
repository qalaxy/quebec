<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$users = array(
					array('name'=>'Administrator','email'=>'admin@email.com', 'password'=>'12345678', 'status'=>1),
				);
		
		
		for($i = 0; $i < count($users); $i++){
			User::firstOrCreate(['email' => $users[$i]['email']],
			[
				'uuid' => Uuid::generate(),
				'name' => $users[$i]['name'],
				'email' => $users[$i]['email'],
				'password' => Hash::make($users[$i]['password']),
				'status' => $users[$i]['status'],
			]);
		}
    }
}
