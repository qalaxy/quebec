<?php 
namespace App\Shell\Data;


class AccountData{
	public $rows = 15;
	
	public $name_key = 'name';
	public $password_key = 'password';
	public $first_name_key = 'first_name';
	public $middle_name_key = 'middle_name';
	public $last_name_key = 'last_name';
	public $p_number_key = 'p_number';
	public $phone_number_key = 'phone_number';
	public $number_key = 'number';
	public $email_key = 'email';
	public $address_key = 'address';
	public $station_id_key = 'station_id';
	public $user_id_key = 'user_id';
	public $status_key = 'status';
	public $from_key = 'from';
	public $to_key = 'to';
	public $account_id_key = 'account_id';
	public $level_id_key = 'level_id';
	public $role_id_key = 'role_id';
	
	public $name_req = 'required|regex:/^([a-zA-Z\']+)$/|min:3';
	public $optional_name_req = 'nullable|regex:/^([a-zA-Z\']+)$/|min:3';
	public $p_number_req = 'required|digits:9';
	public $station_id_req = 'required|uuid';
	public $phone_number_req = 'required|digits:10|starts_with:07,08';
	public $email_req = 'required|email';
	public $from_req = 'required|date|before_or_equal:today';
	public $to_req = 'nullable|date|after_or_equal:from';
	public $status_req = 'required|boolean';
	public $role_id_req = 'required|uuid';
	
	public $validation_msgs = [
		'first_name.required'=>'You have not entered first name',
		'first_name.regex'=>'First name should have alphabets and apostrophe  only',
		'first_name.min'=>'First name should not have less than 3 characters',
		'middle_name.regex'=>'Middle name should have alphabets and apostrophe only',
		'middle_name.min'=>'Middle name should not have less than 3 characters',
		'last_name.required'=>'You have not entered last name',
		'last_name.regex'=>'Last name should have alphabets and apostrophe only',
		'last_name.min'=>'Last name should not have less than 3 characters',
		'p_number.required'=>'You have not entered personal number',
		'p_number.digits'=>'Personal number should be numeric and have 9 digits',
		'p_number.unique'=>'Personal number has already been entered for another user',
		'station_id.required'=>'You have not selected station',
		'station_id.uuid'=>'Station value should be a UUID',
		'phone_number.required'=>'You have not entered phone number',
		'phone_number.digits'=>'Phone number should be numeric and have 10 digits',
		'phone_number.starts_with'=>'Phone number should start with 07 or 08',
		'email.required'=>'You have not entered email address',
		'email.email'=>'Email address should have correct email format',
		'email.unique'=>'Email address has been assigned to another user',
		
		'from.required'=>'You have not enter the date officer begin being at the station',
		'from.date'=>'From field should have a date entry',
		'from.before_or_equal'=>'The date officer is joining a station should be today before today',
		'to.required'=>'You have not enter the date officer end being at the station',
		'to.date'=>'To field should have a date entry',
		'to.after_or_equal'=>'The date officer cease being in a station should be later than the date the officer joined the station',
		'status.required'=>'You have not entered the status of user being in the station',
		'status.boolean'=>'Status value should either be 1 or 0',
	];
	
	public $station_validation_msgs = [
		'station_id.required'=>'You have not selected station',
		'station_id.uuid'=>'Station value should be a UUID',
		'from.required'=>'You have not enter the date officer begin being at the station',
		'from.date'=>'From field should have a date entry',
		'from.before_or_equal'=>'The date officer is joining a station should be today before today',
		'to.required'=>'You have not enter the date officer end being at the station',
		'to.date'=>'To field should have a date entry',
		'to.after_or_equal'=>'The date officer cease being in a station should be later than the date the officer joined the station',
		'status.required'=>'You have not selected the status of user being in the station',
		'status.boolean'=>'Status value should either be 1 or 0',
	];
	
	public $supervisory_validation_msgs = [
		'station_id.required'=>'You have not selected station',
		'station_id.uuid'=>'Station value should be a UUID',
		'from.required'=>'You have not enter the date officer became a supervisor at the station',
		'from.date'=>'From field should have a date entry',
		'from.before_or_equal'=>'The date officer became a supervisor should be a date before today',
		'to.required'=>'You have not enter the date officer end being a supervisor',
		'to.date'=>'To field should have a date entry',
		'to.after_or_equal'=>'The date officer cease being a supervisor should be later than the date the officer became a supervisor',
		'status.required'=>'You have not selected the status of officer being a supervisor at the station',
		'status.boolean'=>'Status value should either be 1 or 0',
	];
	
	public $role_validation_msgs = [
		'role_id.required'=>'You have not selected a role',
		'role_id.uuid'=>'Role value should be uuid',
	];
	
	public $account_credentials_validation_msgs = [
		'email.required' => 'You have not entered email address',
		'email.email' => 'Enter correct format of email address',
		'email.string' => 'Email address should be a string of characters',
		'email.min' => 'Email address should have more than 255 characters',
		'email.unique' => 'Another user is currently using this email address you have entered',

		'old_password.required' => 'You have not entered the old password',
		'old_password.min' => 'Old password should be a string of 8 or more characters',
		'old_password.string' => 'Old password should be a string of characters',

		'password.required' => 'You have not entered a new password',
		'password.min' => 'New password should be a string of 8 or more characters',
		'password.string' => 'New password should be a string of characters',
		'password.confirm' => 'The new password and its confirmation do not match',
	];
}
?>