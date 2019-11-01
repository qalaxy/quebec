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
	
	public function validatePermData(array $data){
		$rules = [
			$this->perm_data->name_key => $this->perm_data->name_req,
			$this->perm_data->display_name_key => $this->perm_data->display_name_req,
			$this->perm_data->description_key => $this->perm_data->description_req
		];
		
		return Validator::make($data, $rules, $this->perm_data->validationMsgs);
	}
	
	public function archivePerm(object $old){
		$keys = [$this->perm_data->name_key,
				$this->perm_data->display_name_key,
				$this->perm_data->description_key,
		];
		
		if($this->recordValidator($old, Permission::withUuid($old->uuid)->first(), $keys)){
			if(!is_int($this->archiver($old)))
				$this->sendDeveloperEmail($old);
			}
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
						<p class="w3-padding-16">Your are about to delete this permission:</p>
						<p>Name: '.$perm->name.'<br /> '.(is_null($perm->description) ? '' : '<br />Description: '.$perm->description).'</p>
						<form id="destroy-perm" action="'.url('destroy-perm/'.$perm->uuid).'" method="POST" style="display: none;">
                            @csrf
							<input type="hidden" name="delete" value="true" readonly/>
                        </form>
					</div>
				</div>';
	}
}

?>