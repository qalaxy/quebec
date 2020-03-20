<?php 
namespace App\Shell\Web\Executor;

use Exception;

use App\Shell\Web\Base;
use App\Shell\Data\SystemErrorData;

class SystemErrorExe extends Base{
	private $se_data;

	public function __construct(){
		$this->se_data = new SystemErrorData();
	}
}
?>