<?php 
namespace App\Shell\Web\Extension;

use Exception;
use App\Error;
use App\ErrorStatus;
use App\Func;
use App\Station;

use Illuminate\Support\Str;
use App\Mail\ErrorNotificationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Shell\Web\Base;
use App\Shell\Data\ErrorData;

class ErrorExt extends Base{
	private $error_data;
	
	public function __construct(){
		$this->error_data = new ErrorData();
	}
	
	public function searchErrors(array $data){
		try{
			$errors = Error::where($this->prepareSearchParam($data, ['user_id', 'function_id', 'station_id', 'date_created', 'time_created', 'responsibility']))
								->paginate($this->error_data->rows);
			if(is_null($errors)){
				throw new Exception('Errors could not be retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $errors;
	}
	
	public function getPaginatedErrors(){
		try{
			$errors = Error::paginate($this->error_data->rows);
			if(is_null($errors)){
				throw new Exception('Erros could not be retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $errors;
	}
	
	public function getFunctions(){
		try{
			$functions = Func::all();
			if(is_null($functions)){
				throw new Exception('Functions have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $functions;
	}
	
	public function getStations(){
		try{
			$stations = Station::all();
			if(is_null($stations)){
				throw new Exception('Stations have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $stations;
	}
	
	public function getErrorStatus(){
		try{
			$status = ErrorStatus::all();
			if(is_null($status)){
				throw new Exception('Error status have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $status;
	}
	
	public function validateErrorData(array $data){
		$rules = [
				$this->error_data->station_id_key => $this->error_data->station_id_req,
				$this->error_data->function_id_key => $this->error_data->function_id_req,
				$this->error_data->date_time_created_key => $this->error_data->date_time_created_req,
				$this->error_data->description_key => $this->error_data->description_req,
				$this->error_data->impact_key => $this->error_data->impact_req,
				$this->error_data->remarks_key => $this->error_data->remarks_req,
				$this->error_data->responsibility_key => $this->error_data->responsibility_req,
				$this->error_data->notification_message_key => $this->error_data->notification_message_req,
		];
		
		$this->error_data->error_data_validation_msgs['date_time_created.before'] = Str::replaceArray('?', 
								[date_format(date_create($data[$this->error_data->date_time_created_key]), 'd/m/Y H:i:s'), 
								date('d/m/Y H:i:s', strtotime(strval(today()).' + 1days'))],
								$this->error_data->error_data_validation_msgs['date_time_created.before']);
								
		return Validator::make($data, $rules, $this->error_data->error_data_validation_msgs);
	}
	
	public function getFunction($uuid){
		try{
			$function = Func::withUuid($uuid)->first();
			if(is_null($function)){
				throw new Exception('Function has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $function;
	}
	
	public function getStation($uuid){
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
	
	public function getNotificationRecipients(object $station){
		try{
			$recipients = $station->recipient()->get();
			if(is_null($recipients)){
				throw new Exception('Recipients have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $recipients;
	}
	
	public function getErrorNumber($station_id, $function_id){
		try{
			$number = Error::withTrashed()->where($this->error_data->function_id_key, $function_id)
								->where($this->error_data->station_id_key, $station_id)
								->where('created_at', '>=', date_create(date('Y').'-01-01 00:00:00'))
								->where('created_at', '<', date_create(date('Y', strtotime(' + 1 year')).'-01-01 00:00:00'))
								->count();
			if(is_null($number)){
				throw new Exception('Number of functional error could not be retrieved successfully');
			}else{
				$number++;
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $number;
	}
	
	public function sendErrorNotificationEmail($uuid, $recipients){
		$error = $this->getError($uuid);
		if(!is_object($error)) return $error;
		
		$addresses = $this->prepareRecipientEmails($recipients, $error);
		
		if($error->station()->first()->email()->first()){
			array_push($addresses, $error->station()->first()->email()->first()->address);
		}
		
		if(count($addresses)){
			for($i = 0; $i < count($addresses); $i++){
				try{
					$email = Mail::to($addresses[$i])->send(new ErrorNotificationEmail($error));
					if($email){
						throw new Exception('Email to '.$addresses[$i].' has not been sent successfully');
					}
				}catch(Exception $e){
					return $e->getMessage();
				}	
			}
		}else return 'Addressees for email notification could not be found';
		
	}
	
	private function prepareRecipientEmails($recipients, $error){
		$addresses = array();
		foreach($recipients as $recipient){
			foreach($recipient->user()->first()->account()->first()->email()->get() as $email){
				array_push($addresses, $email->address);
			}
		}
		
		return $addresses;
	}
	
	public function getError($uuid){
		try{
			$error = Error::withUuid($uuid)->first();
			if(is_null($error)){
				throw new Exception('Functional error has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $error;
	}
}
?>