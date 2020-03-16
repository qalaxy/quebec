<?php 
namespace App\Shell\Web\Executor;

use Exception;

use App\Shell\Data\ProductData;
use App\Shell\Web\Base;

class ProductExe extends Base{
	protected $data = array();

	private $p_data;

	public function __construct(){
		$this->p_data = new ProductData();
	}
}
?>