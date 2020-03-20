<?php
namespace App\Shell\Web\Extension;

use Exception;

use App\Shell\Web\Base;
use App\Shell\Data\SystemErrorData;

class SystemErrorExt extends Base{
	private $se_data;

	public function __construct(){
		$this->se_data = new SystemErrorData();
	}
}
 ?>
