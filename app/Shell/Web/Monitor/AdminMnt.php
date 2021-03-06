<?php 
namespace App\Shell\Web\Monitor;

use Illuminate\Support\Facades\DB;
use App\Shell\Web\Executor\AdminExe;

class AdminMnt extends AdminExe{

	public function createPerm(array $data){
		$this->data = $data;
		
		DB::beginTransaction();
		$perm = $this->storePerm();
		if(is_null($perm)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function editPerm(array $data, $uuid){
		$this->data = $data;
		
		DB::beginTransaction();
		$perm = $this->updatePerm($uuid);
		if(is_null($perm)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	public function deletePerm(object $permission){
		DB::beginTransaction();
		$perm = $this->destroyPerm($permission);
		if(is_null($perm)){
			DB::rollback();
			return $this->error;
		}
		DB::rollback();
		return $this->success;
	}
}
?>