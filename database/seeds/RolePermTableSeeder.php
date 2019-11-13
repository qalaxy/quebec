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
	   ); 
	   //role e18b3170-045b-11ea-8128-1996de07c7a6, perm def10b50-046b-11ea-8926-5b9fa0c0bb69
	   //http://127.0.0.1/quebec/delete-role-permission/e1555460-045b-11ea-b69d-d3ec56a6c0c2/e18b3170-045b-11ea-8128-1996de07c7a6
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
