<?php 
namespace App\Shell\Data;

class StationData{
	public $rows = 15;
	
	public $function_id_key = 'function_id';
	public $user_id_key = 'user_id';
	public $station_id_key = 'station_id';
	public $account_id_key = 'account_id';
	public $status_key = 'status';
	public $from_key = 'from';
	public $to_key = 'to';
	
	public $function_id_req = 'required|uuid';
	public $user_id_req = 'required|uuid';
	public $from_req = 'required|date|before_or_equal:today';
	public $to_req = 'nullable|date|after_or_equal:from';
	public $status_req = 'required|boolean';
	
	public $station_function_validation_msgs = [
		'function_id.required'=>'You have not selected an AIS function',
		'function_id.uuid'=>'Values for AIS function should be uuid',
	];
	
	public $station_user_validation_msgs = [
		'user_id.required'=>'You have not selected any user',
		'user_id.uuid'=>'Values for the user should be uuid',
	];

	public $station_supervisor_validation_msgs = [
		'account_id.required' => 'You have not selected officer to be a supervisor',
		'account_id.uuid' => 'Value for officer should be uuid',

		'status.required' => 'You have not selected supervisory status of the officer',
		'status.boolean' => 'Value for status should be 1 or 0',

		'from.required' => 'You have not entered the date the officer begins as a supervisor in the station',
		'from.date' => 'Value entered should be date',
		'from.before_or_equal' => 'Date entered should be equal to or ealier than today',

		'to.date' => 'Value entered should be date',
		'to.after_or_equal' => 'Date entered should be equal to or later than the date officer begun as a supervisor at the station',
	];
}

?>