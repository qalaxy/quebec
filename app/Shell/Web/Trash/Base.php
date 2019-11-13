<?php 
namespace App\Shell\Web;

use Illuminate\Support\Facades\Auth;
class Base{

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
					'user' => Auth::id(),
				);
			array_push($data, $new_record);
			ftruncate($file, 0);
		}else{
			$file = fopen($url, 'w');
			$new_record = array('sn'=>1,
						'record' => $this->getRecord($record),
						'datetime' => date('Y-m-d h:i:s'),
						'user' => Auth::id(),
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
						$data[$i]['user'] = Auth::id();
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
	
	protected function prepareSearchParam(array $data){
		//get keys
		$keys = array_keys($data);
		//get data
		for($i = 0; $i < count($keys); $i++){
			if(is_null($data[$keys[$i]]))
				unset($data[$keys[$i]]);
			else
		}
		//prepare data here
		return $data;
	}
}

?>