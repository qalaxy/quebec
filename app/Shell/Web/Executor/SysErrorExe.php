<?php 
namespace App\Shell\Web\Executor;

use App\Shell\Data\SysErrorData;
use App\Shell\Web\Base;
class SysErrorExe extends Base{
	protected $data = array();
	protected $sys_data;

	public function __construct(){
		$this->sys_data = new SysErrorData();
	}

	protected function storeSystemError($system, $station, $number){
		try{
			$system_error = SystemError::create(array($this->sys_data->number_key => $number,
						$this->sys_data->system_id_key => $system->id,
						$this->sys_data->station_id_key => $station->id,
						$this->sys_data->description_key => $this->data[$this->sys_data->description_key],
						$this->sys_data->solution_key => $this->data[$this->sys_data->solution_key],
						$this->sys_data->from_key => $this->data[$this->sys_data->from_key],
						$this->sys_data->to_key => $this->data[$this->sys_data->to_key],
						$this->sys_data->state_id_key => $this->data[$this->sys_data->state_id_key],
						$this->sys_data->remarks_key => $this->data[$this->sys_data->remarks_key],
					));
			if(is_null($system_error)){
				throw new Exception('System error has not  been created successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'System error has been created successfully', 'uuid'=>$system_error->uuid);
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'success', 'message'=>$e->getMessage());
		}
		return $system_error;
	}
}
?>