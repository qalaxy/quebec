<?php 
namespace App\Shell\Web\Executor;

use Exception;
use App\Role;

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
			$role = Role::firstOrCreate(array('uuid' => Uuid::generate(),
									$this->role_data->name_key => $this->data[$this->role_data->name_key],
									$this->role_data->display_name_key => $this->data[$this->role_data->display_name_key],
									$this->role_data->description_key => $this->data[$this->role_data->description_key],
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
	
	protected function updateRole($uuid){
		try{
			$role = Role::where('uuid',$uuid)->update([$this->role_data->name_key => $this->data[$this->role_data->name_key],
							$this->role_data->display_name_key => $this->data[$this->role_data->display_name_key],
							$this->role_data->description_key => $this->data[$this->role_data->description_key],
					]);
					
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
	
	protected function destroyRole($role){
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
	
	protected function storeRolePerm($role, $permission){
		try{
			$role->permission()->attach($permission);
			$perm = $role->permission()->where($permission->id)->first();
			
			if($permission != $perm){
				throw new Exception('Permission has been added to the role successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Permission has been added to the role successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		$perm;
	}
	
}
?>