<?php 
namespace App\Shell\Data;

class PermissionData{
	public $name_key = 'name';
	public $display_name_key = 'display_name';
	public $description_key = 'description';
	
	public $name_req = 'required|alpha_num';
	public $display_name_req = 'required|regex:/[a-zA-Z0-9\ \-\.]+/';
	public $description_req = 'max:255';
	
	public $validationMsgs = [
				'name.required'=>'You have not entered name',
				'name.alpha_num'=>'Name should be alphabets, numbers and underscores only',
				'display_name.required'=>'You have not entered display name',
				'display_name.regex'=>'Display name should have alphabets, numerals, spaces, dashes and dots only',
				'description.max'=>'Description should not be more than 255 characters',
				
			];
}
?>