<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;

class RolePermTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $role_perm = array(array('role'=>'super_admin', 'perm'=>'view_permissions'),
						array('role'=>'super_admin', 'perm'=>'create_permissions'),
						array('role'=>'super_admin', 'perm'=>'edit_permissions'),
						array('role'=>'super_admin', 'perm'=>'delete_permissions'),
						array('role'=>'super_admin', 'perm'=>'view_roles'),
						array('role'=>'super_admin', 'perm'=>'create_roles'),
						array('role'=>'super_admin', 'perm'=>'edit_roles'),
						array('role'=>'super_admin', 'perm'=>'delete_roles'),
						array('role'=>'system_admin', 'perm'=>'view_roles'),
						array('role'=>'system_admin', 'perm'=>'create_roles'),
						array('role'=>'system_admin', 'perm'=>'edit_roles'),
						array('role'=>'system_admin', 'perm'=>'delete_roles'),
						array('role'=>'admin', 'perm'=>'view_roles'),
						array('role'=>'super_admin', 'perm'=>'view_role_permissions'),
						array('role'=>'super_admin', 'perm'=>'add_role_permission'),
						array('role'=>'super_admin', 'perm'=>'delete_role_permissions'),
						array('role'=>'super_admin', 'perm'=>'view_users'),
						array('role'=>'super_admin', 'perm'=>'create_users'),
						array('role'=>'super_admin', 'perm'=>'edit_users'),
						array('role'=>'super_admin', 'perm'=>'delete_users'),
						
						array('role'=>'super_admin', 'perm'=>'view_errors'),
						array('role'=>'super_admin', 'perm'=>'create_errors'),
						array('role'=>'super_admin', 'perm'=>'edit_errors'),
						array('role'=>'super_admin', 'perm'=>'delete_errors'),
						
						array('role'=>'super_admin', 'perm'=>'view_stations'),
						array('role'=>'super_admin', 'perm'=>'create_stations'),
						array('role'=>'super_admin', 'perm'=>'edit_stations'),
						array('role'=>'super_admin', 'perm'=>'delete_stations'),
						
						array('role'=>'super_admin', 'perm'=>'view_products'),
						array('role'=>'super_admin', 'perm'=>'create_products'),
						array('role'=>'super_admin', 'perm'=>'edit_products'),
						array('role'=>'super_admin', 'perm'=>'delete_products'),

						
						array('role'=>'super_admin', 'perm'=>'view_functions'),
						array('role'=>'super_admin', 'perm'=>'create_functions'),
						array('role'=>'super_admin', 'perm'=>'edit_functions'),
						array('role'=>'super_admin', 'perm'=>'delete_functions'),
	   ); 
	   $roles = Role::all();
	   $perms = Permission::all();
	   for($i = 0; $i < count($role_perm); $i++){
		   foreach($roles as $role){
			   if($role_perm[$i]['role'] == $role->name){
				   foreach($perms as $perm){
					   if($role_perm[$i]['perm'] == $perm->name){
						   $role->attachPermission($perm);
					   }
				   }
			   }
		   }
	   }
    }
}
