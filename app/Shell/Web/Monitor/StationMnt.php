<?php 
namespace App\Shell\Web\Monitor;

use Illuminate\Support\Facades\DB;
use App\Shell\Web\Executor\StationExe;

class StationMnt extends StationExe{
	
	public function createStationFunction($station, $function){
		DB::beginTransaction();
		$stn_func = $this->storeStationFunction($station, $function);
		if(is_null($stn_func)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}
	
	public function deleteStationFunction($station, $function){
		DB::beginTransaction();
		$stn_func = $this->destroyStationFunction($station, $function);
		if(is_null($stn_func)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}
	
	public function createStationRecipient($station, $user){
		DB::beginTransaction();
		$recipient = $this->storeStationRecipient($station, $user);
		if(is_null($recipient)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}
	
	public function deleteStationRecipient($recipient){
		DB::beginTransaction();
		$stn_recipient = $this->destroyStationRecipient($recipient);
		if(is_null($stn_recipient)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}

	public function createStationSupervisor(array $data, $station, $account){
		$this->data = $data;
		DB::beginTransaction();

		$supervisor = $this->storeStationSupervisor($station, $account);
		if(is_null($supervisor)){
			DB::rollback();
			return $this->error;
		}

		DB::commit();
		return $this->success;
	}

	public function editStationSupervisor($supervisor, array $data, $account){
		$this->data = $data;
		DB::beginTransaction();
		$supervisor = $this->updateStationSupervisor($supervisor, $account);
		if(is_null($supervisor)){
			DB::rollback();
			return $this->error;
		}

		DB::commit();
		return $this->success;
	}

	public function deleteStationSupervisor($supervisor){
		DB::beginTransaction();
		$supervisor = $this->destroyStationSupervisor($supervisor);
		if(is_null($supervisor)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}

}

?>