<?php 
namespace App\Shell\Web\Executor;

use App\Shell\Web\Base;
use App\Shell\Data\UserData;

class UserExe extends Base{
	protected $data = array();
	
	private $user_data;
	
	public function __construct(){
		$this->user_data = new UserData();
	}
	
}

?>