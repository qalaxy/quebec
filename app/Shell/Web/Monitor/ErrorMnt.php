<?php 
namespace App\Shell\Web\Monitor;

use Illuminate\Support\Facades\DB;
use App\Shell\Web\Executor\AccountExe;

use App\Shell\Web\Executor\ErrorExe;

class ErrorMnt extends ErrorExe{
	public function createError(array $data, object $function, object $reported_station, object $reporting_station, object $recipients){
		$this->data = $data;
		
		DB::beginTransaction();
		$func_error = $this->storeError($function, $reported_station, $reporting_station);
		if(is_null($func_error)){
			DB::rollback();
			return $this->error;
		}
		
		$error_status = $this->storeErrorStatus($func_error);
		if(is_null($error_status)){
			DB::rollback();
			return $this->error;
		}
		
		$notification = $this->storeErrorNotification($func_error, $reported_station);
		if(is_null($notification)){
			DB::rollback();
			return $this->error;
		}
			
		if(count($recipients)){
			foreach($recipients as $recipient){
				if($recipient->station()->first()->accountStation()->where('account_id', $recipient->user()->first()->account()->first()->id)->first()->status == 1){
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

	public function deleteAffectedProduct($product){
		DB::beginTransaction();
		$product = $this->destroyAffectedProduct($product);
		if(is_null($product)){
			DB::rollback();
			return $this->error;
		}
		DB::rollback();
		return $this->success;
	}
	
	public function createCorrectiveAction(array $data, object $error){
		$this->data = $data;
		DB::beginTransaction();
		$correction = $this->storeErrorCorrection($error);
		if(is_null($correction)){
			DB::rollback();
			return $this->error;
		}
		
		$correction_status = $this->storeErrorCorrectionStatus($correction);
		if(is_null($correction_status)){
			DB::rollback();
			return $this->error;
		}
		
		if(isset($data[$this->error_data->originator_id_key])){
			$origin = $this->storeAioError($correction);
			if(is_null($origin)){
				DB::rollback();
				return $this->error;
			}
		}else if(isset($data[$this->error_data->originator_key])){
			$origin = $this->storeOtherError($correction);
			if(is_null($origin)){
				DB::rollback();
				return $this->error;
			}
		}
		$error_status = $this->updateErrorStatus($error->status()->first(), 3);
		if(is_null($error_status)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}

	public function deleteCorrectiveAction(object $error){
		DB::beginTransaction();
		$correction = $this->destroyCorrectiveAction($error);
		if(is_null($correction)){
			DB::rollback();
			return $this->error;
		}
		
		DB::rollback();
		return $this->success;
	}
	
	public function createErrorOriginatorReaction(array $data, object $error_correction){
		$this->data = $data;
		
		DB::beginTransaction();
		$originator_reaction = $this->storeErrorOriginatorReaction($error_correction);
		if(is_null($originator_reaction)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function createErrorSupervisorReaction(array $data, object $error){
		$this->data = $data;
		
		DB::beginTransaction();
		$state = (boolval($data[$this->error_data->supervisor_reaction_key])) ? 4 : 2;
	
		if($state == 4){
			$error_status = $this->updateStatus($error->status()->first(), $state);
			if(is_null($error_status)){
				DB::rollback();
				return $this->error;
			}
		}
		
		$error_correction_status = $this->updateStatus($error->errorCorrection()->first()->status()->first(), $state);
		if(is_null($error_correction_status)){
			DB::rollback();
			return $this->error;
		}
		
		$supervisor_reaction = $this->storeSupervisorReaction($error->errorCorrection()->first());
									
		if(is_null($supervisor_reaction)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}
	
	public function editCorrectiveAction(array $data, object $error){
		$this->data = $data;
		
		DB::beginTransaction();
		$correction = $this->updateErrorCorrection($error->errorCorrection()->first());
		if(is_null($correction)){
			DB::rollback();
			return $this->error;
		}
		
		$correction_status = $this->updateErrorStatus($error->errorCorrection()->first()->status()->first(), 3);
		if(is_null($correction_status)){
			DB::rollback();
			return $this->error;
		}
		
		$aio_error = $error->errorCorrection()->first()->aioError()->first();
		$external_error = $error->errorCorrection()->first()->externalError()->first();
		
		if(isset($data[$this->error_data->originator_id_key])){
			
			if($aio_error 
				&& $aio_error->errorOriginator()->first()->id != $data[$this->error_data->aio_key] 
				&& $aio_error->originatorReaction()->first()){
				$delete_originator_reaction = $this->destroyOriginatorReaction($error->errorCorrection()->first()->originatorReaction()->first());
				if(is_null($delete_originator_reaction)){
					DB::rollback();
					return $this->error;
				}
			}
			
			$update_aio_error = ($aio_error) ? $this->updateAioError($aio_error) : $this->storeAioError($error->errorCorrection()->first());
			if(is_null($update_aio_error)){
				DB::rollback();
				return $this->error;
			}
			
			if($external_error){
				$delete_external_error = $this->destroyExternalError($external_error);
				if(is_null($delete_external_error)){
					DB::rollback();
					return $this->error;
				}
			}
		}else if(isset($data[$this->error_data->originator_key])){
			$update_external_error = ($external_error) ? $this->updateExternalError($external_error) : $this->storeOtherError($error->errorCorrection()->first());
			if(is_null($update_external_error)){
				DB::rollback();
				return $this->error;
			}
			if($aio_error){
				$destroy_aio_error = $this->destroyAioError($aio_error); //Source opinion should also go [through r/ship]
				if(is_null($destroy_aio_error)){
					DB::rollback();
					return $this->error;
				}
			}
		}
		
		DB::commit();
		return $this->success;
		
	}
	
	public function editErrorSupervisorReaction(array $data, object $error){
		$this->data = $data;
		
		DB::beginTransaction();
		
		$state = (boolval($data[$this->error_data->supervisor_reaction_key])) ? 4 : 2;
	
		if($state == 4){
			$error_status = $this->updateStatus($error->status()->first(), $state);
			if(is_null($error_status)){
				DB::rollback();
				return $this->error;
			}
		}
		
		$error_correction_status = $this->updateStatus($error->errorCorrection()->first()->status()->first(), $state);
		if(is_null($error_correction_status)){
			DB::rollback();
			return $this->error;
		}
		
		$supervisor_reaction = $this->updateSupervisorReaction($error->errorCorrection()->first()->supervisorReaction()->first());
									
		if(is_null($supervisor_reaction)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
		
	}

	public function editError(array $data, $error, $reporting_station){
		$this->data = $data;

		DB::beginTransaction();

		$error = $this->updateError($error, $reporting_station);
		if(is_null($error)){
			DB::rollback();
			return $this->error;
		}

		DB::commit();
		return $this->success;
	}

	public function deleteError(object $error){
		DB::beginTransaction();
		$func_error = $this->destroyError($error);
		if(is_null($func_error)){
			DB::rollback();
			return $this->error;
		}
		
		DB::rollback();
		return $this->success;
	}

	public function editErrorOriginatorReaction(array $data, object $error){
		$this->data = $data;
		DB::beginTransaction();
		$func_error = $this->updateErrorOriginatorReaction($error->errorCorrection()->first()->originatorReaction()->first());
		if(is_null($func_error)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}

	public function deleteErrorOriginatorReaction(object $error){
		DB::beginTransaction();
		$originator_reaction = $this->destroyErrorOriginatorReaction($error->errorCorrection()->first()->originatorReaction()->first());
		if(is_null($originator_reaction)){
			DB::rollback();
			return $this->error;
		}
		
		DB::rollback();
		return $this->success;
	}
}
?>