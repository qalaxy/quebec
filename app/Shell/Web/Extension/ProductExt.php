<?php 
namespace App\Shell\Web\Extension;

use Exception;
use App\Product;

use App\Shell\Web\Base;
use App\Shell\Data\ProductData;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ProductExt extends Base{
	private $p_data;

	public function __construct(){
		$this->p_data = new ProductData();
	}

	public function searchProducts(array $data){
		try{
			$products = Product::where($this->prepareSearchParam($data, ['name']))->paginate($this->p_data->rows);
			if(is_null($products)){
				throw new Exception('Products have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $products;
	}
	
	public function getPaginatedProducts(){
		try{
			$products = Product::paginate($this->p_data->rows);
			if(is_null($products)){
				throw new Exception('Products have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $products;
	}

	public function validateProductData(array $data, $uuid=null){
		$rules = [
			$this->p_data->name_key => ['required', 
										'regex:/^([a-zA-Z\'\ ]+)$/', 'min:3',
									(isset($uuid) ? Rule::unique('products')
										->ignore(Product::withUuid($uuid)->first()) 
										: 'unique:products')],
			$this->p_data->description_key => $this->p_data->description_req,
		];

		return Validator::make($data, $rules, $this->p_data->product_data_validation_msgs);
	}

	public function getProduct(string $uuid){
		try{
			$product = Product::withUuid($uuid)->first();
			if(is_null($product)){
				throw new Exception('Product has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $product;
	}

	public function deleteProduct(object $product){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Delete product</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-8 w3-large">Your are about to delete a product:</p>
						<p><strong>Name:</strong> '.$product->name.'<br /> '.((strlen($product->description) < 1) ? '' : '<br /><strong>Description:</strong> '.$product->description).'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-product').'/'.$product->uuid.'" title="Delete product">Delete&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}
}

?>