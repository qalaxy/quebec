<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(array('name'=>'super_admin', 'display_name'=>'Super Admin', 'description'=>''),
				array('name'=>'system_admin', 'display_name'=>'System Admin', 'description'=>''),
				array('name'=>'admin', 'display_name'=>'Admin', 'description'=>''),
				array('name'=>'user', 'display_name'=>'System user', 'description'=>''),
		
		);
		
		for($i = 0; $i < count($roles); $i++){
			Role::firstOrCreate(array('name'=>$roles[$i]['name']),
					array('uuid' => Uuid::generate(),
							'name'=>$roles[$i]['name'], 
							'display_name'=>$roles[$i]['display_name'], 
							'description'=>$roles[$i]['description'])
					);
		}
    }
}
