<?php 
namespace App\Shell\Web\Monitor;

use Illuminate\Support\Facades\DB;

use App\Shell\Web\Executor\ProductExe;

class ProductMnt extends ProductExe{
	public function createProduct(array $data){
		$this->data = $data;

		DB::beginTransaction();
		$product = $this->storeProduct();
		if(is_null($product)){
			DB::rollback();
			return $this->error;
		}

		DB::commit();
		return $this->success;
	}

	public function editProduct(array $data, $product){
		$this->data = $data;
		DB::beginTransaction();
		$product = $this->updateProduct($product);
		if(is_null($product)){
			DB::rollback();
			return $this->error;
		}

		DB::commit();
		return$this->success;
	}

	public function deleteProduct($product){
		DB::beginTransaction();

		$product = $this->destroyProduct($product);
		if(Is_null($product)){
			DB::rollback();
			return $this->error;
		}

		DB::rollback();
		return $this->success;
	}
}
?>