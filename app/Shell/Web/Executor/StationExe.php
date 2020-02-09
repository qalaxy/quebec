<?php 
namespace App\Shell\Web\Executor;

use App\Recipient;
use App\Supervisor;

use Uuid;
use App\Shell\Data\StationData;
use App\Shell\Web\Base;

class StationExe extends Base{
	private $stn_data;
	protected $data = array();
	
	public function __construct(){
		$this->stn_data = new StationData();
	}
	
	protected function storeStationFunction($station, $function){
		try{
			if($station->func()->attach($function)){
				throw new Exception('AIS function has not been added to the station successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'AIS function has been added to the station successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $station;
	}
	
	protected function destroyStationFunction($station, $function){
		try{
			$stn_func = $station->func()->detach($function);
			if(is_null($stn_func)){
				throw new Exception('AIS function has not been removed from the station successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'AIS function has been removed the station successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $stn_func;
	}
	
	protected function storeStationRecipient($station, $user){
		try{
			$recipient = Recipient::firstOrCreate(array($this->stn_data->station_id_key => $station->id,
											$this->stn_data->user_id_key => $user->id), 
							array('uuid' => Uuid::generate(),
									$this->stn_data->station_id_key => $station->id,
									$this->stn_data->user_id_key => $user->id,
									));
			if(is_null($recipient)){
				throw new Exception('Notification recipient has not been added successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Notification recipient has been added successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $recipient;
	}
	
	protected function destroyStationRecipient($recipient){
		try{
			$stn_recipient = $recipient->delete();
			if(is_null($stn_recipient)){
				throw new Exception('Recipient has not been removed successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Recipient has been removed successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $stn_recipient;
	}

	protected function storeStationSupervisor($station, $account){
		try{
			$supervisor = Supervisor::firstOrCreate(array($this->stn_data->station_id_key => $station->id, 
								$this->stn_data->account_id_key => $account->id), 
						array('uuid' => Uuid::generate(),
								$this->stn_data->station_id_key => $station->id, 
								$this->stn_data->account_id_key => $account->id,
								$this->stn_data->status_key => $this->data[$this->stn_data->status_key],
								$this->stn_data->from_key => $this->data[$this->stn_data->from_key],
								$this->stn_data->to_key => $this->data[$this->stn_data->to_key]
							));
			if(is_null($supervisor)){
				throw new Exception('Supervisor has not been added successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Supervisor has been added successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $supervisor;
	}

	protected function updateStationSupervisor($supervisor, $account){
		try{
			$supervisor = $supervisor->update(array('uuid' => Uuid::generate(), 
								$this->stn_data->account_id_key => $account->id,
								$this->stn_data->status_key => $this->data[$this->stn_data->status_key],
								$this->stn_data->from_key => $this->data[$this->stn_data->from_key],
								$this->stn_data->to_key => $this->data[$this->stn_data->to_key]
							));
			if(is_null($supervisor)){
				throw new Exception('Supervisor has not been updated successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Supervisor has been updated successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $supervisor;
	}

	protected function destroyStationSupervisor($supervisor){
		try{
			$supervisor = $supervisor->delete();
			if(is_null($supervisor)){
				throw new Exception('Supervisor has not been deleted successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Supervisor has been deleted successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $supervisor;
	}
}

?>