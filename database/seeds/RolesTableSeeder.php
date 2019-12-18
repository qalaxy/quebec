<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(array('name'=>'super_admin', 'display_name'=>'Super Admin', 'description'=>'', 'global'=>'1', 'owner'=>'Administrator'),
				array('name'=>'system_admin', 'display_name'=>'System Admin', 'description'=>'', 'global'=>'1', 'owner'=>'Administrator'),
				array('name'=>'admin', 'display_name'=>'Admin', 'description'=>'', 'global'=>'1', 'owner'=>'Administrator'),
				array('name'=>'user', 'display_name'=>'System user', 'description'=>'', 'global'=>'1', 'owner'=>'Administrator'),
		
		);
		$users = User::all();
		
		for($i = 0; $i < count($roles); $i++){
			foreach($users as $user){
				if($roles[$i]['owner'] == $user->name){
					Role::firstOrCreate(array('name'=>$roles[$i]['name']),
					array('uuid' => Uuid::generate(),
							'name'=>$roles[$i]['name'], 
							'display_name'=>$roles[$i]['display_name'], 
							'description'=>$roles[$i]['description'],
							'global'=>$roles[$i]['global'],
							'owner_id'=>$user->id,)
					);
				}
			}
		}
    }
}
