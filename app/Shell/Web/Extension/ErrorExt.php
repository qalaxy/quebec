<?php 
namespace App\Shell\Web\Extension;

use Exception;
use App\Account;
use App\Error;
use App\ErrorNotification;
use App\State;
use App\Func;
use App\Product;
use App\Station;

use Illuminate\Support\Str;
use App\Mail\ErrorNotificationEmail;
use App\Mail\ErrorOriginatorNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Shell\Web\pdf\fpdf\FPDF;
use App\Shell\Web\pdf\cellfit\FPDF_CellFit;

use App\Shell\Web\Base;
use App\Shell\Data\ErrorData;

class ErrorExt extends Base{
	private $error_data;
	private $pdf;
	private $cellfit;
	private $total_rows;
	
	public function __construct(){
		$this->error_data = new ErrorData();
		$this->pdf = new FPDF();
		$this->cellfit = new FPDF_CellFit();
	}
	
	public function searchErrors(array $data){
		try{
			$errors = Error::where($this->prepareSearchParam($data, ['user_id', 'function_id', 'station_id', 'date_created', 'time_created', 'responsibility']))
								->orderBy('id', 'desc')
								->paginate($this->error_data->rows);
			if(is_null($errors)){
				throw new Exception('Errors could not be retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $errors;
	}
	
	public function getPaginatedErrors(){
		try{
			$errors = Error::orderBy('id', 'desc')->paginate($this->error_data->rows);
			if(is_null($errors)){
				throw new Exception('Erros could not be retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $errors;
	}
	
	public function getErrors(){
		try{
			$errors = Error::all();
			if(is_null($errors)){
				throw new Exception('Errors have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $errors;
	}
	
	public function getFunctions(){
		try{
			$functions = Func::all();
			if(is_null($functions)){
				throw new Exception('Functions have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $functions;
	}
	
	public function getStations(){
		try{
			$stations = Station::all();
			if(is_null($stations)){
				throw new Exception('Stations have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $stations;
	}
	
	public function getErrorStatus(){
		try{
			$status = State::all();
			if(is_null($status)){
				throw new Exception('Error status have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $status;
	}
	
	public function getStationFunctions(object $station){
		$data = array();
		foreach($station->func()->get() as $func){
			array_push($data, ['id'=>$func->uuid, 'name'=>$func->name]);
		}
		return $data;
	}
	
	public function validateErrorData(array $data){
		$rules = [
				$this->error_data->station_id_key => $this->error_data->station_id_req,
				$this->error_data->function_id_key => $this->error_data->function_id_req,
				//$this->error_data->date_time_created_key => $this->error_data->date_time_created_req,
				$this->error_data->description_key => $this->error_data->description_req,
				$this->error_data->impact_key => $this->error_data->impact_req,
				$this->error_data->remarks_key => $this->error_data->remarks_req,
				$this->error_data->responsibility_key => $this->error_data->responsibility_req,
				$this->error_data->notification_message_key => $this->error_data->notification_message_req,
		];
		
		/*$this->error_data->error_data_validation_msgs['date_time_created.before'] = Str::replaceArray('?', 
								[date_format(date_create($data[$this->error_data->date_time_created_key]), 'd/m/Y H:i:s'), 
								date('d/m/Y H:i:s', strtotime(strval(today()).' + 1days'))],
								$this->error_data->error_data_validation_msgs['date_time_created.before']);*/
								
		return Validator::make($data, $rules, $this->error_data->error_data_validation_msgs);
	}
	
	public function getFunction($uuid){
		try{
			$function = Func::withUuid($uuid)->first();
			if(is_null($function)){
				throw new Exception('Function has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $function;
	}
	
	public function getStation($uuid){
		try{
			$station = Station::withUuid($uuid)->first();
			if(is_null($station)){
				throw new Exception('Station has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $station;
	}
	
	public function getNotificationRecipients(object $station){
		try{
			$recipients = $station->recipient()->get();
			if(is_null($recipients)){
				throw new Exception('Recipients have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $recipients;
	}
	
	public function getErrorNumber($station_id, $function_id){
		try{
			$number = Error::withTrashed()->where($this->error_data->function_id_key, $function_id)
								->where($this->error_data->station_id_key, $station_id)
								->where('created_at', '>=', date_create(date('Y').'-01-01 00:00:00'))
								->where('created_at', '<', date_create(date('Y', strtotime(' + 1 year')).'-01-01 00:00:00'))
								->count();
			if(is_null($number)){
				throw new Exception('Number of functional error could not be retrieved successfully');
			}else{
				$number++;
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $number;
	}
	
	public function sendErrorNotificationEmail($uuid, $recipients){
		$error = $this->getError($uuid);
		if(!is_object($error)) return $error;
		
		$addresses = $this->prepareRecipientEmails($recipients, $error);
		
		if($error->station()->first()->email()->first()){
			array_push($addresses, $error->station()->first()->email()->first()->address);
		}
		
		if(count($addresses)){
			for($i = 0; $i < count($addresses); $i++){
				try{
					$email = Mail::to($addresses[$i])->send(new ErrorNotificationEmail($error));
					if($email){
						throw new Exception('Email to '.$addresses[$i].' has not been sent successfully');
					}
				}catch(Exception $e){
					return $e->getMessage();
				}	
			}
		}else return 'Addressees for email notification could not be found';
		
	}
	
	private function prepareRecipientEmails($recipients, $error){
		$addresses = array();
		foreach($recipients as $recipient){
			foreach($recipient->user()->first()->account()->first()->email()->get() as $email){
				array_push($addresses, $email->address);
			}
		}
		
		return $addresses;
	}
	
	public function getError($uuid){
		try{
			$error = Error::withUuid($uuid)->first();
			if(is_null($error)){
				throw new Exception('Functional error has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $error;
	}
	
	public function validateErrorProductData(array $data){
		$rules = [
			$this->error_data->product_id_key => $this->error_data->product_id_req,
			$this->error_data->product_identification_key => $this->error_data->product_identification_req,
		];
		
		return Validator::make($data, $rules, $this->error_data->error_product_validation_msgs);
	}
	
	public function getProduct($uuid){
		try{
			$product = Product::withUuid($uuid)->first();
			if(is_null($product)){
				throw new Exception('Product has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $product;
	}
	
	public function getJsonStationAccounts(object $station){
		$accounts = array();
		foreach($station->accountStation()->get() as $account_station){
			array_push($accounts, 
					['id'=>$account_station->account()->first()->uuid, 
					'name'=>$account_station->account()->first()->first_name.' '
					.$account_station->account()->first()->middle_name.' '
					.$account_station->account()->first()->last_name]);
		}
		return $accounts;
	}
	
	public function validateCorrectiveActionData(array $data){
		$rules = [
			$this->error_data->corrective_action_key => $this->error_data->corrective_action_req,
			$this->error_data->cause_key => $this->error_data->cause_req,
			$this->error_data->error_origin_key => $this->error_data->error_origin_req,
			$this->error_data->remarks_key => $this->error_data->remarks_req,
			//$this->error_data->date_time_created_key => $this->error_data->date_time_created_req,
			$this->error_data->originator_id_key => $this->error_data->originator_id_req,
			$this->error_data->originator_key => $this->error_data->originator_req,
		];
		
		/*$this->error_data->corrective_action_validation_msgs['date_time_created.before'] = Str::replaceArray('?', 
								[date_format(date_create($data[$this->error_data->date_time_created_key]), 'd/m/Y H:i:s'), 
								date('d/m/Y H:i:s', strtotime(strval(today()).' + 1days'))],
								$this->error_data->corrective_action_validation_msgs['date_time_created.before']);*/
								
		return Validator::make($data, $rules, $this->error_data->corrective_action_validation_msgs);
	}
	
	public function getAccount($uuid){
		try{
			$account = Account::withUuid($uuid)->first();
			if(is_null($account)){
				throw new Exception('Account has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $account;
	}
	
	public function sendOriginatorEmail($error){
		try{
			$email = Mail::to($error->errorCorrection()->first()->aioError()->first()->errorOriginator()->first()->email)
							->send(new ErrorOriginatorNotification($error));
			if($email){
				throw new Exception('Email to '.$error->errorCorrection()->first()->aioError()->first()->errorOriginator()->first()->name.' has not been sent successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}	
	}
	
	
	public function pdfError(object $error){
		
		$this->pdf->AddPage();
		$this->pdf->SetMargins(20, 10, 10);
		$this->Header();
		
		$this->pdf->SetFont('Arial','B',16);
		$this->getPdfReportedError($error);
		if($error->affectedProduct()->get())
			$this->getPdfErrorProducts(['Product', 'Identification'], $error);
		
		if($error->errorCorrection()->first())
			$this->getPdfErrorCorrection($error);
		
		//$this->Footer();
		$this->pdf->Output('I', 'name', true);
		
	}
	
	protected function getTextWidth(array $string, array $cellwidth, float $cellheight){
		$str_lenght = array(); $cell_width = array(); $rows = array();
		
		foreach($string as $str){
			/*$pix = imageftbbox($size, 0, dirname(__FILE__).'/../pdf/font/arial.ttf', $str);
			$width = $pix[2] - $pix[0];
			*/
			
			$width = $this->pdf->GetStringWidth($str);
			array_push($str_lenght, $width);
		}
		
		//return $str_lenght;
		/*
		foreach($cellwidth as $w){
			$cell_px = $w/0.264583333;
			array_push($cell_width, $cell_px);
		}*/
		for($i = 0; $i < count($str_lenght); $i++){
			$r = $str_lenght[$i]/$cellwidth[$i];
			array_push($rows, $r);
		}
		$this->total_rows = ceil((max($rows) + 1) * $cellheight);
		
		return $rows;
		
	}
	
	private function Header()
	{
		
		// Logo
		$this->pdf->Image(asset('public/images/logo/kcaa.png'),15,6,30);
		// Arial bold 15
		$this->pdf->SetFont('Arial','B',20);
		// Move to the right
		$this->pdf->Cell(80);
		// Title
		$this->pdf->Cell(30,10,'AIM Non-CONFORMITY REPORT',0,0,'C');
		// Line break
		$this->pdf->Ln(20);
	}
	
	private function Footer(){
		// Position at 1.5 cm from bottom
		$this->pdf->SetY(-15);
		// Arial italic 8
		$this->pdf->SetFont('Arial','I',8);
		// Page number
		$this->pdf->Cell(0,10,'Page '.$this->pdf->PageNo().'/{nb}',0,0,'C');
	}
	
	private function getPdfReportedError($error){
		$this->pdf->SetFont('Arial','B',17);
		$this->pdf->Cell(100, 10, 'Reported error', 0, 0, 'L');
		$this->pdf->Ln();
		
		$this->pdf->SetFont('Arial','',15);
		if(isset($error->number)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Number: ', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(80, 6, 
			$error->station()->first()->abbreviation.'/'
				.$error->func()->first()->abbreviation.'/'
				.$error->number.'/'
				.date_format(date_create($error->date_time_created), 'y'), 0);
			$this->pdf->Ln();
		}
		if(isset($error->function_id)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Function: ', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(80, 6, $error->func()->first()->name, 0);
			$this->pdf->Ln();
		}
		
		if(isset($error->description)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Description:', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(120, 6, $error->description, 0);
			$this->pdf->Ln();
		}
		
		if(isset($error->impact)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Impact:', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(120, 6, $error->impact, 0);
			$this->pdf->Ln();
		}
		
		if(isset($error->station_id)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Station of Origin:', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(120, 6, $error->station()->first()->name, 0);
			$this->pdf->Ln();
		}
		
		if(isset($error->date_time_created)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Date reported:', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(120, 6, date_format(date_create($error->date_time_created), 'd/m/Y H:i:s'), 0);
			$this->pdf->Ln();
		}
		
		if(isset($error->responsibility)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Responsibility:', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(120, 6, ($error->responsibility == 1)? $error->user()->first()->name : $error->station()->first()->name, 0);
			$this->pdf->Ln();
		}
		if(isset($error->user_id)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Reported by:', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(120, 6, $error->user()->first()->name, 0);
			$this->pdf->Ln();
		}
		if(isset($error->remarks)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Remarks:', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(120, 6, $error->remarks, 0);
			$this->pdf->Ln();
		}
		
	}
	
	private function getPdfErrorProducts(array $header, object $error)
	{
		
		$this->pdf->Ln();
		if(count($error->affectedProduct()->get())){

			$this->pdf->SetFont('Arial','B',17);
			$this->pdf->Cell(100, 10, 'Affected products', 0, 0, 'L');
			$this->pdf->Ln();
			
			// Column widths
			$w = array(60, 110);
			// Header
			$this->pdf->SetFont('Arial','',15);
			for($i=0;$i<count($header);$i++)
				$this->pdf->Cell($w[$i],7,$header[$i],1,0,'L');
			$this->pdf->Ln();
			
			// Data
			$start_x = $this->pdf->GetX();
			$current_y = $this->pdf->GetY();
			$current_x = $this->pdf->GetX();
			
			$this->pdf->SetFont('Arial','',13);
			foreach($error->affectedProduct()->get() as $row){
				$rows = $this->getTextWidth([$row->product()->first()->name, $row->product_identification], $w, 6);
				
				$this->pdf->MultiCell($w[0],$this->total_rows/((ceil($rows[0]) < 1)? 1 :ceil($rows[0])),$row->product()->first()->name,'LRB', 'L');
				$current_x += $w[0];
				$this->pdf->SetXY($current_x, $current_y);
				
				$this->pdf->MultiCell($w[1],$this->total_rows/((ceil($rows[1]) < 1)? 1 :ceil($rows[1])),$row->product_identification,'RB');
				
				$current_x = $start_x;
				$current_y += $this->total_rows;
			}
		}else{
			$this->pdf->SetFont('Arial','B',17);
			$this->pdf->Cell(60, 10, 'Affected products: ', 0, 0, 'L');
			$this->pdf->SetFont('Arial','',13);
			$this->pdf->Cell(100, 10, 'Nill', 0, 0, 'L');
			$this->pdf->Ln();
		}
		
	}
	
	private function getPdfErrorCorrection(object $error){
		$this->pdf->Ln();
		$this->pdf->SetFont('Arial','B',17);
		$this->pdf->Cell(100, 10, 'Error correction', 0, 0, 'L');
		$this->pdf->Ln();
		
		$this->pdf->SetFont('Arial','',15);
		
		if(isset($error->errorCorrection()->first()->cause)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Cause of error: ', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(80, 6, $error->errorCorrection()->first()->cause, 0);
			$this->pdf->Ln();
		}
		if(isset($error->errorCorrection()->first()->corrective_action)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Corrective action: ', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(80, 6, $error->errorCorrection()->first()->corrective_action, 0);
			$this->pdf->Ln();
		}
		if(isset($error->errorCorrection()->first()->station()->first()->name)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Station: ', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(80, 6, $error->errorCorrection()->first()->station()->first()->name, 0);
			$this->pdf->Ln();
		}
		if(isset($error->errorCorrection()->first()->remarks)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Remarks: ', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(80, 6, $error->errorCorrection()->first()->remarks, 0);
			$this->pdf->Ln();
		}
		if($error->aioError()->first() || $error->errorCorrection()->first()->externalError()->first()){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Error source: ', 0);
			$this->pdf->SetFontSize(13);
			if($error->aioError()->first())
				$this->pdf->MultiCell(80, 6, 
						$error->aioError()->first()->errorOriginator()->first()->account()->first()->first_name.' '
						.$error->aioError()->first()->errorOriginator()->first()->account()->first()->middle_name.' '
						.$error->aioError()->first()->errorOriginator()->first()->account()->first()->last_name,0);
			else if($error->errorCorrection()->first()->externalError()->first())
				$this->pdf->MultiCell(80, 6, $error->errorCorrection()->first()->externalError()->first()->description, 0);
			$this->pdf->Ln();
		}
		if(isset($error->date_time_created)){
			$this->pdf->SetFontSize(16);
			$this->pdf->Cell(50, 6, 'Date of response:', 0);
			$this->pdf->SetFontSize(13);
			$this->pdf->MultiCell(120, 6, date_format(date_create($error->errorCorrection()->first()->date_time_created), 'd/m/Y H:i:s'), 0);
			$this->pdf->Ln();
		}
	}
	
	public function errorPdfData($error){
		$products = array(); $correction = array(); $reported_error = array(); $originator_reaction = array();
		
		$reported_error = [
				'number' => $error->station()->first()->abbreviation.'/'
					.$error->func()->first()->abbreviation.'/'
					.$error->number.'/'
					.date_format(date_create($error->created_at), 'y'), 
				
				'function' => $error->func()->first()->name,
				'description' => $error->description,
				'impact' => $error->impact,
				'station' => $error->station()->first()->name,
				'date_reported' => date_format(date_create($error->created_at), 'd/m/Y H:i:s'),
				'responsibility' => ($error->responsibility)?$error->user()->first()->name:null,
				'user' => $error->user()->first()->name,
				'remarks' => $error->remarks,
		];
		
		foreach($error->affectedProduct()->get() as $product){
			array_push($products, ['product'=>$product->product()->first()->name, 'identification'=>$product->product_identification]);
		}
		
		if($error->errorCorrection()->first()){
			if($error->errorCorrection()->first()->aioError()->first()){
				$source = $error->errorCorrection()->first()->aioError()->first()->errorOriginator()->first()->name;
			}else if($error->errorCorrection()->first()->externalError()->first()){
				$source = $error->errorCorrection()->first()->externalError()->first()->description;
			}
			$correction = [
				'cause'=>$error->errorCorrection()->first()->cause,
				'corrective_action'=>$error->errorCorrection()->first()->corrective_action,
				'remarks'=>$error->errorCorrection()->first()->remarks,
				'source'=>$source,
				'date_responded' =>date_format(date_create($error->errorCorrection()->first()->created_at), 'd/m/Y H:i:s'),
				'corrector'=>$error->errorCorrection()->first()->user()->first()->name,
			];
			
			if($error->errorCorrection()->first()->originatorReaction()->first()){
				$originator_reaction = [
					'status'=>(boolval($error->errorCorrection()->first()->originatorReaction()->first()->status))
								? 'I agree with the corrective action'
								: 'I disagree with the corrective action',
					'remarks'=>$error->errorCorrection()->first()->originatorReaction()->first()->remarks,
				];
			}
		}
		
		
		
		return [
			'reported_error' => $reported_error,
			'products' => $products,
			'correction' => $correction,
			'originator_reaction' => $originator_reaction,
		];
	}
	
	public function errorsPdfData($errors){
		$data = array();
		foreach($errors as $error){
			
			array_push($data, $this->errorPdfData($error));
		}
		
		return ['errors' => $data];
	}
	
	public function getAccountStations(){
		try{
			$account_stations = Auth::user()->account()->first()->accountStation()->get();
			if(is_null($account_stations)){
				throw new Exception('Account stations cannot be retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $account_stations;
	}
	
	private function prepareNotificationErrorQuery($account_stations){
		$num = count($account_stations); $query = ''; $i = 0;
		
		if($num > 1){
			foreach($account_stations as $account_station){ 
				$i++;
				$query .= ($i == $num)? 'error_notifications.station_id='.$account_station->station()->first()->id
									:'error_notifications.station_id='.$account_station->station()->first()->id.' OR ';
			}
		}else{
			$query .= 'error_notifications.station_id='.$account_stations->first()->station()->first()->id;
		}
		return $query;
	}
	
	public function getPaginatedNotifiedErrors($account_stations){
		
		try{			
			$error_notification = ErrorNotification::whereRaw($this->prepareNotificationErrorQuery($account_stations))->orderBy('id', 'desc')->paginate($this->error_data->rows);
			if(is_null($error_notification)){
				throw new Exception('Notified errors have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $error_notification;
	}
	
	public function getNotifiedErrors($account_stations){
		try{			
			$error_notification = ErrorNotification::whereRaw($this->prepareNotificationErrorQuery($account_stations))->orderBy('id', 'desc')->get();
			if(is_null($error_notification)){
				throw new Exception('Notified errors have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $error_notification;
	}
	
	public function countErrorNotifications($error_notifications){
		
		$i = 0;
		foreach($error_notifications as $error_notification){
			if($error_notification->error()->first()->status()->first()->state()->first()->code == '1')
				$i++;
		}
		
		return $i;
	}
	
	public function searchErrorNotificationsPdf(array $data){
		//
	}
	
	public function validateErrorOriginatorReactionData(array $data){
		$rules = [
			$this->error_data->originator_reaction_key => $this->error_data->originator_reaction_req,
			$this->error_data->remarks_key => $this->error_data->remarks_req,
		];
		
		return Validator::make($data, $rules, $this->error_data->validate_error_originator_reaction_msgs);
	}
}
?>