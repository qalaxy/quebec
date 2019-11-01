<?php

use Illuminate\Database\Seeder;
use App\User;
//use Uuid;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$users = array(array('name'=>'Elias','email'=>'elias@email', 'password'=>'12345678'),
					array('name'=>'Jared','email'=>'jared@email', 'password'=>'12345678'),
					array('name'=>'Seth','email'=>'seth@email', 'password'=>'12345678'),
					array('name'=>'Kibet','email'=>'kibeliask@gmail.com', 'password'=>'12345678'),
					array('name'=>'Korir','email'=>'ekorir@kcaa.or.ke', 'password'=>'12345678'),
				);
				
		for($i = 0; $i < count($users); $i++){
			User::firstOrCreate(['email' => $users[$i]['email']],
			[
				'uuid' => Uuid::generate(),
				'name' => $users[$i]['name'],
				'email' => $users[$i]['email'],
				'password' => Hash::make($users[$i]['password']),
			]);
		}
        
    }
}
