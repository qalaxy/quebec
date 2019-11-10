<?php 
namespace App\Shell\Web\Executor;

use Exception;
use App\Permission;
use App\Shell\Web\Base;
use App\Shell\Data\PermissionData;
use Uuid;

class AdminExe extends Base{
	protected $data;
	protected $error = array();
	protected $success = array();
	private $perm_data;
	
	public function __construct(){
		$this->perm_data = new PermissionData();
	}
	protected function storePerm(){
		try{
			do{$uuid = Uuid::generate();}while(Permission::withUuid($uuid)->first());
			
			$perm = Permission::firstOrCreate(['name'=>$this->data[$this->perm_data->name_key], 'deleted_at'=>null],['uuid' => $uuid,
									$this->perm_data->name_key => $this->data[$this->perm_data->name_key],
									$this->perm_data->display_name_key => $this->data[$this->perm_data->display_name_key],
									$this->perm_data->description_key => $this->data[$this->perm_data->description_key],
								]);
			if(is_null($perm)){
				throw new Exception('Permission has not been created successfully');
			}
			else{
				$this->success = array('indicator'=>'success', 'message'=>'Permission successfully created', 'uuid'=>$perm->uuid);
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $perm;
	}
	
	protected function updatePerm($uuid){
		try{
			$perm = Permission::where('uuid', $uuid)->update([$this->perm_data->name_key => $this->data[$this->perm_data->name_key],
									$this->perm_data->display_name_key => $this->data[$this->perm_data->display_name_key],
									$this->perm_data->description_key => $this->data[$this->perm_data->description_key],
								]);
			if(is_null($perm)){
				throw new Exception('Permission has not been updated successfully');
			}
			else{
				$this->success = array('indicator'=>'success', 'message'=>'Permission successfully updated');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $perm;
	}
	
	protected function destroyPerm($permission){
		try{
			$perm = $permission->delete();
			if(is_null($perm)){
				throw new Exception('Permission has not been deleted successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Permission is successfully deleted');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $perm;
	}
}

?>