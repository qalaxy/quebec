<?php 
namespace App\Shell\Web\Executor;

use Exception;

use App\Product;
use App\Shell\Data\ProductData;
use App\Shell\Web\Base;

class ProductExe extends Base{
	protected $data = array();

	private $p_data;

	public function __construct(){
		$this->p_data = new ProductData();
	}

	protected function storeProduct(){
		try{
			$product = Product::create(array(
						$this->p_data->name_key => ucwords($this->data[$this->p_data->name_key]),
						$this->p_data->description_key => ucfirst($this->data[$this->p_data->description_key]),
					));

			if(is_null($product)){
				throw new Exception('Product has not been created successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Product has been created successfully', 'uuid'=>$product->uuid) ;
			}
		}catch(Exception $e){
			$this->error = array('indicator' => 'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $product;
	}

	protected function updateProduct($product){
		try{
			$product = $product->update(array(
						$this->p_data->name_key => ucwords($this->data[$this->p_data->name_key]),
						$this->p_data->description_key => ucfirst($this->data[$this->p_data->description_key]),
					));

			if(is_null($product)){
				throw new Exception('Product has not been updated successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Product has been updated successfully') ;
			}
		}catch(Exception $e){
			$this->error = array('indicator' => 'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $product;
	}

	protected function destroyProduct($product){
		try{
			$product = $product->delete();
			if(is_null($product)){
				throw new Exception('Product has not been deleted successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Product has been deleted successfully') ;
			}
		}catch(Exception $e){
			$this->error = array('indicator' => 'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $product;
	}
}
?>