<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perms = array(array('name'=>'view_permissions', 'display_name'=>'View permissions', 'description'=>''),
					array('name'=>'create_permissions', 'display_name'=>'Create permissions', 'description'=>''),
					array('name'=>'edit_permissions', 'display_name'=>'Edit permissions', 'description'=>'User can edit permissions'),
					array('name'=>'delete_permissions', 'display_name'=>'Delete permissions', 'description'=>''),
		);
		
		for($i = 0; $i < count($perms); $i++){
			Permission::firstOrCreate(array('name'=>$perms[$i]['name']),
						array('uuid' => Uuid::generate(),
								'name'=>$perms[$i]['name'],
								'display_name'=>$perms[$i]['display_name'],
								'description'=>$perms[$i]['description'],)
					);
		}
    }
}
