@extends('w3.layout.app')

@section('title')
<title>Error corrective action</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Corrective action for error: {{$error->station()->first()->abbreviation}}/{{$error->func()->first()->abbreviation}}/{{$error->number}}/{{date_format(date_create($error->date_time_created), 'y')}}
  </h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/store-error-corrective-action/'.$error->uuid)}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<!--<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Station responsible<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('station_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" name="station_id">
								<option value="" disabled selected>Select a functional unit</option>
								@if($stations)
									@php $selection = (old('station_id'))? 'selected' : null; @endphp
									@foreach($stations as $station)
										
										<option value="{{$station->uuid}}" {{(is_null($selection) && ($station->uuid == $error->station()->first()->uuid))? 'selected':$selection}}>{{$station->name}}</option>
											
									@endforeach
								@endif
							 </select>
							@if($errors->has('station_id'))
								<span class="w3-small w3-text-red">{{$errors->first('station_id')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>-->
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Corrective action<span class="w3-text-red">*</span></label>
							<textarea class="w3-input w3-border {{($errors->has('corrective_action')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
										placeholder="Give a corrective action done to the error"
										name="corrective_action"
										rows="4">{{old('corrective_action')}}</textarea>
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
										rows="4">{{old('cause')}}</textarea>
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
								<option value="" disabled selected>Did the error originate from the station?</option>
								<option value="0" {{(old('error_origin') == '0')? 'selected':null}}>Not from the station</option>
								<option value="1" {{(old('error_origin') == '1')? 'selected':null}}>Yes, from the station</option>
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
						style="display:{{(strval(old('error_origin')) == '0' || strval(old('error_origin')) == '1')?'block':'none'}};">
						<div class="w3-col s12 m10 l10 w3-left">
							@if(old('error_origin') == '1')
							<label class="w3-text-dark-gray">Error causing AIO<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('originator_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" name="originator_id">
								<option value="" selected>Select the officer who caused the error</option>
								@if($error->station()->first()->accountStation()->get())
									@foreach($error->station()->first()->accountStation()->get() as $account_station)
										
										<option value="{{$account_station->account()->first()->uuid}}" {{(old('originator_id') == $account_station->account()->first()->uuid)? 'selected':null}}>{{$account_station->account()->first()->first_name}} {{$account_station->account()->first()->last_name}}</option>
											
									@endforeach
								@endif<!-- AJAX call based on station responsible-->
							 </select>
							@elseif(old('error_origin') == '0')
							<label class="w3-text-dark-gray">Originator<span class="w3-text-red">*</span></label>
							<textarea class="w3-input w3-border {{($errors->has('originator')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
										placeholder="Describe the originator of the error"
										name="originator"
										rows="2">{{old('originator')}}</textarea>
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
							<label class="w3-text-dark-gray">Date of reporting<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('date_time_created')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									type="datetime-local" 
									name="date_time_created"
									placeholder="Enter the date and time you are reporting the error"
									autocomplete="off"
									value="{{old('date_time_created')}}">
							@if($errors->has('date_time_created'))
								<span class="w3-small w3-text-red">{{$errors->first('date_time_created')}}</span>
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
									rows="2">{{old('remarks')}}</textarea>
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
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Report a new error">Report&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
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
		xhr.open("GET", "{{url('get-account-station').'/'.$error->station()->first()->uuid}}");
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
