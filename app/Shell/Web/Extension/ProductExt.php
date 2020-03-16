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
}

?>