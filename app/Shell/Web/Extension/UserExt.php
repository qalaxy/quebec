<?php 
namespace App\Shell\Web\Extension;

use App\Shell\Web\Base;
use App\Shell\Data\UserData;

class UserExt extends Base{
	private $user_data;
	
	public function __construct(){
		$this->user_data = new UserData();
	}
	
	
}

?>