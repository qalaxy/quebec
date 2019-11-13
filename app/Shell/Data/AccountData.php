<?php 
namespace App\Shell\Data;


class AccountData{
	public $rows = 5;
	
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
	
	public $name_req = 'required|regex:/[a-zA-Z\ \']/|min:3';
	public $optional_name_req = 'nullable|regex:/[a-zA-Z\ \']/|min:3';
	public $p_number_req = 'required|digits:9';
	public $station_id_req = 'required|uuid';
	public $phone_number_req = 'required|digits:10|starts_with:07,08';
	public $email_req = 'required|email';
	
	public $validation_msgs = [
		'first_name.required'=>'You have not entered first name',
		'first_name.regex'=>'First name should have alphabets, apostrophe and spaces only',
		'first_name.min'=>'First name should not have less than 3 characters',
		'middle_name.regex'=>'Middle name should have alphabets, apostrophe and spaces only',
		'middle_name.min'=>'Middle name should not have less than 3 characters',
		'last_name.required'=>'You have not entered last name',
		'last_name.regex'=>'Last name should have alphabets, apostrophe and spaces only',
		'last_name.min'=>'Last name should not have less than 3 characters',
		'p_number.required'=>'You have not entered personal number',
		'p_number.digits'=>'Personal number should be numeric and have 9 digits',
		'station_id.required'=>'You have not selected station',
		'station_id.uuid'=>'Station value should be a UUID',
		'phone_number.required'=>'You have not entered phone number',
		'phone_number.digits'=>'Phone number should be numeric and have 10 digits',
		'phone_number.starts_with'=>'Phone number should start with 07 or 08',
		'email.required'=>'You have not entered email address',
		'email.email'=>'Email address should have email format',
	];
	
}
?>