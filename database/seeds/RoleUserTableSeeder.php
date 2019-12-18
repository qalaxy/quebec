<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = array(array('user'=>'Administrator', 'role'=>'super_admin'),
						array('user'=>'Korir', 'role'=>'system_admin'),
						array('user'=>'elias', 'role'=>'admin'),
						array('user'=>'John', 'role'=>'super_admin'),
		);
		
		$users = User::all();
		$roles = Role::all();
		
		for($i = 0; $i < count($role_user); $i++){
			foreach($users as $user){
				if($role_user[$i]['user'] == $user->name){
					foreach($roles as $role){
						if($role_user[$i]['role'] == $role->name){
							$user->role()->attach($role);
						}
					}
				}
			}
		}
    }
}
