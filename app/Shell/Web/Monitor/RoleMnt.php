<?php 
namespace App\Shell\Web\Monitor;

use Illuminate\Support\Facades\DB;
use App\Shell\Web\Executor\RoleExe;

class RoleMnt extends RoleExe{
	public function createRole(array $data){
		$this->data = $data;
		
		DB::beginTransaction();
		$role = $this->storeRole();
		if(is_null($role)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function editRole(array $data, $uuid){
		$this->data = $data;
		DB::beginTransaction();
		$role = $this->updateRole($uuid);
		if(is_null($role)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function deleteRole(object $role){
		DB::beginTransaction();
		$role = $this->destroyRole($role);
		if(is_null($role)){
			DB::rollback();
			return $this->error;
		}
		
		DB::rollback();
		return $this->success;
	}
	
	public function addRolePerm($role, $permission){
		DB::beginTransaction();
		$role_perm = $this->storeRolePerm($role, $permission);
		if(is_null($role_perm)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function deleteRolePermission($role, $permission){
		DB::beginTransaction();
		$role_perm = $this->destroyRolePerm($role, $permission);
		if(is_null($role_perm)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
}

?>