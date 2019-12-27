<?php 
namespace App\Shell\Web\Executor;

use App\AffectedProduct;
use App\AioError;
use App\Error;
use App\ErrorCorrection;
use App\ErrorNotification;
use App\ExternalError;
use App\State;
use App\Status;
use App\SupervisorReaction;
use App\Message;
use App\NotificationRecipient;
use App\OriginatorReaction;
use Uuid;

use Illuminate\Support\Facades\Auth;
use App\Shell\Web\Base;
use App\Shell\Data\ErrorData;

class ErrorExe extends Base{
	protected $error_data;
	protected $data = array();
	
	public function __construct(){
		$this->error_data = new ErrorData();
	}
	
	protected function storeError($function, $station){
		try{
			$func_error = Error::firstOrCreate(array($this->error_data->number_key => $this->data[$this->error_data->number_key],
							$this->error_data->function_id_key => $function->id,
							$this->error_data->station_id_key => $station->id,
						), 
					array('uuid'=>Uuid::generate(),
						$this->error_data->user_id_key => Auth::id(),
						$this->error_data->function_id_key => $function->id,
						$this->error_data->station_id_key => $station->id,
						$this->error_data->number_key => $this->data[$this->error_data->number_key],
						//$this->error_data->date_time_created_key => $this->data[$this->error_data->date_time_created_key],
						$this->error_data->description_key => $this->data[$this->error_data->description_key],
						$this->error_data->impact_key => $this->data[$this->error_data->impact_key],
						$this->error_data->remarks_key => $this->data[$this->error_data->remarks_key],
						$this->error_data->responsibility_key => $this->data[$this->error_data->responsibility_key],
						));
			if(is_null($func_error)){
				throw new Exception('Error has not been created successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Error has been created successfully', 'uuid'=>$func_error->uuid);
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $func_error;
	}
	
	protected function storeErrorStatus($func_error){
		try{
			$status = Status::create(array('uuid'=>Uuid::generate(),
						$this->error_data->user_id_key => Auth::id(),
						$this->error_data->state_id_key => State::where('code', 1)->first()->id,)
					);
			if(is_null($status)){
				throw new Exception('Error status has not been created successfully');
			}else{
				$status->error()->attach($func_error);
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
		}
		return $status;
	}
	
	protected function storeErrorNotification($func_error, $station){
		try{
			$notification = ErrorNotification::firstOrCreate(array($this->error_data->error_id_key => $func_error->id), 
								array('uuid'=>Uuid::generate(),
									$this->error_data->error_id_key => $func_error->id,
									$this->error_data->station_id_key => $station->id,
									$this->error_data->user_id_key => Auth::id(),
									$this->error_data->status_key => 1,
								)
							);
			if(is_null($notification)){
				throw new Exception('Notification has not been created successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $notification;
	}
	
	protected function storeMessage($notification){
		try{
			$msg = Message::create(array('uuid'=>Uuid::generate(),
										$this->error_data->error_notification_id_key => $notification->id,
										$this->error_data->user_id_key => Auth::id(),
										$this->error_data->text_key => $this->data[$this->error_data->notification_message_key],
									));
			if(is_null($msg)){
				throw new Exception('Error notification message has not been created successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $msg;
	}
	
	protected function storeRecepients($notification, $recipient){
		try{
			$notification_recipient = NotificationRecipient::firstOrCreate(array($this->error_data->error_notification_id_key => $notification->id),
								array($this->error_data->error_notification_id_key => $notification->id,
								$this->error_data->user_id_key => $recipient->user()->first()->id)
							);
			if(is_null($notification_recipient)){
				throw new Exception('Notification recipient has not been created successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $notification_recipient;
	}
	
	protected function storeErrorProduct($error, $product){
		try{
			$error_product = AffectedProduct::firstOrCreate(array($this->error_data->error_id_key => $error->id,
							$this->error_data->product_id_key => $product->id, ), 
					array($this->error_data->product_id_key => $product->id,
						$this->error_data->user_id_key => Auth::id(),
						$this->error_data->error_id_key => $error->id,
						$this->error_data->product_identification_key => $this->data[$this->error_data->product_identification_key],));
			if(is_null($error_product)){
				throw new Exception('Affected product has not been created successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Affected product has been created successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $error_product;
	}
	
	protected function storeErrorCorrection($error){
		try{
			$correction = ErrorCorrection::firstOrCreate(array($this->error_data->error_id_key => $error->id), 
					array('uuid' => Uuid::generate(),
							$this->error_data->error_id_key => $error->id,
							$this->error_data->user_id_key => Auth::id(),
							$this->error_data->station_id_key => $error->station()->first()->id,
							$this->error_data->source_key => $this->data[$this->error_data->error_origin_key],
							//$this->error_data->date_time_created_key => $this->data[$this->error_data->date_time_created_key],
							$this->error_data->corrective_action_key => $this->data[$this->error_data->corrective_action_key],
							$this->error_data->cause_key => $this->data[$this->error_data->cause_key],
							$this->error_data->remarks_key => $this->data[$this->error_data->remarks_key],
						));
			if(is_null($correction)){
				throw new Exception('Error corrective action has not been created successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Error corrective action has been created successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $correction;
	}
	
	protected function storeErrorCorrectionStatus($correction){
		try{
			$status = Status::create(array('uuid'=>Uuid::generate(),
						$this->error_data->user_id_key => Auth::id(),
						$this->error_data->state_id_key => State::where('code', 3)->first()->id,)
					);
			if(is_null($status)){
				throw new Exception('Error correction status has not been created successfully');
			}else{
				$status->errorCorrection()->attach($correction);
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $status;
	}
	
	protected function storeAioError($correction){
		try{
			$aio_error = AioError::firstOrCreate(array($this->error_data->error_correction_id_key => $correction->id), 
						array('uuid' => Uuid::generate(),
							$this->error_data->error_correction_id_key => $correction->id,
							$this->error_data->user_id_key => Auth::id(),
							$this->error_data->originator_id_key => $this->data[$this->error_data->aio_key]));
			if(is_null($aio_error)){
				throw new Exception('Aio error has not been created successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $aio_error;
	}
	
	protected function storeOtherError($correction){
		try{
			$external_error = ExternalError::firstOrCreate(array($this->error_data->error_correction_id_key => $correction->id), 
					array('uuid' => Uuid::generate(),
							$this->error_data->error_correction_id_key => $correction->id,
							$this->error_data->user_id_key => Auth::id(),
							$this->error_data->description_key => $this->data[$this->error_data->originator_key]));
			if(is_null($external_error)){
				throw new Exception('External source has not been created successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $external_error;
	}
	
	protected function updateErrorStatus($status){
		try{
			$error_status = $status->update([$this->error_data->state_id_key => State::where('code', 3)->first()->id]);
			if(is_null($error_status)){
				throw new Exception('Error status has not been updated successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$error_status);
			return null;
		}
		return $error_status;
	}
	
	protected function storeErrorOriginatorReaction($error_correction){
		try{
			$originator_reaction = OriginatorReaction::firstOrCreate(array($this->error_data->error_correction_id_key => $error_correction->id), 
								array('uuid' => Uuid::generate(),
									$this->error_data->error_correction_id_key => $error_correction->id,
									$this->error_data->status_key => $this->data[$this->error_data->originator_reaction_key],
									$this->error_data->remarks_key => $this->data[$this->error_data->remarks_key],));
			if(is_null($originator_reaction)){
				throw new Exception('Originator reaction has not been created successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Originator reaction has been created successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $originator_reaction;
	}
	
	protected function updateStatus($status, $state){
		try{
			$error_status = $status->update(array($this->error_data->state_id_key => $state->id,
										$this->error_data->user_id_key => Auth::id(),
										$this->error_data->remarks_key => $this->data[$this->error_data->remarks_key]));
			if(is_null($error_status)){
				throw new Exception('Error status has not been updated successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $error_status;
	}
	
	protected function storeSupervisorReaction($error_correction, $status){
		try{
			$supervisor_reaction = SupervisorReaction::firstOrCreate(array($this->error_data->error_correction_id_key => $error_correction->id), 
								array('uuid' => Uuid::generate(),
									$this->error_data->error_correction_id_key => $error_correction->id,
									$this->error_data->status_id_key => $status->id));
			
			if(is_null($supervisor_reaction)){
				throw new Exception('Supervisor reaction has not been created successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Supervisor reaction has been created successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $supervisor_reaction;
	}
	
}

?>