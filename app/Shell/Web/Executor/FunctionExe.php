<?php 
namespace App\Shell\Web\Executor;

use Exception;

use App\Func;

use App\Shell\Data\FunctionData;
use App\Shell\Web\Base;

class FunctionExe extends Base{
	private $f_data;
	protected $data = array();

	public function __construct(){
		$this->f_data = new FunctionData();
	}

	protected function storeFunction(){
		try{
			$func = Func::create(array($this->f_data->name_key => ucwords($this->data[$this->f_data->name_key]),
				$this->f_data->abbreviation_key => strtoupper($this->data[$this->f_data->abbreviation_key]),
				$this->f_data->description_key => ucfirst($this->data[$this->f_data->description_key]),));
			if(is_null($func)){
				throw new Exception('AIM function has not been created successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'AIM function has been created successfully', 'uuid'=>$func->uuid);
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $func;
	}

	protected function updateFunction(Func $func){
		try{
			$func = $func->update(array($this->f_data->name_key => ucwords($this->data[$this->f_data->name_key]),
				$this->f_data->abbreviation_key => strtoupper($this->data[$this->f_data->abbreviation_key]),
				$this->f_data->description_key => ucfirst($this->data[$this->f_data->description_key]),));
			if(is_null($func)){
				throw new Exception('AIM function has not been updated successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'AIM function has been updated successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $func;
	}

	protected function destroyFunction(Func $function){
		try{
			$function = $function->delete();

			if(is_null($function)){
				throw new Exception('AIM function has not been deleted successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'AIM function has been deleted successfully');
			}

		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $function;
	}

	protected function storeFunctionProduct(Func $function, Product $product){
		try{
			$func_prod = $function->product()->attach($product);
			if($func_prod){
				throw new Exception('Product has not been added to the function successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Product has been added to the function successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $product;
	}

	protected function destroyFunctionProduct(Func $func, Product $product){
		try{
			$func_prod = $func->product()->detach($product);
			if(is_null($func_prod)){
				throw new Exception('Product has not been removed from the function successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Product has been removed from the function successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $func_prod;
	}
}

?>