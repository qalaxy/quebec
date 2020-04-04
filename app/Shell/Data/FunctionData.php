<?php 
namespace App\Shell\Data;

class FunctionData{
	public $rows = 15;

	public $name_key = 'name';
	public $description_key = 'description';
	public $abbreviation_key = 'abbreviation';
	public $product_id_key = 'product_id';

	public $name_req = 'required|regex:/^([a-zA-Z\'\ ]+)$/';
	public $description_req = 'nullable|max:255';
	public $abbreviation_req = 'required|alpha_num';
	public $product_id_req = 'required|uuid';

	public $function_data_validation_msgs = [
		'name.required' => 'You have not entered name',
		'name.regex' => 'Product name should have alphabets, spaces and apostrophe only',
		'name.unique' => 'The product name you entered already exists',
		'name.min' => 'The product name should have 3 or more characters',

		'description.max' => 'Product description should not contain more than 255 characters',

		'abbreviation.required' => 'You have not entered AIM function abbreviation',
		'abbreviation.alpha_num' => 'Abbreviation should be alphabets and numerals only',
	];

	public $validate_function_data_mgs = [
		'product_id.required' => 'You have not selected an AIM product to be added',
		'product_id.uuid' => 'Value for the product should be a UUIDs',
	];
	
}
?>