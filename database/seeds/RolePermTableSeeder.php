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
