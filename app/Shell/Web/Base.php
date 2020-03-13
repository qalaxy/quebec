<?php 
namespace App\Shell\Web;

use Illuminate\Support\Facades\Auth;
class Base{

	public $missing_view = array('indicator'=>'information', 'message'=>'The web application interface is missing');
	public $under_construction = array('indicator'=>'information', 'message'=>'The page is under construction');
	public $validation = array('indicator'=>'warning', 'message'=>'Correct the flagged input fields appropriately');
	
	protected function checkUuid($uuid){
		
	}
	
	protected function recordValidator(object $old, object $new, array $keys){
		for($i = 0; $i < count($keys); $i++){
			if($old->$keys[$i] != $new->$keys[$i]){
				return true;
				break;
			}
		}
	}
	
	protected function archiver(object $record){
		$table = $record->getTable();
		do{
			$f_status = $this->fileLocker($table, $record, false);
		}while(is_null($f_status));
		
		$data = array();
		$url = asset('App\Shell\Data\Archive\\'.$table.'\\'.$record->uuid.'.json');
		$file = fopen($url);
		if($file){
			$data = fread($file, filesize($url));
			
			$data = json_decode($data, true);
			$new_record = array('sn'=>$data(count)+1,
					'record' => $this->getRecord($record),
					'datetime' => date('Y-m-d h:i:s'),
					'user' => Auth::user()->uuid,
				);
			array_push($data, $new_record);
			ftruncate($file, 0);
		}else{
			$file = fopen($url, 'w');
			$new_record = array('sn'=>1,
						'record' => $this->getRecord($record),
						'datetime' => date('Y-m-d h:i:s'),
						'user' => Auth::user()->uuid,
					);
			array_push($data, $new_record);
		}
		$fw = fwrite($file, json_encode($data));
		fclose($file);
		$this->fileLocker($table, $record, true);
		return $fw;
	}
	
	private function fileLocker($table, $record, $status){
		$url = asset('App\Shell\Data\Archive\locker.json');
		$data = array();
		$locker = fopen($url);
		if($locker){
			$data = json_decode(fread($locker, filesize($url)));
			for($i = 0; $i < count($data); $i++){
				if($data[$i]['file'] == $record->uuid){
					if($data[$i]['status'] != $status){
						$data[$i]['status'] = $status;
						$data[$i]['user'] = Auth::user()->uuid;
						ftruncate($locker, 0);
					}
					fclose($locker);
					return null;
				}
			}
		}else{
			$locker = fopen($url, 'w');
			array_push($data, array('file'=>$record->uuid, 'status'=>$status, 'user'=>Auth::id()));
		}
		$fw = fwrite($locker, json_encode($data));
		fclose($locker);
		return $fw;
	}
	
	private function getRecord(object $record){
		$keys = array_keys($record->attributes);
		
		for($i = 0; $i < count($keys); $i++){
			$data[$keys[$i]] = $record->keys[$i];
		}
		return $data;
	}
	
	
	
	protected function sendDeveloperEmail(object $record){
		//
	}
	
	protected function prepareSearchParam(array $request, array $keys){		
		$params = array(); $data = array();
		
		if(session()->has('params')){
			for($i = 0; $i < count($keys); $i++){
				if(array_key_exists($keys[$i], $request))
					$data[$keys[$i]] = $request[$keys[$i]];
			}
			if(empty($data))
				$data = session('params');
		}else{
			$data = $request;
		}
		
		for($i = 0; $i < count($keys); $i++){
			if(array_key_exists($keys[$i], $data)){
				if(is_null($data[$keys[$i]]))
					continue;
				else
					array_push($params, [$keys[$i], $data[$keys[$i]]]);
			}
		}
		session()->flash('params', $data);
		return $params;
	}
	
	protected function stationIds($stations){
		$ids = array();
		foreach($stations as $station){
			array_push($ids, $station->id);
		}
		return $ids;
	}
	
	public function invalidDeletion($msg=null, $indicator=null){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container '.($indicator ?: "w3-theme").'"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Invalid deletion</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-8 w3-large">'.($msg ?: "Sorry, your deletion request is not valid").'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<button class="w3-button w3-large w3-theme w3-hover-light-blue" title="Dismiss" onclick="document.getElementById(\'delete\').style.display=\'none\'">OK&nbsp;</button>
							</div>
						</div>
					</footer>
				</div>';
	}

	public function invalidRequest(){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-theme"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Invalid deletion</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-8 w3-large">Sorry, your request is not valid</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<button class="w3-button w3-large w3-theme w3-hover-light-blue" title="Dismiss" onclick="document.getElementById(\'delete\').style.display=\'none\'">OK&nbsp;</button>
							</div>
						</div>
					</footer>
				</div>';
	}
}

?>