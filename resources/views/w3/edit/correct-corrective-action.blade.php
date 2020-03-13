@extends('w3.layout.app')

@section('title')
<title>Correct corrective action</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Correct corrective action for error: <a class="w3-hover-text-blue" href="{{url('error/'.$error->uuid)}}" target="_blank" style="text-decoration:none;">{{$error->reportedStation()->first()->abbreviation}}/{{$error->func()->first()->abbreviation}}/{{$error->number}}/{{date_format(date_create($error->date_time_created), 'y')}}</a>
  </h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/update-error-corrective-action/'.$error->uuid)}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Corrective action<span class="w3-text-red">*</span></label>
							<textarea class="w3-input w3-border {{($errors->has('corrective_action')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
										placeholder="Give a corrective action done to the error"
										name="corrective_action"
										rows="3">{{(old('corrective_action'))?old('corrective_action'):$error->errorCorrection()->first()->corrective_action}}</textarea>
							@if($errors->has('corrective_action'))
								<span class="w3-small w3-text-red">{{$errors->first('corrective_action')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>	
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Cause<span class="w3-text-red">*</span></label>
							<textarea class="w3-input w3-border {{($errors->has('cause')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
										placeholder="What was the cause of the error?"
										name="cause"
										rows="4">{{(old('cause'))?old('cause'):$error->errorCorrection()->first()->cause}}</textarea>
							@if($errors->has('cause'))
								<span class="w3-small w3-text-red">{{$errors->first('cause')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
				</div>
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Error origin<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('error_origin')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="error_origin"
									onchange="getOriginator(this.value)">
								<option value="" disabled selected>Did the error originate from officers in this station?</option>
								@php $selected = null; @endphp
								
								<option value="0" @if(old('error_origin') == '0')@php $selected = 'selected'; @endphp {{$selected}}
										@elseif(is_null($selected) && !boolval($error->errorCorrection()->first()->source)) @php $selected = 'selected';@endphp {{$selected}}@endif>Not from the officers</option>
										
								<option value="1" @if(old('error_origin') == '1')@php $selected = 'selected'; @endphp {{$selected}}
										@elseif(is_null($selected) && boolval($error->errorCorrection()->first()->source)) @php $selected = 'selected';@endphp {{$selected}}@endif>Yes, from the officers</option>
							 </select>
							@if($errors->has('error_origin'))
								<span class="w3-small w3-text-red">{{$errors->first('error_origin')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small" 
						id="originator" 
						style="display:{{(strval(old('error_origin')) || $error->errorCorrection()->first())?'block':'none'}};">
						<div class="w3-col s12 m10 l10 w3-left">
							@if(old('error_origin') == '1' || (is_null(old('error_origin')) && $error->errorCorrection()->first()->aioError()->first()))
							<label class="w3-text-dark-gray">Error causing AIO<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('originator_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" name="originator_id">
								<option value="" disabled>Select the officer who caused the error</option>
								@if($error->reportedStation()->first()->accountStation()->get())
									@foreach($error->reportedStation()->first()->accountStation()->get() as $account_station)
										@php $selected = null; @endphp
										<option value="{{$account_station->account()->first()->uuid}}" 
											@if(old('originator_id') == $account_station->account()->first()->uuid)
												@php $selected = 'selected' ;@endphp
												{{$selected}}
											@elseif(is_null($selected) && $error->errorCorrection()->first()->aioError()->first()
															&& $error->errorCorrection()->first()->aioError()->first()
																->errorOriginator()->first()->account()->first()->uuid == $account_station->account()->first()->uuid)
												@php $selected = 'selected' ;@endphp
												{{$selected}}
											@endif>{{$account_station->account()->first()->user()->first()->name}}</option>
											
									@endforeach
								@endif<!-- AJAX call based on station responsible-->
							 </select>
							@elseif(old('error_origin') == '0' || (is_null(old('error_origin')) && $error->errorCorrection()->first()->externalError()->first()))
							@php $description = ($error->errorCorrection()->first()->externalError()->first())?
									$error->errorCorrection()->first()->externalError()->first()->description:null; @endphp
									
							<label class="w3-text-dark-gray">Originator<span class="w3-text-red">*</span></label>
							<textarea class="w3-input w3-border {{($errors->has('originator')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
										placeholder="Describe the originator of the error"
										name="originator"
										rows="2">{{(old('originator'))?old('originator'):$description}}</textarea>
							@endif
							
							@if($errors->has('originator_id'))
								<span class="w3-small w3-text-red">{{$errors->first('originator_id')}}</span>
							@elseif($errors->has('originator'))
								<span class="w3-small w3-text-red">{{$errors->first('originator')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>	
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Remarks</label>
							<textarea class="w3-input w3-border-dark-gray w3-border" 
									placeholder="Give your remarks" 
									name="remarks" 
									rows="2">{{(old('remarks'))?old('remarks'):$error->errorCorrection()->first()->remarks}}</textarea>
							@if($errors->has('remarks'))
								<span class="w3-small w3-text-red">{{$errors->first('remarks')}}</span>
							@elseif($errors->has('remarks'))
								<span class="w3-small w3-text-red">{{$errors->first('remarks')}}</span>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="w3-row">
				<div class="w3-col w3-padding-small">
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Update error correction">Update&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="row" style="max-width:75%;">

</div>
@endsection

@section('scripts')
<script>

document.getElementById('errors').className += " w3-text-blue";
document.getElementById('menu-error').className += " w3-text-blue";
menuAcc('error');
w3_show_nav('menuQMS');

function getOriginator(originator){
	let originator_div = document.getElementById("originator");
	
	if(originator == '0'){
		let input = '<label class="w3-text-dark-gray">Originator<span class="w3-text-red">*</span></label>';
		input += '<textarea class="w3-input w3-border w3-border-dark-gray" placeholder="Describe the originator of the error"';
		input += 'name="originator" rows="2"></textarea><span>&nbsp;</span>';
		
		originator_div.children[0].innerHTML = input;
		originator_div.style.display = 'block';
		console.log(originator_div.children[0]);
		
	}else if(originator == '1'){
		let input = '<label class="w3-text-dark-gray">Error causing AIO<span class="w3-text-red">*</span></label>';
		input += '<select class="w3-select w3-border w3-border-dark-gray" name="originator_id">';
		
		let xhr = new XMLHttpRequest();
		xhr.open("GET", "{{url('get-account-station').'/'.$error->reportedStation()->first()->uuid}}");
		xhr.send();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				let accounts = JSON.parse(xhr.responseText);
				let option = '<option value="" selected>Select the officer who caused the error</option>';
				for(i in accounts){
					option += '<option value="'+ accounts[i]['id']+'">'+accounts[i]['name']+'</option>';
				}
				input += option + '</select><span>&nbsp;</span>';
				
				originator_div.children[0].innerHTML = input;
				originator_div.style.display = 'block';
				console.log(originator_div.children[0]);
			}
		}
	}
	
	
}

</script>


@endsection
