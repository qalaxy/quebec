<?php 
namespace App\Shell\Web\Executor;

use Exception;

use App\Shell\Data\FunctionData;
use App\Shell\Web\Base;

class FunctionExe extends Base{
	private $f_data;
	protected $data = array();

	public function __construct(){
		$this->f_data = new FunctionData();
	}
}

?>