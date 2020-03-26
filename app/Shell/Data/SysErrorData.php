<?php 
namespace App\Shell\Data;

class SysErrorData{
	public $rows = 15;

	public $number_key = 'number';
	public $station_id_key = 'station_id';
	public $system_id_key = 'system_id';
	public $description_key = 'description';
	public $solution_key = 'solution';
	public $from_key = 'from';
	public $to_key = 'to';
	public $state_id_key = 'state_id';
	public $remarks_key = 'remarks';

	public $station_id_req = 'required|uuid';
	public $system_id_req = 'required|uuid';
	public $description_req = 'required|string|max:255';
	public $solution_req = 'required|string|max:255';
	public $from_req = 'required|date';
	public $to_req = 'required|date';
	public $remarks_req = 'required|max:255';

	public $sys_data_validation_msgs = [
		'station_id.required' => 'You have not selected station',
		'station_id.uuid' => 'Value for station should be a uuid',

		'system_id.required' => 'You have not selected system',
		'system_id.uuid' => 'Value for system should be a uuid',

		'description.required' => 'You have not entered system error description',
		'description.string' => 'The value for system error description should be a string',
		'description.max' => 'The characters in system error description should not be more than 255',

		'solution.required' => 'You have not entered solution to system error',
		'solution.string' => 'The value for system error solution should be a string',
		'solution.max' => 'The characters in system error solution should not be more than 255',

		'from.required' => 'You have not entered the date the error began appearing',
		'from.date' => 'Value should be a date',

		'to.required' => 'You have not entered the date the error was solved',
		'to.date' => 'Value should be a date',

		'remarks.required' => 'You have not entered remarks on system error',
		'remarks.max' => 'The characters in system error remarks should not be more than 255',
	];
}
?>