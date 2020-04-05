<?php
namespace App\Shell\Web\Extension;

use Exception;

use App\AccountStation;
use App\Station;
use App\System;
use App\SystemError;
use App\User;

use App\Shell\Web\Base;
use App\Shell\Data\SysErrorData;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SysErrorExt extends Base{
	private $sys_data;
	
	public function __construct(){
		$this->sys_data = new SysErrorData();
	}

	public function searchSysErrors(array $data){
		try{
			$errors = SystemError::where($this->prepareSearchParam($data, ['name', 'station_id', 'system_id', 'from', 'to']))->paginate($this->sys_data->rows);
			if(is_null($errors)){
				throw new Exception('System errors have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $errors;
	}

	public function getPaginatedSysErrors(){
		try{
			$errors = SystemError::paginate($this->sys_data->rows);
			if(is_null($errors)){
				throw new Exception('System errors have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $errors;
	}

	public function getSystems(){
		try{
			$systems = System::all();
			if(is_null($systems)){
				throw new Exception('Systems have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $systems;
	}


	public function getUserAccountStations(User $user){
		try{
			$account_stations = AccountStation::where('account_id', $user->account()->first()->id)->get();
			if(is_null($account_stations)){
				throw new Exception('Account stations have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $account_stations;
	}

	public function validateSystemErrorData(array $data){
		$rules = [
			$this->sys_data->system_id_key => $this->sys_data->system_id_req,
			$this->sys_data->station_id_key => $this->sys_data->station_id_req,
			$this->sys_data->description_key => $this->sys_data->description_req,
			$this->sys_data->solution_key => $this->sys_data->solution_req,
			$this->sys_data->from_key => $this->sys_data->from_req,
			$this->sys_data->to_key => $this->sys_data->to_req,
			$this->sys_data->remarks_key => $this->sys_data->remarks_req,
		];

		return Validator::make($data, $rules, $this->sys_data->sys_data_validation_msgs);
	}

	public function getStation(string $uuid){
		try{
			$station = Station::withUuid($uuid)->first();
			if(is_null($station)){
				throw new Exception('Station has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $station;
	}

	public function getSystem(string $uuid){
		try{
			$system = System::withUuid($uuid)->first();
			if(is_null($system)){
				throw new Exception('System has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $system;
	}

	public function getNextSystemErrorNumber(Station $station, System $system){
		try{
			$number = Error::withTrashed()->where($this->error_data->station_id_key, $station->id)
								->where($this->error_data->system_id_key, $system->id)
								->where('created_at', '>=', date_create(date('Y').'-01-01 00:00:00'))
								->where('created_at', '<', date_create(date('Y', strtotime(' + 1 year')).'-01-01 00:00:00'))
								->count();
			if(is_null($number)){
				throw new Exception('Number of system error could not be retrieved successfully');
			}else{
				$number++;
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $number;
	}
}
?>