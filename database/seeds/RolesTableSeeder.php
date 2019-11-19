<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Level;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(array('name'=>'super_admin', 'display_name'=>'Super Admin', 'description'=>'', 'level'=>'super_admin'),
				array('name'=>'system_admin', 'display_name'=>'System Admin', 'description'=>'', 'level'=>'system_admin'),
				array('name'=>'admin', 'display_name'=>'Admin', 'description'=>'', 'level'=>'admin'),
				array('name'=>'user', 'display_name'=>'System user', 'description'=>'', 'level'=>'user'),
		
		);
		
		$levels = Level::all();
		
		for($i = 0; $i < count($roles); $i++){
			foreach($levels as $level){
				if($roles[$i]['level'] == $level->name){
					Role::firstOrCreate(array('name'=>$roles[$i]['name']),
						array('uuid' => Uuid::generate(),
								'name'=>$roles[$i]['name'], 
								'display_name'=>$roles[$i]['display_name'], 
								'description'=>$roles[$i]['description'],
								'level_id'=>$level->id,)
						);
				}
			}
		}
    }
}
