<?php 
namespace App\Shell\Web\Executor;

use Exception;
use App\Role;
use App\RoleStation;
use App\PermissionRole;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Shell\Web\Base;
use App\Shell\Data\RoleData;
use Uuid;

class RoleExe extends Base{
	private $role_data;
	protected $data = array();
	
	public function __construct(){
		$this->role_data = new RoleData();
	}
	
	protected function storeRole(){
		try{
			$role = Role::firstOrCreate(array($this->role_data->display_name_key => $this->data[$this->role_data->name_key]),
								array('uuid' => Uuid::generate(),
									$this->role_data->name_key => Str::snake($this->data[$this->role_data->name_key]),
									$this->role_data->display_name_key => $this->data[$this->role_data->name_key],
									$this->role_data->description_key => $this->data[$this->role_data->description_key],
									$this->role_data->global_key => ($this->data[$this->role_data->global_key] == 3)? 1: 0,
									$this->role_data->owner_id_key => Auth::id(),
							));
			if(is_null($role)){
				throw new Exception('Role has not been created successfully');
			}
			else{
				$this->success = array('indicator'=>'success', 'message'=>'Role has been created successfully', 'uuid'=>$role->uuid);
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $role;
	}
	
	protected function storeRoleStation(Role $role, Station $station){
		try{
			$role_station = $role->station()->attach($station->id);
			if($role_station){
				throw new Exception('Role has not been attached to a station successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $role;
	}
	
	protected function updateRole($role){
		try{
			$role = $role->update(array(
									$this->role_data->name_key => Str::snake($this->data[$this->role_data->name_key]),
									$this->role_data->display_name_key => $this->data[$this->role_data->name_key],
									$this->role_data->description_key => $this->data[$this->role_data->description_key],
									$this->role_data->global_key => ($this->data[$this->role_data->global_key] == 3)? 1: 0,
									$this->role_data->owner_id_key => Auth::id(),
							));
					
			if(is_null($role)){
				throw new Exception('Role has not been updated successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Role has been updated successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=> $e->getMessage());
			return null;
		}
		return $role;
	}
	
	protected function destroyRoleStations($role){
		try{
			$role_station = RoleStation::where('role_id', $role->id)->delete();
			if(is_null($role_station)){
				throw new Exception('Roles have not been detached sfrom stations successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $role_station;
	}
	
	protected function deleteRoleStation($role, $station){
		try{
			$role_stn = $role->station()->detach($station);
			if(is_null($role_stn)){
				throw new Exception('Station has not been detached from role successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $role_stn;
	}
	
	protected function updateRoleStation($role, $station){
		try{
			$role_station = RoleStation::where('role_id', $role->id)->where('station_id', $station->id)->first();
			if(!$role_station){
				if($role->station()->attach($station->id)){
					throw new Exception('Role has not been attached to station successfully');
				}
			}				
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $role;
	}
	
	protected function destroyRole(Role $role){
		try{
			$role = $role->delete();
			if(is_null($role)){
				throw new Exception('Role has not been deleted successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Role has been deleted successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $role;
	}
	
	protected function storeRolePerm(Role $role, Permission $permission){
		try{
			if($role->permission()->attach($permission)){
				throw new Exception('Permission has not been added to the role successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Permission has been added to the role successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $role;
	}
	
	protected function destroyRolePerm(Role $role, Permission $perm){
		try{
			$role_perm = $role->permission()->detach($perm);
			if(is_null($role_perm)){
				throw new Exception('Permission for the role '.$role->display_name.' has not been deleted successfully'.$role_perm);
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Permission has been deleted successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $role_perm;
	}
}
?>