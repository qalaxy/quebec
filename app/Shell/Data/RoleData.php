<?php 
namespace App\Shell\Data;

class RoleData{
	public $rows = 2;
	
	public $name_key = 'name';
	public $display_name_key = 'display_name';
	public $description_key = 'description';
	public $permission_key = 'permission';
	public $permission_id_key = 'permission_id';
	public $role_id_key = 'role_id';
	public $owner_id_key = 'owner_id';
	public $global_key = 'global';
	public $stations_key = 'stations';
	
	//public $name_req = 'required|alpha_dash';
	public $name_req = 'required|regex:/[a-zA-Z0-9\ \-\.]+/|unique:roles';
	public $description_req = 'max:255';
	public $global_req = 'required|integer|regex:/[123]?/';
	public $stations_req = 'sometimes|required_if:global,2|array';
	public $station_id_req = 'uuid';
	public $permission_req = 'required|uuid';
	
	public $roleValidationMsgs = [
				'name.required'=>'You have not entered the name of the role',
				'name.regex'=>'Name should have alphabets, numerals, spaces, dashes and dots only',
				'name.unique'=>'Another role with this name already exists',
				
				'description.max'=>'Description should not be more than 255 characters',
				
				'global.required'=>'You have not entered role applicability',
				'global.integer'=>'Role applicability value should be integer',
				'global.regex'=>'Role applicability value should be either 1, 2 or 3 only',
				
				'stations.required'=>'You have not selected stations',
				'stations.array'=>'Values of stations should be in array',
				
				'stations.*.uuid'=>'Values of stations should be uuid',

			];
			
	public $rolePermValidationMsgs = [
				'permission.required'=>'You have not selected a permission',
				'permission.uuid'=>'Entry for permission should be UUID',
			];
			
	
}
?>