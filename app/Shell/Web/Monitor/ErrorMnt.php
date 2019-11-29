<?php 
namespace App\Shell\Web\Monitor;

use Illuminate\Support\Facades\DB;
use App\Shell\Web\Executor\AccountExe;

use App\Shell\Web\Executor\ErrorExe;

class ErrorMnt extends ErrorExe{
	public function createError(array $data, object $function, object $station, object $recipients){
		$this->data = $data;
		
		DB::beginTransaction();
		$func_error = $this->storeError($function, $station);
		if(is_null($func_error)){
			DB::rollback();
			return $this->error;
		}
		
		if($func_error->responsibility == 0){
			$notification = $this->storeErrorNotification($func_error, $station);
			if(is_null($notification)){
				DB::rollback();
				return $this->error;
			}
			
			$message = $this->storeMessage($notification);
			if(is_null($message)){
				DB::rollback();
				return $this->error;
			}
			if($recipients){
				foreach($recipients as $recipient){
					$notification_recipient = $this->storeRecepients($notification, $recipient);
					if(is_null($notification_recipient)){
						DB::rollback();
						return $this->error;
					}
				}
			}
		}		
		
		DB::commit();
		return $this->success;
	}
	
	public function createErrorProduct(array $data, object $error, object $product){
		$this->data = $data;
		
		DB::beginTransaction();
		$error_product = $this->storeErrorProduct($error, $product);
		if(is_null($error_product)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}
	
	public function createCorrectiveAction(array $data, object $error){
		$this->data = $data;
		//return $this->error_data->source_key;
		DB::beginTransaction();
		$correction = $this->storeErrorCorrection($error);
		if(is_null($correction)){
			DB::rollback();
			return $this->error;
		}
		
		if(isset($data[$this->error_data->originator_id_key])){
			$origin = $this->storeAioError($error, $correction);
			if(is_null($origin)){
				DB::rollback();
				return $this->error;
			}
		}else if(isset($data[$this->error_data->originator_key])){
			$origin = $this->storeOtherError($error, $correction);
			if(is_null($origin)){
				DB::rollback();
				return $this->error;
			}
		}
		DB::commit();
		return $this->success;
	}
}
?>