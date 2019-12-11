<?php 
namespace App\Shell\Data;

class StationData{
	public $rows = 5;
	
	public $function_id_key = 'function_id';
	public $user_id_key = 'user_id';
	public $station_id_key = 'station_id';
	
	public $function_id_req = 'required|uuid';
	public $user_id_req = 'required|uuid';
	
	public $station_function_validation_msgs = [
		'function_id.required'=>'You have not selected an AIS function',
		'function_id.uuid'=>'Values for AIS function should be uuid',
	];
	
	public $station_user_validation_msgs = [
		'user_id.required'=>'You have not selected any user',
		'user_id.uuid'=>'Values for the user should be uuid',
	];
}

?>