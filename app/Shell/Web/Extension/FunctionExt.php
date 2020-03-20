<?php 
namespace App\Shell\Web\Extension;

use Exception;
use App\Func;

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

}
?>