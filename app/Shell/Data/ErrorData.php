<?php 
namespace App\Shell\Data;

class ErrorData{
	public $rows = 15;
	
	public $function_id_key = 'function_id';
	public $station_id_key = 'station_id';
	public $reported_station_id_key = 'reported_station_id';
	public $reporting_station_id_key = 'reporting_station_id';
	public $date_time_created_key = 'date_time_created';
	public $description_key = 'description';
	public $impact_key = 'impact';
	public $remarks_key = 'remarks';
	public $error_status_id_key = 'error_status_id';
	public $responsibility_key = 'responsibility';
	public $user_id_key = 'user_id';
	public $status_key = 'status';
	public $error_notification_id_key = 'error_notification_id';
	public $error_id_key = 'error_id';
	public $text_key = 'text';
	public $number_key = 'number';
	public $notification_message_key = 'notification_message';
	public $product_id_key = 'product_id';
	public $product_identification_key = 'product_identification';
	public $corrective_action_key = 'corrective_action';
	public $cause_key = 'cause';
	public $originator_id_key = 'originator_id';
	public $originator_key = 'originator';
	public $error_origin_key = 'error_origin';
	public $source_key = 'source';
	public $aio_key = 'aio';
	public $state_id_key = 'state_id';
	public $originator_reaction_key = 'originator_reaction';
	public $supervisor_reaction_key = 'supervisor_reaction';
	public $error_correction_id_key = 'error_correction_id';
	public $aio_error_id_key = 'aio_error_id';
	public $sts_key = 'sts';
	
	
	public $function_id_req = 'required|uuid';
	public $station_id_req = 'required|uuid';
	public $date_time_created_req = 'required|date|before:tomorrow';
	public $description_req = 'required|max:255';
	public $impact_req = 'required|max:255';
	public $remarks_req = 'nullable|max:255';
	public $error_status_id_req = 'required|uuid';
	public $responsibility_req = 'required|boolean';
	public $message_req = 'nullable|max:255';
	public $notification_message_req = 'sometimes|required|max:255';
	public $product_id_req = 'required|uuid';
	public $product_identification_req = 'nullable|max:255';
	public $corrective_action_req = 'required|max:255';
	public $cause_req = 'required|max:255';
	public $originator_id_req = 'sometimes|required|uuid';
	public $originator_req = 'sometimes|required|max:255';
	public $error_origin_req = 'required|boolean';
	public $originator_reaction_req = 'required|boolean';
	public $supervisor_reaction_req = 'required|boolean';
	public $state_id_req = 'required|uuid';
	public $error_search_number_req = 'nullable|integer';
	public $error_search_station_req = 'nullable|uuid';
	public $error_search_function_req = 'nullable|uuid';
	public $error_search_originator_req = 'nullable|uuid';
	public $error_search_error_from_req = 'nullable|date';
	public $error_search_error_to_req = 'nullable|date|after:error_from';
	public $error_search_correction_from_req = 'nullable|date';
	public $error_search_correction_to_req = 'nullable|date|after:correction_from';
	
	public $corrective_action_validation_msgs = [
		'corrective_action.required' => 'You have not entered corrective action to the error',
		'corrective_action.max' => 'Corrective action should not have more than 255 characters',
		
		'cause.required' => 'You have not entered the cause of the error',
		'cause.max' => 'The cause of the error should not have more than 255 characters',
		
		'remarks.max'=>'Remarks should not have more than 255 characters',
		
		'date_time_created.required' => 'You have not entered the date and time the corrective action was done',
		'date_time_created.date' => 'Date of responding value should have a date format',
		'date_time_created.before' => 'The date ? should be before ?',
		
		'originator_id.required'=>'You have not selected the officer who caused the error',
		'originator_id.uuid'=>'The value should be uuid',
		
		'originator.required'=>'You have not described the entity that caused error',
		'originator.max'=>'The description of originator should not have more than 255 characters',
		
		'error_origin.required'=>'You have not said whether the error originated from teh station or not',
		'error_origin.boolean'=>'Value should be either 0 or 1',
	];
	
	public $error_data_validation_msgs = [
		'function_id.required'=>'You have not selected functional unit',
		'function_id.uuid'=>'Functional unit value should be a uuid',
		
		'reported_station_id.required'=>'You have not selected station which caused the error',
		'reported_station_id.uuid'=>'Station value should be a uuid',
		
		'reporting_station_id.required'=>'You have not selected station which is reporting the error',
		'reporting_station_id.uuid'=>'Station value should be a uuid',
		
		'date_time_created.required'=>'You have not entered date and time when the error occuered',
		'date_time_created.date'=>'Date and time should have a date format',
		'date_time_created.before'=>'Date and time ? should be before ?',
		
		'description.required'=>'You have not described the error',
		'description.max'=>'Characters in error description should not be more than 255',
		
		'impact.required'=>'You have not entered error impact',
		'impact.max'=>'Characters in error impact should not be more than 255',
		
		'remarks.max'=>'Characters in remarks should not be more than 255',
		
		'responsibility.required'=>'You have not choosen if you are responsible for the error',
		'responsibility.boolean'=>'Responsibility value should either be 1 or 0',
		
		'notification_message.required'=>'You have not entered notification message',
		'notification_message.max'=>'Characters in message should not be more than 255',
	];
	
	public $error_product_validation_msgs = [
		'product_id.required'=>'You have not selected the product affected',
		'product_id.uuid'=>'Value for the product affected should be uuid type',
		
		'product_identification.max'=>'Identification of affected product should not have more tha 255 characters',
	];
	
	public $validate_error_originator_reaction_msgs = [
		'originator_reaction.required' => 'You have not selected your reaction to the corrective action given',
		'originator_reaction.boolean' => 'Reaction value can either be 1 or 0',
		
		'remarks.max'=>'Characters in remarks should not be more than 255',
	];
	
	public $validate_error_supervisor_reaction_msgs = [
		'supervisor_reaction.required' => 'You have not made any selection',
		'supervisor_reaction.uuid' => 'Value should be uuid',
		
		'remarks.max'=>'Characters in remarks should not be more than 255',
	];

	public $validate_error_search_data_msgs = [
		'number.integer' => 'Number should be integer only',
		'station.uuid' => 'Value for station field should be uuid',
		'func.uuid' => 'Value for function field should be uuid',
		'originator.uuid' => 'Value for officer origination the error field should be uuid',
		'error_from.date' => 'Error reporting date (From) should be a date',
		'error_to.date' => 'Error reporting date (To) should be a date',
		'error_to.after' => 'Error reporting date (To) should be after the From date',
		'correction_from.date' => 'Error correction date (From) should be a date',
		'correction_to.date' => 'Error correction date (To) should be a date',
		'correction_to.after' => 'Error correction date (To) should be after the From date',

	];
}
?>