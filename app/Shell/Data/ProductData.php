<?php 
namespace App\Shell\Data;

class ProductData{
	public $rows = 15;
	public $name_key = 'name';
	public $description_key = 'description';

	public $name_req = 'required|regex:/^([a-zA-Z\'\ ]+)$/';
	public $description_req = 'nullable|max:255';

	public $product_data_validation_msgs = [
		'name.required' => 'You have not entered name',
		'name.regex' => 'Product name should have alphabets and apostrophe only',
		'name.unique' => 'The product name you entered already exists',
		'name.min' => 'The product name should have 3 or more characters',

		'description.max' => 'Product description should not contain more than 255 characters',
	];

}
?>