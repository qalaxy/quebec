<?php 
namespace App\Shell\Web\Monitor;

use Illuminate\Support\Facades\DB;

use App\Shell\Web\Executor\FunctionExe;

class FunctionMnt extends FunctionExe{
	public function createFunction(array $data){
		$this->data = $data;

		DB::beginTransaction();
		$fucn = $this->storeFunction();
		if(is_null($fucn)){
			DB::rollback();
			return $this->error;
		}

		DB::rollback();
		return $this->success;
	}

	public function editFunction(array $data, $func){
		$this->data = $data;

		DB::beginTransaction();
		$func = $this->updateFunction($func);
		if(is_null($func)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}

	public function deleteFunction($function){
		DB::beginTransaction();
		$func = $this->destroyFunction($function);
		if(is_null($func)){
			DB::rollback();
			return $this->error;
		}

		DB::rollback();
		return $this->success;
	}

	public function createFunctionProduct($function, $product){
		$func_prod = $this->storeFunctionProduct($function, $product);
		if(is_null($func_prod)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}

	public function deleteFunctionProduct($func, $product){
		$func_prod = $this->destroyFunctionProduct($func, $product);
		if(is_null($func_prod)){
			DB::rollback();
			return $this->error;
		}

		DB::commit();
		return $this->success;
	}
}

?>