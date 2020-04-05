<?php 
namespace App\Shell\Web\Extension;

use Illuminate\Support\Facades\Validator;

use Exception;
use App\Permission;
use App\Shell\Web\Base;
use App\Shell\Data\PermissionData;

class AdminExt extends Base{
	
	private $perm_data;
	
	public function __construct(){
		$this->perm_data = new PermissionData();
	}
	
	public function getPermissions(){
		try{
			$perms = Permission::all();
			if(is_null($perms)){
				throw new Exception('Permissions have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $perms;
	}
	
	public function getPaginatedPermissions(){
		try{
			$perms = Permission::paginate($this->perm_data->rows);
			if(is_null($perms)){
				throw new Exception('Permissions have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $perms;
	}
	
	public function getPermission($uuid){
		try{
			$permission = Permission::withUuid($uuid)->first();
			if(is_null($permission)){
				throw new Exception('Permission has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $permission;
	}
	
	public function validatePermData(array $data){
		$rules = [
			$this->perm_data->name_key => $this->perm_data->name_req,
			$this->perm_data->display_name_key => $this->perm_data->display_name_req,
			$this->perm_data->description_key => $this->perm_data->description_req
		];
		
		return Validator::make($data, $rules, $this->perm_data->validationMsgs);
	}
	
	public function archivePerm(Permission $old){
		$keys = [$this->perm_data->name_key,
				$this->perm_data->display_name_key,
				$this->perm_data->description_key,
		];
		
		if($this->recordValidator($old, Permission::withUuid($old->uuid)->first(), $keys)){
			if(!is_int($this->archiver($old)))
				$this->sendDeveloperEmail($old);
			
		}
	}
	
	public function deletePerm($perm){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Delete permission</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-8 w3-large">Your are about to delete a permission:</p>
						<p><strong>Name:</strong> '.$perm->display_name.'<br /> '.((strlen($perm->description) < 1) ? '' : '<br /><strong>Description:</strong> '.$perm->description).'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-permission').'/'.$perm->uuid.'" title="Delete permission">Delete&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}
	
	public function searchPermission(array $data){
		
		$params = $this->prepareSearchParam($data, ['name','display_name']);
		try{
			$permissions = Permission::where($params)->paginate($this->perm_data->rows);
			if(is_null($permissions)){
				throw new Exception('Permissions could not be retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $permissions;		
	}
}

?>