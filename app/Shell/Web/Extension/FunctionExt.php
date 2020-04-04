<?php 
namespace App\Shell\Web\Extension;

use Exception;
use App\Func;
use App\Product;

use App\Shell\Web\Base;
use App\Shell\Data\FunctionData;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FunctionExt extends Base{
	private $f_data;

	public function __construct(){
		$this->f_data = new FunctionData();
	}

	public function searchFunctions(array $data){
		try{
			$functions = Func::where($this->prepareSearchParam($data, ['name']))->paginate($this->f_data->rows);
			if(is_null($functions)){
				throw new Exception('Functions have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $functions;
	}
	
	public function getPaginatedFunctions(){
		try{
			$functions = Func::paginate($this->f_data->rows);
			if(is_null($functions)){
				throw new Exception('Functions have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $functions;
	}

	public function getFunction(string $uuid){
		try{
			$func = Func::withUuid($uuid)->first();
			if(is_null($func)){
				throw new Exception('Function has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $func;
	}

	public function validateFunctionData(array $data, $uuid=null){
		$rules = [
			$this->f_data->name_key => ['required', 
										'regex:/^([a-zA-Z\'\ ]+)$/', 'min:3',
									(isset($uuid) ? Rule::unique('functions')
										->ignore(Func::withUuid($uuid)->first()) 
										: 'unique:functions')],
			$this->f_data->abbreviation_key => $this->f_data->abbreviation_req,
			$this->f_data->description_key => $this->f_data->description_req,
		];

		return Validator::make($data, $rules, $this->f_data->function_data_validation_msgs);
	}

	public function deleteFunction(object $function){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Delete AIM function</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-8 w3-large">Your are about to delete a function:</p>
						<p><strong>Name:</strong> '.$function->name.'<br /> '.((strlen($function->description) < 1) ? '' : '<br /><strong>Description:</strong> '.$function->description).'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-function').'/'.$function->uuid.'" title="Delete functions">Delete&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}


	public function getFunctionProducts(object $func){
		try{
			$products = $func->product()->paginate($this->f_data->rows);
			if(is_null($products)){
				throw new Exception('Products for the function have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $products;
	}

	public function getUnaddedProducts(object $func){
		try{
			$products = DB::table('products')
								->whereNotIn('products.id', function($query) use($func){
									$query->select(DB::raw('product_id'))
									->from('function_product')
									->whereRaw('function_product.function_id='.$func->id);
								})
								->whereNull('products.deleted_at')
								->select('products.*')
								->get();
			if(is_null($products)){
				throw new Exception('Products have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $products;
	}

	public function validateFunctionProductData(array $data){
		$rules = [
			$this->f_data->product_id_key => $this->f_data->product_id_req,
		];

		return Validator::make($data, $rules, $this->f_data->validate_function_data_mgs);
	}

	public function getProduct(string $uuid){
		try{
			$product = product::withUuid($uuid)->first();
			if(is_null($product)){
				throw new Exception('Product has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $product;
	}

	public function deleteFunctionProduct(object $function, object $product){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Remove product from '.$function->name.'</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-8 w3-large">Your are about to remove a product from an AIM function:</p>
						<p><strong>Product name:</strong> '.$product->name.'<br /> '.((strlen($product->description) < 1) ? '' : '<br /><strong>Description:</strong> '.$product->description).'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-function-product').'/'.$function->uuid.'/'.$product->uuid.'" title="Remove a product">Remove&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}
}
?>