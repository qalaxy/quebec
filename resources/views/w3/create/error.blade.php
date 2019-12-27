@extends('w3.layout.app')

@section('title')
<title>Error reporting</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Error reporting</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/store-error')}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Station of origin<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('station_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="station_id"
									onchange="getStationFunctions(this.value);">
								<option value="" disabled selected>Select a station</option>
								@if($stations)
									@foreach($stations as $station)
										<option value="{{$station->uuid}}" {{(old('station_id') == $station->uuid)? 'selected':null}}>{{$station->name}}</option>
											
									@endforeach
								@endif
							 </select>
							@if($errors->has('station_id'))
								<span class="w3-small w3-text-red">{{$errors->first('station_id')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Function<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('function_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									id="functions"
									name="function_id">
								<option value="" disabled selected>Select a functional unit</option>
								@if($functions)
									@foreach($functions as $func)
										
										<option value="{{$func->uuid}}" {{(old('function_id') == $func->uuid)? 'selected':null}}>{{$func->name}}</option>
											
									@endforeach
								@endif
							 </select>
							@if($errors->has('function_id'))
								<span class="w3-small w3-text-red">{{$errors->first('function_id')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Description<span class="w3-text-red">*</span></label>
							<textarea class="w3-input w3-border {{($errors->has('description')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
										placeholder="Describe the error"
										name="description"
										rows="3">{{old('description')}}</textarea>
							@if($errors->has('description'))
								<span class="w3-small w3-text-red">{{$errors->first('description')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>	
				</div>
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Impact<span class="w3-text-red">*</span></label>
							<textarea class="w3-input w3-border {{($errors->has('impact')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									placeholder="Describe the impact of the error" 
									name="impact"
									rows="3">{{old('impact')}}</textarea>
							@if($errors->has('impact'))
								<span class="w3-small w3-text-red">{{$errors->first('impact')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<!--<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Date and time of reporting<span class="w3-text-red">*</span></label>
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
					</div>-->
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
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Responsibility<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('responsibility')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="responsibility" onchange="showMessageInput(this.value);">
								<option value="" disabled selected>Are you giving corrective action to the error?</option>
								<option value="0" {{(old('responsibility') == '0')? 'selected':null}}>No</option>
								<option value="1" {{(old('responsibility') == '1')? 'selected':null}}>Yes</option>
							 </select>
							@if($errors->has('responsibility'))
								<span class="w3-small w3-text-red">{{$errors->first('responsibility')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small" id="message" style="display:{{(old('notification_message') || (old('responsibility') == '0'))?'inline':'none'}};">
						<div class="w3-col s12 m10 l10 w3-left">
							@if(old('notification_message') || (old('responsibility') == '0'))
								<label class="w3-text-dark-gray">Notification message<span class="w3-text-red">*</span></label>
								<textarea class="w3-input w3-border {{($errors->has('notification_message')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
										placeholder="Write a notification message to persons giving corrective action to the error" 
										name="notification_message" 
										rows="2">{{old('notification_message')}}</textarea>
								
								@if($errors->has('notification_message'))
									<span class="w3-small w3-text-red">{{$errors->first('notification_message')}}</span>
								@else
									<span>&nbsp;</span>
								@endif
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


function getStationFunctions(station){
	let options = '<option value="" disabled selected>Select a functional unit</option>';
	xhr = new XMLHttpRequest();
	xhr.open("GET", "{{url('get-station-functions')}}/"+station);
	xhr.send();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			let functions = JSON.parse(xhr.responseText);
			for(i in functions){
				options += '<option value="'+functions[i]['id']+'">'+functions[i]['name']+'</option>';
			}
			document.getElementById('functions').innerHTML = options;
		}
	}
}
</script>

<script src="{{asset('public/js/error.js')}}"></script>


@endsection
