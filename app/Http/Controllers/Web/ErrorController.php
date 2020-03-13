<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use PDF;

use App\Shell\Web\Extension\ErrorExt;
use App\Shell\Web\Monitor\ErrorMnt;

class ErrorController extends Controller
{
    private $ext;
    private $mnt;
	
	public function __construct(){
		$this->ext = new ErrorExt();
		$this->mnt = new ErrorMnt();
	}
	
	public function errors(Request $request){
		if(Auth::user()->can('view_errors')){
			if(count($request->all())){
				$errors = $this->ext->searchErrors($request->all());
			}else{				
				$errors = $this->ext->getPaginatedErrors();
			}

			//return $errors;
			$stations = $this->ext->getStations();
			if(!is_object($stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));

			$functions = $this->ext->getFunctions();
			if(!is_object($functions))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$functions));

			$accounts = $this->ext->getAccounts();
			if(!is_object($accounts))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$accounts));


			if(is_object($errors)){
				if(View::exists('w3.index.errors'))
					if(count($errors))
						return view('w3.index.errors')->with(compact('errors', 'stations', 'functions', 'accounts'));
					else
						return view('w3.index.errors')->with(compact('errors', 'stations', 'functions', 'accounts'))
								->with('notification', array('indicator'=>'warning', 'message'=>'Error(s) not found'));
				else{
					return back()->with('notification', $this->ext->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$errors));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view errors'));
		}
	}

	public function validateErrorsSearchForm($data){
		$data = json_decode($data, true);

		$validation = $this->ext->validateSerachErrorData($data);

		return response()->json($validation);
	}
	
	public function createError(){
		if(Auth::user()->can('create_errors')){
			if(is_null(Auth::user()->account()->first()))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'You do not have a proper account'));
			
			$functions = $this->ext->getFunctions();
			if(!is_object($functions))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$functions));
			
			$stations = $this->ext->getStations();
			if(!is_object($stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			
			$account_stations = $this->ext->getAccountStations(); 
			if(!is_object($account_stations)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account_stations));
			
			if(!count($account_stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'You have not been assigned a station'));
			
			if(View::exists('w3.create.error')){
				return view('w3.create.error')->with(compact('functions', 'stations', 'account_stations'))
							->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank.'));
			}else{
				return back()->with('notification', $this->missing_view);
			}
			
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create functional errors'));
		}
	}
	
	public function getStationFunctions($uuid){
		if(Auth::user()->can('create_errors')){
			$station = $this->ext->getStation($uuid);
			if(!is_object($station))
				return null;
			return response()->json($this->ext->getStationFunctions($station));
			
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create functional errors'));
		}
	}
	
	public function storeError(Request $request){
		if(Auth::user()->can('create_errors')){
			$validation = $this->ext->validateErrorData($request->all());
			if($validation->fails()){
				return redirect('create-error')
						->withErrors($validation)
						->withInput()
						->with('notification', $this->ext->validation);
			}
			
			$function = $this->ext->getFunction($request['function_id']);
			if(!is_object($function))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$function)); 
			
			$reported_station = $this->ext->getStation($request['reported_station_id']);
			if(!is_object($reported_station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$reported_station)); 
			
			$reporting_station = $this->ext->getStation($request['reporting_station_id']);
			if(!is_object($reporting_station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$reporting_station)); 
			
			$recipients = $this->ext->getNotificationRecipients($reported_station);
			if(!is_object($recipients))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$recipients));
			
			$number = $this->ext->getErrorNumber($reported_station->id, $function->id); 
			if(is_null($number))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$number));
			else
				$request['number'] = $number;
				
			$notification = $this->mnt->createError($request->all(), $function, $reported_station, $reporting_station, $recipients); 
			if(in_array('success', $notification)){
				if(count($recipients)){
					$error_email = $this->ext->sendErrorNotificationEmail($notification['uuid'], $recipients); 
					if(is_string($error_email)) $notification['message'] .= '. '.$error_email;
				}			
					
				if(View::exists('w3.show.error'))
					return redirect('error/'.$notification['uuid'])->with(compact('notification'));
				else
					return back()->with('notification', $this->ext->missing_view)->withInput();
			}else{
				return back()->with(compact('notification'))->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create functional errors'));
		}
	}
	
	public function showError($error_uuid){ 
		if(Auth::user()->can('view_errors')){//4471f490-0ab2-11ea-aa54-1746b95626cb 8+1+4+1+4+1+4+1+12=36
		
			$uuid = (strlen($error_uuid) > 36)? decrypt($error_uuid): $error_uuid;
			
			$error = $this->ext->getError($uuid);
			if(!is_object($error)){
				if(strlen($uuid) > 36)
					return redirect('/')->with('notification', array('indicator'=>'warning', 'message'=>$error));
				else return back()->with('notification', array('indicator'=>'warning', 'message'=>$error));
			}
			
			//return $error->errorCorrection()->first()->aioError()->first()->id;
			//return var_dump($error->errorCorrection()->first()->status()->first()->state()->first()->code);
		//return $error->errorCorrection()->first()->originatorReaction()->first()->sts;
			
			if(View::exists('w3.show.error')){
				return view('w3.show.error')->with(compact('error'));
			}else{
				if(strlen($error_uuid) > 36)
					return redirect('/')->with('notification', $this->ext->missing_view);
				else return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			if(strlen($error_uuid) > 36)
				return redirect('/')->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view errors'));
			else 
				return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view errors'));
		}
	}
	
	public function editError($uuid){
		if(Auth::user()->can('edit_errors')){

			if(is_null(Auth::user()->account()->first()))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'You do not have a proper account'));

			$error = $this->ext->getError($uuid);
			if(!is_object($error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error));
			
			$functions = $this->ext->getFunctions();
			if(!is_object($functions))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$functions));
			
			$stations = $this->ext->getStations();
			if(!is_object($stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			
			$account_stations = $this->ext->getAccountStations(); 
			if(!is_object($account_stations)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account_stations));
			
			if(!count($account_stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'You have not been assigned a station'));
			
			if(View::exists('w3.edit.error')){
				return view('w3.edit.error')->with(compact('error', 'functions', 'stations', 'account_stations'))
							->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank.'));
			}else{
				return back()->with('notification', $this->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit functional errors'));
		}
	}
	public function updateError(Request $request, $uuid){
		if(Auth::user()->can('edit_errors')){
			$request['error_id'] = $uuid;
			$validation = $this->ext->validateErrorData($request->all());
			if($validation->fails()){
				return back()->withErrors($validation)
						->withInput()
						->with('notification', $this->ext->validation);
			}

			$error = $this->ext->getError($uuid);
			if(!is_object($error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error));
			
			$reporting_station = $this->ext->getStation($request['reporting_station_id']);
			if(!is_object($reporting_station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$reporting_station)); 

			$notification = $this->mnt->editError($request->all(), $error, $reporting_station); 
			if(in_array('success', $notification)){
				if(View::exists('w3.show.error'))
					return redirect('error/'.$error->uuid)->with(compact('notification'));
				else
					return back()->with('notification', $this->ext->missing_view)->withInput();
			}else{
				return back()->with(compact('notification'))->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit functional errors'));
		}
	}
	
	public function deleteError($uuid){
		if(session()->has('params')) session()->reflash();

		if(Auth::user()->can('delete_errors')){
			$error = $this->ext->getError($uuid);
			if(is_object($error)){
				return $this->ext->deleteError($error);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			
		}
	}
	public function destroyError($uuid){
		if(Auth::user()->can('delete_errors')){
			$error = $this->ext->getError($uuid);
			if(is_object($error)){
				$notification = $this->mnt->deleteError($error);
				if(in_array('success', $notification)){
					if(View::exists('w3.index.errors')){
						return redirect('errors')
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $error));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete an error'));
		}
	}
	
	public function addErrorProduct($uuid){
		if(Auth::user()->can('create_errors')){
			$func_error = $this->ext->getError($uuid);
			if(!is_object($func_error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$func_error));
			
			if(View::exists('w3.create.affected-product')){
				return view('w3.create.affected-product')->with(compact('func_error'))
						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank.'));
			}else{
				return back()->with('notification', $this->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add affected products for the errors'));
		}
	}
	public function storeErrorProduct(Request $request, $uuid){
		if(Auth::user()->can('create_errors')){
			$validation = $this->ext->validateErrorProductData($request->all());
			if($validation->fails()){
				return redirect('add-error-affected-product/'.$uuid)
							->withErrors($validation)
							->withInput()
							->with('notification', array('indicator'=>'warning', 'message'=>'Do appropriate correction on the data before you submit'));
			}
			
			
			$error = $this->ext->getError($uuid); 
			if(!is_object($error)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error)); 
			
			$product = $this->ext->getProduct($request['product_id']); 
			if(!is_object($product)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$product));
			
			$notification = $this->mnt->createErrorProduct($request->all(), $error, $product);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.error'))
					return redirect('error/'.$uuid)->with(compact('notification'));
				else 
					return back()->with('notification', $this->ext->missing_view)->withInput();
			}else{
				return redirect('add-error-affected-product/'.$uuid)->with(compact('notification'))->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add affected products for the errors'));
		}
	}

	public function getAffectedProduct($uuid){

		if(session()->has('params')) session()->reflash();

		if(Auth::user()->can('view_errors')){
			$product = $this->ext->getAffectedProduct($uuid);
			if(is_object($product))
				return response()->json($this->ext->prepAffectedProduct($product));
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view affected product'));
		}
	}

	public function deleteAffectedProduct($uuid){
		if(session()->has('params')) session()->reflash();

		if(Auth::user()->can('delete_errors')){
			$product = $this->ext->getAffectedProduct($uuid);
			if(is_object($product)){
				return $this->ext->deleteAffectedProduct($product);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			
		}
	}

	public function destroyAffectedProduct($uuid){
		if(Auth::user()->can('delete_errors')){
			$product = $this->ext->getAffectedProduct($uuid);
			if(is_object($product)){
				$notification = $this->mnt->deleteAffectedProduct($product);
				if(in_array('success', $notification)){
					if(View::exists('w3.show.error')){
						return redirect('error/'.$product->error()->first()->uuid)
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $product));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete an error'));
		}
	}
	
	public function addCorrectiveAction($uuid){
		if(Auth::user()->can('create_errors')){
			$error = $this->ext->getError($uuid);
			if(!is_object($error)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error)); 
			
			if($error->errorCorrection()->first())
				return redirect('error-corrective-action/'.$uuid)
						->with('notification', array('indicator'=>'warning', 'message'=>'Error has been provided with a corrective action already'));
			
			$stations = $this->ext->getStations();
			if(!is_object($stations)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			
			if(View::exists('w3.create.corrective-action')){
				return view('w3.create.corrective-action')->with(compact('error', 'stations'))
						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank.'));
			}else{
				return back()->with('notification', $this->ext->missing_view)->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add corrective for the errors'));
		}
	}
	
	public function getAccountStation($uuid){
		if(Auth::user()->can('create_errors')){
			
			$station = $this->ext->getStation($uuid);
			if(!is_object($station))
				return response()->json(array(['id'=>null, 'name'=>'Error occurred. Sorry']));
			
			return response()->json($this->ext->getJsonStationAccounts($station));
			
			
		}else{
			return response()->json(array(['id'=>null, 'name'=>'You are not allowed to give corrective action to errors']));
		}
	}
	
	public function storeCorrectiveAction(Request $request, $uuid){
		if(Auth::user()->can('create_errors')){
			
			$validation = $this->ext->validateCorrectiveActionData($request->all());
			if($validation->fails()){
				return redirect('error-corrective-action/'.$uuid)
						->withErrors($validation)
						->withInput()
						->with('notification', array('indicator'=>'warning', 'message'=>'Correct the input fields appropriately'));
			}
			
			$error = $this->ext->getError($uuid);
			if(!is_object($error)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error))->withInput();
			
			if($error->errorCorrection()->first())
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'Error has been provided with a corrective action already'))->withInput();
			
			if(isset($request['originator_id'])){
				$account = $this->ext->getAccount($request['originator_id']);
				if(!is_object($account)) 
					return back()->with('notification', array('indicator'=>'warning', 'message'=>$account))->withInput();
				$request['aio'] = $account->user()->first()->id;
			}
			
			$notification = $this->mnt->createCorrectiveAction($request->all(), $error);
			if(in_array('success', $notification)){
				//Send email to the aio who caused the error
				if(in_array('aio', $request->all())){
					$originator_email = $this->ext->sendOriginatorEmail($error);
					if(is_string($originator_email))
						$notification['message'] .= '. ' . $originator_email;
				}

				//Send email to supervisor of the station
				if($error->reportedStation()->first()->supervisor()->first()){
					$supervisor_email = $this->ext->sendSupervisorErrorCorrectionEmail($error);
					if(is_string($supervisor_email))
						$notification['message'] .= '. ' . $supervisor_email;
				}
				
				if(View::exists('w3.show.error'))
					return redirect('error/'.$uuid)->with(compact('notification'));
				else 
					return back()->with('notification', $this->ext->missing_view)->withInput();
			}else{
				return redirect('error-corrective-action/'.$uuid)->with(compact('notification'))->withInput();
			}
			
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to provide corrective action for the errors'));
		}
	}

	public function editCorrectiveAction($uuid){
		if(Auth::user()->can('edit_errors')){
			$error = $this->ext->getError($uuid);
			if(!is_object($error)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error)); 
			
			$stations = $this->ext->getStations();
			if(!is_object($stations)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			
			if(View::exists('w3.edit.corrective-action')){
				return view('w3.edit.corrective-action')->with(compact('error', 'stations'))
						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank.'));
			}else{
				return back()->with('notification', $this->ext->missing_view)->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit corrective for the errors'));
		}
	}
	
	public function updateCorrectiveAction(Request $request, $uuid){
		if(Auth::user()->can('edit_errors')){
			
			$validation = $this->ext->validateCorrectiveActionData($request->all());
			if($validation->fails()){
				return back()->withErrors($validation)
						->withInput()
						->with('notification', array('indicator'=>'warning', 'message'=>'Correct the input fields appropriately'));
			}
			
			$error = $this->ext->getError($uuid);
			if(!is_object($error)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error))->withInput();
			
			if(isset($request['originator_id'])){
				$account = $this->ext->getAccount($request['originator_id']);
				if(!is_object($account)) 
					return back()->with('notification', array('indicator'=>'warning', 'message'=>$account))->withInput();
				$request['aio'] = $account->user()->first()->id;
			}

			$request['code'] = intval($error->errorCorrection()->first()->status()->first()->state()->first()->code);
			
			
			$notification = $this->mnt->editCorrectiveAction($request->all(), $error);
			if(in_array('success', $notification)){
				//Send email to the aio who caused the error
				//Check if the aio is different from the earlier one
				if(in_array('aio', $request->all())){
					$originator_email = $this->ext->sendOriginatorEmail($error);
					if(is_string($originator_email))
						$notification['message'] .= '. ' . $originator_email;
				}
				
				//Send email to supervisor of the station
				if($error->reportedStation()->first()->supervisor()->first()){
					$supervisor_email = $this->ext->sendSupervisorErrorCorrectionEmail($error);
					if(is_string($supervisor_email))
						$notification['message'] .= '. ' . $supervisor_email.' Sup';
				}
				
				if(View::exists('w3.show.error'))
					return redirect('error/'.$uuid)->with(compact('notification'));
				else 
					return back()->with('notification', $this->ext->missing_view)->withInput();
			}else{
				return redirect('error-corrective-action/'.$uuid)->with(compact('notification'))->withInput();
			}
			
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to provide corrective action for the errors'));
		}
	}

	public function deleteCorrectiveAction($uuid){
		if(session()->has('params')) session()->reflash();

		if(Auth::user()->can('delete_errors')){
			$error = $this->ext->getError($uuid);
			if(is_object($error)){
				return $this->ext->deleteCorrectiveAction($error);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			
		}
	}

	public function destroyCorrectiveAction($uuid){
		if(Auth::user()->can('delete_errors')){
			$error = $this->ext->getError($uuid);
			if(is_object($error)){
				$notification = $this->mnt->deleteCorrectiveAction($error);
				if(in_array('success', $notification)){
					if(View::exists('w3.show.error')){
						return redirect('error/'.$uuid)
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $error));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete an error correction'));
		}
	}
	
	public function pdfError($uuid){
		
		if(Auth::user()->can('view_errors')){
			$error = $this->ext->getError($uuid); //return var_dump($error);
			if(!is_object($error)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error))->withInput();
			
			return response($this->ext->pdfError($error))
					->header('Content-Type', 'application/pdf');
		}else{
			
		}
	}
	
	public function errorPdf($uuid){
		if(Auth::user()->can('view_errors')){
			$error = $this->ext->getError($uuid); //return var_dump($error);
			if(!is_object($error)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error))->withInput();
			
			$data = $this->ext->errorPdfData($error); //return var_dump($data);
			
			$pdf = PDF::loadView('w3.pdf.error', $data);
			return $pdf->stream();
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view errors'));
		}
	}
	
	public function errorsPdf(Request $request){
		if(Auth::user()->can('view_errors')){
			if(count($request->all())){
				$errors = $this->ext->getErrorsWithUuids(json_decode($request['errors']));
			}else{ 
				abort(403);
				//$errors = $this->ext->getPaginatedErrors();
			}
			
			if(is_object($errors)){
				$data = $this->ext->errorsPdfData($errors);
				
				$pdf = PDF::loadView('w3.pdf.errors', $data)->setPaper('a4', 'landscape');
				return $pdf->stream();
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$errors));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view errors'));
		}
	}
	
	public function errorNotifications(){
		if(Auth::user()->can('view_errors')){
			
			if(is_null(Auth::user()->account()->first()) || is_null(Auth::user()->account()->first()->accountStation()->first()))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'You have not been allocated a station'));
			
			$account_stations = $this->ext->getAccountStations(); 
			if(!is_object($account_stations)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account_stations));
			
			if(count($account_stations)){
				$error_notifications = $this->ext->getPaginatedNotifiedErrors($account_stations); 
				if(!is_object($error_notifications)){
					return back()->with('notification', array('indicator'=>'warning', 'message'=>$error_notifications));
				}
				
				if(View::exists('w3.index.error_notifications')){
					if(count($error_notifications))
						return view('w3.index.error_notifications')->with(compact('error_notifications'));
					else
						return view('w3.index.error_notifications')->with(compact('error_notifications'))
							->with('notification', array('indicator'=>'warning', 'message'=>'Notified error(s) could not be found'));	
				}
				
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'You have not been assigned to any station in the system'));
			}
			
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view errors'));
		}
	}
	
	public function countErrorNotifications(){
		if(is_null(Auth::user()->account()->first()) || is_null(Auth::user()->account()->first()->accountStation()->first()))
			return '<span class="w3-text-red">!stn</span>';
		
		$account_stations = $this->ext->getAccountStations();
		if(!is_object($account_stations)) 
				return '<span class="w3-text-red">!acc stn</span>';
		
		if(count($account_stations)){
			$error_notifications = $this->ext->getNotifiedErrors($account_stations); //return var_dump($error_notifications);
			if(!is_object($error_notifications)){
				return '<span class="w3-text-red">Error!</span>';
			}
			return count($error_notifications);
			//return $this->ext->countErrorNotifications($error_notifications);
		}
	}
	
	public function createErrorOriginatorReaction($uuid){
		if(Auth::user()->can('create_errors')){
			$func_error = $this->ext->getError($uuid);
			if(!is_object($func_error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$func_error));
			
			if(is_null($func_error->errorCorrection()->first()))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'Error has not been given correction action'));
			
			if($func_error->errorCorrection()->first()->originatorReaction()->first())
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'Originator reaction has been given'));
			
			if($func_error->errorCorrection()->first()->aioError()->first() 
				&& $func_error->errorCorrection()->first()->aioError()->first()->errorOriginator()->first()->id != Auth::id())
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not the originator of this error'));
			
			if(View::exists('w3.create.originator-reaction')){
				return view('w3.create.originator-reaction')
						->with(compact('func_error'))
						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank.'));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to provide originator reaction to the error'));
		}
	}
	
	public function storeErrorOriginatorReaction(Request $request, $uuid){
		if(Auth::user()->can('create_errors')){
			$validation = $this->ext->validateErrorOriginatorReactionData($request->all());
			if($validation->fails()){
				return back()->withErrors($validation)
						->withInput()->with('notification', $this->ext->validation);
			}
			
			$error = $this->ext->getError($uuid);
			if(!is_object($error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error))->withInput();
			
			if(is_null($error->errorCorrection()->first()))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'Error has not been given correction action'))->withInput();
			
			$notification = $this->mnt->createErrorOriginatorReaction($request->all(), $error->errorCorrection()->first());
			if(in_array('success', $notification)){
				//Send supervisor email
				$supervisor_email = $this->ext->sendSupervisorEmail($error);
				if(is_string($supervisor_email))
					$notification['message'] .= '. ' . $supervisor_email;
				
				if(view::exists('w3.show.error'))
					return redirect('error/'.$uuid)->with(compact('notification'));
				else
					return back()->with('notification', $this->ext->missing_view)->withInput();
			}else{
				return back()->with(compact('notification'))->withInput();
			}
			
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to provide originator reaction to the error'));
		}
	}

	public function editErrorOriginatorReaction($uuid){
		if(Auth::user()->can('edit_errors')){
			$func_error = $this->ext->getError($uuid);
			if(!is_object($func_error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$func_error));
			
			if(View::exists('w3.edit.originator-reaction')){
				return view('w3.edit.originator-reaction')
						->with(compact('func_error'))
						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank.'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to provide supervisor reaction to the error'));
		}
	}

	public function updateErrorOriginatorReaction(Request $request, $uuid){

		if(Auth::user()->can('edit_errors')){
			$validation = $this->ext->validateErrorOriginatorReactionData($request->all());
			if($validation->fails()){
				return back()->withErrors($validation)
						->withInput()
						->with('notification', $this->ext->validation);
			}
			
			$error = $this->ext->getError($uuid);
			if(!is_object($error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error));

			$notification = $this->mnt->editErrorOriginatorReaction($request->all(), $error);
			if(in_array('success', $notification)){
				$supervisor_email = $this->ext->sendSupervisorEmail($error);
				if(is_string($supervisor_email))
					$notification['message'] .= '. ' . $supervisor_email;

				if(view::exists('w3.show.error'))
					return redirect('error/'.$uuid)->with(compact('notification'));
				else
					return back()->with('notification', $this->ext->missing_view)->withInput();
			}else{
				return back()->with(compact('notification'))->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to provide supervisor reaction to the error'));
		}
	}

	public function deleteErrorOriginatorReaction($uuid){
		if(session()->has('params')) session()->reflash();

		if(Auth::user()->can('delete_errors')){
			$error = $this->ext->getError($uuid);
			if(is_object($error)){
				return $this->ext->deleteErrorOriginatorReaction($error);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			
		}
	}

	public function destroyErrorOriginatorReaction($uuid){
		if(Auth::user()->can('delete_errors')){
			$error = $this->ext->getError($uuid);
			if(is_object($error)){
				$notification = $this->mnt->deleteErrorOriginatorReaction($error);
				if(in_array('success', $notification)){
					if(View::exists('w3.show.error')){
						return redirect('error/'.$uuid)
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $error));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete an error originator reaction'));
		}
	}
	
	public function createErrorSupervisorReaction($uuid){
		if(Auth::user()->can('create_errors')){
			$func_error = $this->ext->getError($uuid);
			if(!is_object($func_error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$func_error));
			
			if(is_null($func_error->errorCorrection()->first()))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'Error has not been given correction action'));
			
			if($func_error->errorCorrection()->first()->supervisorReaction()->first() 
				&& $func_error->errorCorrection()->first()->status()->first()->state()->first()->code == 4)
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'Supervisor reaction has been given'));
			
			if($func_error->errorCorrection()->first()->station()->first()->id != Auth::user()->account()->first()->supervisor()->first()->station()->first()->id)
				return back()
				->with('notification', array('indicator'=>'warning', 'message'=>'You are not a supervisor at '.$func_error->errorCorrection()->first()->station()->first()->name));
			
			if(View::exists('w3.create.supervisor-reaction')){
				return view('w3.create.supervisor-reaction')
						->with(compact('func_error'))
						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank.'));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to provide supervisor reaction to the error'));
		}
	}
	
	public function storeErrorSupervisorReaction(Request $request, $uuid){
		if(Auth::user()->can('edit_errors')){
			$validation = $this->ext->validateSupervisorReactionData($request->all());
			if($validation->fails()){
				return back()->withErrors($validation)
						->withInput()
						->with('notification', $this->ext->validation);
			}
			
			$error = $this->ext->getError($uuid);
			if(!is_object($error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error));
			
			
			$notification = $this->mnt->createErrorSupervisorReaction($request->all(), $error);
			if(in_array('success', $notification)){
				if($error->errorCorrection()->first()->status()->first()->state()->first()->code == 2){
					$sup_reaction = $this->ext->sendSupReactionEmail($error);
					if(is_string($sup_reaction))
						$notification['message'] .= '. ' . $sup_reaction;
				}
				if(view::exists('w3.show.error'))
					return redirect('error/'.$uuid)->with(compact('notification'));
				else
					return back()->with('notification', $this->ext->missing_view)->withInput();
			}else{
				return back()->with(compact('notification'))->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to provide supervisor reaction to the error'));
		}
	}
	
	public function editErrorSupervisorReaction($uuid){ 
		if(Auth::user()->can('edit_errors')){
			$func_error = $this->ext->getError($uuid);
			if(!is_object($func_error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$func_error));
			
			if(View::exists('w3.edit.supervisor-reaction')){
				return view('w3.edit.supervisor-reaction')
						->with(compact('func_error'));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to provide supervisor reaction to the error'));
		}
	}
	
	public function updateErrorSupervisorReaction(Request $request, $uuid){
		if(Auth::user()->can('edit_errors')){
			$validation = $this->ext->validateSupervisorReactionData($request->all());
			if($validation->fails()){
				return back()->withErrors($validation)
						->withInput()
						->with('notification', $this->ext->validation);
			}
			
			$error = $this->ext->getError($uuid);
			if(!is_object($error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error));
			
			
			$notification = $this->mnt->editErrorSupervisorReaction($request->all(), $error);
			if(in_array('success', $notification)){
				if($error->errorCorrection()->first()->status()->first()->state()->first()->code == 2){
					$sup_reaction = $this->ext->sendSupReactionEmail($error);
					if(is_string($sup_reaction))
						$notification['message'] .= '. ' . $sup_reaction;
				}
				if(view::exists('w3.show.error'))
					return redirect('error/'.$uuid)->with(compact('notification'));
				else
					return back()->with('notification', $this->ext->missing_view)->withInput();
			}else{
				return back()->with(compact('notification'))->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to provide supervisor reaction to the error'));
		}
	}

	public function deleteErrorSupervisorReaction($uuid){
		if(session()->has('params')) session()->reflash();

		if(Auth::user()->can('delete_errors')){
			$error = $this->ext->getError($uuid);
			if(is_object($error)){
				return $this->ext->deleteErrorSupervisorReaction($error);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			
		}
	}

	public function destroyErrorSupervisorReaction($uuid){
		if(Auth::user()->can('delete_errors')){
			$error = $this->ext->getError($uuid);
			if(is_object($error)){
				$notification = $this->mnt->deleteErrorSupervisorReaction($error);
				if(in_array('success', $notification)){
					if(View::exists('w3.show.error')){
						return redirect('error/'.$uuid)
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $error));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete an error originator reaction'));
		}
	}
	
}
