<?php 
namespace App\Shell\Web\Extension;

use Exception;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\Validator;

use App\Shell\Web\Base;
use App\Shell\Data\RoleData;

class RoleExt extends Base{
	private $role_data;
	
	public function __construct(){
		$this->role_data = new RoleData();
	}
	
	public function searchRoles(array $data){
		try{
			$roles = Role::where($this->prepareSearchParam($data, ['name', 'display_name']))
							->paginate($this->role_data->rows);
			if(is_null($roles)){
				throw new Exception('Roles have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $roles; 
	}
	
	public function getPaginatedRoles(){
		try{
			$roles = Role::paginate($this->role_data->rows);
			if(is_null($roles)){
				throw new Exception('Roles have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $roles;
	}
	
	public function validateRoleData(array $data){
		$rules = [
			$this->role_data->name_key => $this->role_data->name_req,
			$this->role_data->display_name_key => $this->role_data->display_name_req,
			$this->role_data->description_key => $this->role_data->description_req
		];
		
		return Validator::make($data, $rules, $this->role_data->validationMsgs);
	}
	
	public function getRole($uuid){
		try{
			$role = Role::withUuid($uuid)->first();
			if(is_null($role)){
				throw new Exception('Role has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $role;
	}
	
	public function deleteRole(object $role){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Delete role</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-8 w3-large">Your are about to delete a role:</p>
						<p><strong>Name:</strong> '.$role->display_name.'<br /> '.((strlen($role->description) < 1) ? '' : '<br /><strong>Description:</strong> '.$role->description).'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-role').'/'.$role->uuid.'" title="Delete role">Delete&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}
	
	public function searchRolePermissions(array $data, $role){
		try{
			$permissions = $role->permission()->where($this->prepareSearchParam($data, ['name', 'display_name']))->paginate($this->role_data->rows);
			if(is_null($permissions)){
				throw new Exception('Roles could not be retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $permissions;
	}
	
	public function getPaginatedRolePermissions($role){
		try{
			$permissions = $role->permission()->paginate($this->role_data->rows);
			if(is_null($permissions)){
				throw new Exception('Roles could not be retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $permissions;
	}
	
	public function getPermissions(){
		try{
			$permissions = Permission::all();
			if(is_null($permissions)){
				throw new Exception('Permissions have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $permissions;
	}
	
	public function getPermission($uuid){
		try{
			$permissions = Permission::withUuid($uuid)->first();
			if(is_null($permissions)){
				throw new Exception('Permission have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $permissions;
	}
	
	public function validateRolePermData(array $data){
		$rules = [$this->role_data->permission_key => $this->role_data->permission_req];
		
		return Validator::make($data, $rules, $this->role_data->rolePermValidationMsgs);
	}
	
}

?>