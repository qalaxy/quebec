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
					array('name'=>'perm_1', 'display_name'=>'Some permission', 'description'=>'Permission 1'),
					array('name'=>'perm_2', 'display_name'=>'Some permission', 'description'=>'Permission 2'),
					array('name'=>'perm_3', 'display_name'=>'Some permission', 'description'=>'Permission 3'),
					array('name'=>'perm_4', 'display_name'=>'Some permission', 'description'=>'Permission 4'),
					array('name'=>'perm_5', 'display_name'=>'Some permission', 'description'=>'Permission 5'),
					array('name'=>'perm_6', 'display_name'=>'Some permission', 'description'=>'Permission 6'),
					array('name'=>'perm_7', 'display_name'=>'Some permission', 'description'=>'Permission 7'),
					array('name'=>'view_roles', 'display_name'=>'View roles', 'description'=>''),
					array('name'=>'create_roles', 'display_name'=>'Create roles', 'description'=>''),
					array('name'=>'edit_roles', 'display_name'=>'Edit roles', 'description'=>''),
					array('name'=>'delete_roles', 'display_name'=>'Delete roles', 'description'=>''),
					array('name'=>'view_role_permissions', 'display_name'=>'View role permissions', 'description'=>''),
					array('name'=>'add_role_permission', 'display_name'=>'Add role permissions', 'description'=>''),
					array('name'=>'delete_role_permissions', 'display_name'=>'Delete role permissions', 'description'=>''),
					array('name'=>'view_users', 'display_name'=>'View users accounts', 'description'=>''),
					array('name'=>'create_users', 'display_name'=>'Create users account', 'description'=>''),
					array('name'=>'edit_users', 'display_name'=>'Edit users account', 'description'=>''),
					array('name'=>'delete_users', 'display_name'=>'Delete users account', 'description'=>''),
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
