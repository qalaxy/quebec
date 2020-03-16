<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Level;
class PermTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perms = array(array('name'=>'view_permissions', 'display_name'=>'View permissions', 'description'=>'', 'level'=>'super_admin'),
					array('name'=>'create_permissions', 'display_name'=>'Create permissions', 'description'=>'', 'level'=>'super_admin'),
					array('name'=>'edit_permissions', 'display_name'=>'Edit permissions', 'description'=>'User can edit permissions', 'level'=>'super_admin'),
					array('name'=>'delete_permissions', 'display_name'=>'Delete permissions', 'description'=>'', 'level'=>'super_admin'),
					array('name'=>'view_roles', 'display_name'=>'View roles', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'create_roles', 'display_name'=>'Create roles', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'edit_roles', 'display_name'=>'Edit roles', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'delete_roles', 'display_name'=>'Delete roles', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'view_role_permissions', 'display_name'=>'View role permissions', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'add_role_permission', 'display_name'=>'Add role permissions', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'delete_role_permissions', 'display_name'=>'Delete role permissions', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'view_users', 'display_name'=>'View users accounts', 'description'=>'', 'level'=>'admin'),
					array('name'=>'create_users', 'display_name'=>'Create users account', 'description'=>'', 'level'=>'admin'),
					array('name'=>'edit_users', 'display_name'=>'Edit users account', 'description'=>'', 'level'=>'admin'),
					array('name'=>'delete_users', 'display_name'=>'Delete users account', 'description'=>'', 'level'=>'admin'),
					array('name'=>'view_errors', 'display_name'=>'View errors', 'description'=>'', 'level'=>'super_user'),
					array('name'=>'create_errors', 'display_name'=>'Create errors', 'description'=>'', 'level'=>'super_user'),
					array('name'=>'edit_errors', 'display_name'=>'Edit errors', 'description'=>'', 'level'=>'super_user'),
					array('name'=>'delete_errors', 'display_name'=>'Delete errors', 'description'=>'', 'level'=>'super_user'),
					array('name'=>'view_stations', 'display_name'=>'View stations', 'description'=>'', 'level'=>'admin'),
					array('name'=>'create_stations', 'display_name'=>'Create stations', 'description'=>'', 'level'=>'admin'),
					array('name'=>'edit_stations', 'display_name'=>'Edit stations', 'description'=>'', 'level'=>'admin'),
					array('name'=>'delete_stations', 'display_name'=>'Delete stations', 'description'=>'', 'level'=>'admin'),

					array('name'=>'view_products', 'display_name'=>'View Products', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'create_products', 'display_name'=>'Create Products', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'edit_products', 'display_name'=>'Edit Products', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'delete_products', 'display_name'=>'Delete Products', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'view_functions', 'display_name'=>'View functions  ', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'create_functions', 'display_name'=>'Create functions  ', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'edit_functions', 'display_name'=>'Edit functions  ', 'description'=>'', 'level'=>'system_admin'),
					array('name'=>'delete_functions', 'display_name'=>'Delete functions  ', 'description'=>'', 'level'=>'system_admin'),
		);
		$levels = Level::all();
		for($i = 0; $i < count($perms); $i++){
			foreach($levels as $level){
				if($perms[$i]['level'] == $level->name){
					Permission::firstOrCreate(array('name'=>$perms[$i]['name']),
						array('uuid' => Uuid::generate(),
								'name'=>$perms[$i]['name'],
								'display_name'=>$perms[$i]['display_name'],
								'description'=>$perms[$i]['description'],
								'level_id'=>$level->id,)
					);
				}
			}
			
		}
    }
}
