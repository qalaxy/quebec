<?php 
namespace App\Shell\Web\Extension;

use App\Shell\Web\Base;
use App\Shell\Data\ErrorData;

class ErrorExt extends Base{
	private $error_data;
	
	public function __construct(){
		$this->error_data = new ErrorData();
	}
}
?>