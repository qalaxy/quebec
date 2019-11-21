<?php 
namespace App\Shell\Web\Executor;

use App\Error;
use App\ErrorNotification;
use App\ErrorStatus;
use App\Message;
use Uuid;

use Illuminate\Support\Facades\Auth;
use App\Shell\Web\Base;
use App\Shell\Data\ErrorData;

class ErrorExe extends Base{
	private $error_data;
	protected $data = array();
	
	public function __construct(){
		$this->error_data = new ErrorData();
	}
	
	protected function storeError($function, $station){
		try{
			$func_error = Error::firstOrCreate(array($this->error_data->number_key => $this->data[$this->error_data->number_key]), 
					array('uuid'=>Uuid::generate(),
						$this->error_data->user_id_key => Auth::id(),
						$this->error_data->function_id_key => $function->id,
						$this->error_data->station_id_key => $station->id,
						$this->error_data->number_key => $this->data[$this->error_data->number_key],
						$this->error_data->date_time_created_key => $this->data[$this->error_data->date_time_created_key],
						$this->error_data->description_key => $this->data[$this->error_data->description_key],
						$this->error_data->impact_key => $this->data[$this->error_data->impact_key],
						$this->error_data->remarks_key => $this->data[$this->error_data->remarks_key],
						$this->error_data->error_status_id_key => ErrorStatus::where('id', 1)->first()->id,
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
			$notification_recipient = NotificationRecipient::firstOrCreate(array(),
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
	
}

?>