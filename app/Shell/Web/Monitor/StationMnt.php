<?php 
namespace App\Shell\Web\Monitor;

use Illuminate\Support\Facades\DB;
use App\Shell\Web\Executor\StationExe;

class StationMnt extends StationExe{
	
	public function createStationFunction(object $station, object $function){
		DB::beginTransaction();
		$stn_func = $this->storeStationFunction($station, $function);
		if(is_null($stn_func)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}
	
	public function deleteStationFunction(object $station, object $function){
		DB::beginTransaction();
		$stn_func = $this->destroyStationFunction($station, $function);
		if(is_null($stn_func)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}
	
	public function createStationRecipient(object $station, object $user){
		DB::beginTransaction();
		$recipient = $this->storeStationRecipient($station, $user);
		if(is_null($recipient)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}
	
	public function deleteStationRecipient(object $recipient){
		DB::beginTransaction();
		$stn_recipient = $this->destroyStationRecipient($recipient);
		if(is_null($stn_recipient)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}

}

?>