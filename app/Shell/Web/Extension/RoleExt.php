<?php 
namespace App\Shell\Web\Extension;

use Exception;
use App\Role;
use App\Station;
use App\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Shell\Web\Base;
use App\Shell\Data\RoleData;

class RoleExt extends Base{
	private $role_data;
	
	public function __construct(){
		$this->role_data = new RoleData();
	}
	
	public function searchRoles(array $data){
		try{
			$roles = Role::where('owner_id',Auth::id())
							->where($this->prepareSearchParam($data, ['name', 'display_name']))
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
			$roles = Role::where('owner_id',Auth::id())->paginate($this->role_data->rows);
			if(is_null($roles)){
				throw new Exception('Roles have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $roles;
	}
	
	public function getStations(){
		try{
			$stations = Station::all();
			if(is_null($stations)){
				throw new Exception('Stations have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $stations;
	}
	
	public function getRoleStations(object $stations){
		$data = array();
		foreach($stations as $station){
			array_push($data, ['id'=>$station->uuid, 'name'=>$station->name]);
		}
		return $data;
	}
	
	public function getRoleUserStations(object $account_stations, object $stations){
		$data = array();
		foreach($account_stations as $account_station){
			array_push($data, ['id'=>$account_station->station()->first()->uuid, 'name'=>$account_station->station()->first()->name]);
		}
		
		return ['stations'=>$this->getRoleStations($stations), 'user_stations'=>$data];
	}
	
	public function validateRoleData(array $data){
		$rules = [
			$this->role_data->name_key => ['required', 'regex:/[a-zA-Z0-9\ \-\.]+/', 
					(isset($data['role_id'])? Rule::unique('roles')
						->where(function($query) use($data){ 
									$query->where('uuid', '<>',$data['role_id'])->whereNull('deleted_at');//Magical query
								}) : Rule::unique('roles')->where(function($query){
																	$query->whereNull('deleted_at');
																}))],
			$this->role_data->description_key => $this->role_data->description_req,
			$this->role_data->global_key => $this->role_data->global_req,
			$this->role_data->stations_key => ['array', (isset($data['global']))?Rule::requiredIf($data['global'] == 2):null],
			$this->role_data->stations_key.'*' => $this->role_data->station_id_req,
		];
		return Validator::make($data, $rules, $this->role_data->roleValidationMsgs);
	}
	
	public function getSelectedStations(array $data){
		try{
			$stations = Station::withUuid($data)->get();
			if(is_null($stations)){
				throw new Exception('Stations have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $stations;
	}
	
	public function getUserStations($id){
		try{
			$stations = DB::table('users')
							->join('account_user', 'users.id', '=', 'account_user.user_id')
							->join('accounts', 'account_user.account_id', '=', 'accounts.id')
							->join('account_station', 'accounts.id', '=', 'account_station.account_id')
							->join('stations', 'account_station.station_id', '=', 'stations.id')
							->where('users.id', $id)
							->select('stations.*')
							->get();
							
			if(is_null($stations)){
				throw new Exception('Stations have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $stations;
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
	
	public function getPaginatedRolePermissions(object $role){
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
	
	public function getPermNotInRole(object $role){
		try{
			$permissions = DB::table('permissions')
								->whereNotIn('permissions.id', function($query) use($role){
									$query->select(DB::raw('permission_id'))
									->from('permission_role')
									->whereRaw('permission_role.role_id='.$role->id);
								})
								->join('levels', 'permissions.level_id', '=', 'levels.id')
								->where('levels.order', '>=', Auth::user()->level()->first()->order)
								->select('permissions.*')
								->get();
								
			if(is_null($permissions)){
				throw new Exception('Permissions have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $permissions;
	}
	
	public function deleteRolePermission(object $role, object $perm){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Delete role\'s permission</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-8 w3-large">Your are about to delete a permission for the role \''.$role->display_name.'\':</p>
						<p><strong>Name:</strong> '.$perm->display_name.'<br /> '.((strlen($perm->description) < 1) ? '' : '<br /><strong>Description:</strong> '.$perm->description).'</p>
						
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<p class="w3-padding-8 w3-text-red">Are you sure you want to delete?</p>
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-role-permission').'/'.$role->uuid.'/'.$perm->uuid.'" title="Delete role permission">YES&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
								<button class="w3-button w3-large w3-theme w3-hover-light-blue" title="Dismiss" onclick="document.getElementById(\'delete\').style.display=\'none\'">NO&nbsp;</button>
							</div>
						</div>
					</footer>
				</div>';
	}
}

?>