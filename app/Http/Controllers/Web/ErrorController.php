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
			if(is_object($errors)){
				if(View::exists('w3.index.errors'))
					if(count($errors))
						return view('w3.index.errors')->with(compact('errors'));
					else
						return view('w3.index.errors')->with(compact('errors'))
								->with('notification', array('indicator'=>'warning', 'message'=>'Errors(s) not found'));
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
	
	public function createError(){
		if(Auth::user()->can('create_errors')){
			$functions = $this->ext->getFunctions();
			if(!is_object($functions))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$functions));
			
			$stations = $this->ext->getStations();
			if(!is_object($stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			
			$status = $this->ext->getErrorStatus();
			if(!is_object($status))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$status));
			
			if(View::exists('w3.create.error')){
				return view('w3.create.error')->with(compact('functions', 'stations', 'status'))
							->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank. Use Chrome browser v20.0'));
			}else{
				return back()->with('notification', $this->missing_view);
			}
			
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
						->withInput();
			}
			
			$function = $this->ext->getFunction($request['function_id']);
			if(!is_object($function))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$function)); 
			
			$station = $this->ext->getStation($request['station_id']);
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations)); 
			
			$recipients = $this->ext->getNotificationRecipients($station);
			if(!is_object($recipients))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$recipients));
			
			$number = $this->ext->getErrorNumber($station->id, $function->id); 
			if(is_null($number))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$number));
			else
				$request['number'] = $number;
				
			$notification = $this->mnt->createError($request->all(), $function, $station, $recipients); 
			if(in_array('success', $notification)){
				if($request['responsibility'] == 0){
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
			
		}else{
			
		}
	}
	public function updateError(Request $request, $uuid){
		if(Auth::user()->can('edit_errors')){
			
		}else{
			
		}
	}
	
	public function deleteError($uuid){
		if(Auth::user()->can('delete_errors')){
			
		}else{
			
		}
	}
	public function destroyError($uuid){
		if(Auth::user()->can('delete_errors')){
			
		}else{
			
		}
	}
	
	public function addErrorProduct($uuid){
		if(Auth::user()->can('create_errors')){
			$func_error = $this->ext->getError($uuid);
			if(!is_object($func_error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$func_error));
			
			/*foreach($func_error->affectedProduct()->get() as $affected_product){
				var_dump($affected_product->product()->first()->uuid);
			}return;*/
			
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
		//return var_dump($request->all());
		if(Auth::user()->can('create_errors')){
			$validation = $this->ext->validateErrorProductData($request->all());
			if($validation->fails()){
				return redirect('add-error-affected-product/'.$uuid)
							->withErrors($validation)
							->withInput()
							->with('notification', array('indicator'=>'warning', 'message'=>'Do appropriate correction on the data before you submit'));
			}
			
			
			$error = $this->ext->getError($uuid); //return var_dump($error->description);
			if(!is_object($error)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error)); 
			
			$product = $this->ext->getProduct($request['product_id']); //return var_dump($product->name);
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
	
	public function addCorrectiveAction(Request $request, $uuid){
		if(Auth::user()->can('create_errors')){
			$error = $this->ext->getError($uuid);
			if(!is_object($error)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error)); 
			
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
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add affected products for the errors'));
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
			
			if(isset($request['originator_id'])){
				$account = $this->ext->getAccount($request['originator_id']);
				if(!is_object($error)) 
					return back()->with('notification', array('indicator'=>'warning', 'message'=>$account))->withInput();
				$request['aio'] = $account->user()->first()->id;
			}
			
			$notification = $this->mnt->createCorrectiveAction($request->all(), $error); 
			if(in_array('success', $notification)){
				//Send email to the aio who caused the error
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
	
	public function pdfError($uuid){
		
		if(Auth::user()->can('view_errors')){
			$error = $this->ext->getError($uuid); //return var_dump($error);
			if(!is_object($error)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$error))->withInput();
			//return var_dump($this->ext->pdfError($error));
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
				$errors = $this->ext->searchErrorsPdf(json_decode($request->all()));
			}else{				
				$errors = $this->ext->getErrors();
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
	
}
