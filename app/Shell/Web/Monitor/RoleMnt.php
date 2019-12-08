<?php 
namespace App\Shell\Web\Monitor;

use Illuminate\Support\Facades\DB;
use App\Shell\Web\Executor\RoleExe;

class RoleMnt extends RoleExe{
	public function createRole(array $data, object $stations){
		$this->data = $data;
		
		DB::beginTransaction();
		$role = $this->storeRole();
		if(is_null($role)){
			DB::rollback();
			return $this->error;
		}
		
		foreach($stations as $station){
			$role_station = $this->storeRoleStation($role, $station);
			if(is_null($role_station)){
				DB::rollback();
				return $this->error;
			}
		}
		
		DB::commit();
		return $this->success;
	}
	
	public function editRole(array $data, $stations, $role){
		$this->data = $data;
		DB::beginTransaction();
		$role_update = $this->updateRole($role);
		if(is_null($role_update)){
			DB::rollback();
			return $this->error;
		}
		
		foreach($role->station()->get() as $role_station){
			foreach($stations as $station){
				if($role_station->id != $station->id){
					$role_stn = $this->deleteRoleStation($role, $role_station);
					if(is_null($role_stn)){
						DB::rollback();
						return $this->error;
					}
				}
			}
		}
		
		/*$role_station = $this->destroyRoleStations($role);
		if(is_null($role_station)){
			DB::rollback();
			return $this->error;
		}*/
		
		foreach($stations as $station){
			$role_station = $this->updateRoleStation($role, $station);
			if(is_null($role_station)){
				DB::rollback();
				return $this->error;
			}
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
		
		DB::commit();
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