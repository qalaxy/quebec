<?php 
namespace App\Shell\Web\Monitor;

use Illuminate\Support\Facades\DB;

use App\Shell\Web\Executor\SysErrorExe;
class SysErrorMnt extends SysErrorExe{

	public function createSystemError(array $data, $system, $station, int $number){
		$this->data = $data;

		DB::beginTransaction();
		$system_error = $this->storeSystemError($system, $station, $number);
		if(is_null($system_error)){
			DB::rollback();
			return $this->error;
		}
		DB::rollback();
		return $this->success;
	}
}

?>