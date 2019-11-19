<?php 
namespace App\Shell\Web\Executor;

use App\Shell\Web\Base;
use App\Shell\Data\ErrorData;

class ErrorExe extends Base{
	private $error_data;
	protected $data = array();
	
	public function __construct(){
		$this->error_data = new ErrorData();
	}
	
}

?>