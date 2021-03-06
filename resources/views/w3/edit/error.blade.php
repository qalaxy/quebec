@extends('w3.layout.app')

@section('title')
<title>Edit reported error</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Edit error</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/update-error/'.$error->uuid)}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Station of origin</label>
							<select class="w3-select w3-border {{($errors->has('reported_station_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="reported_station_id"
									onchange="getStationFunctions(this.value);">
								<option value="" disabled selected>Select a station which the error originated</option>
								@if($stations)
									@php $selected = null; @endphp
									@foreach($stations as $station)
										<option value="{{$station->uuid}}" disabled
											@if(old('reported_station_id') == $station->uuid && is_null($seleceted))
												@php $selected = 'selected'; @endphp
												{{$selected}}

											@elseif(is_null($selected) && $error->reportedStation()->first()->uuid == $station->uuid)
												@php $selected = 'selected'; @endphp
												{{$selected}}
											@endif
											>{{$station->name}}
										</option>
									@endforeach
								@endif
							 </select>
							@if($errors->has('reported_station_id'))
								<span class="w3-small w3-text-red">{{$errors->first('reported_station_id')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Function</label>
							<select class="w3-select w3-border {{($errors->has('function_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									id="functions"
									name="function_id">
								<option value="" disabled selected>Select a functional unit</option>
								@if($functions)
									@php $selection = null; @endphp
									@foreach($functions as $func)

										<option value="{{$func->uuid}}" disabled

											@if(old('function_id') == $func->uuid && is_null($selection))
												@php $selection = 'selected'; @endphp
												{{$selection}}
											@elseif(is_null($selection) && $error->func()->first()->uuid == $func->uuid)
												@php $selection = 'selected'; @endphp
												{{$selection}}

											@endif

											>{{$func->name}}

										</option>
											
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
										rows="4">{{(old('description'))?old('description'):$error->description}}</textarea>
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
									rows="3">{{(old('impact'))?old('impact'):$error->impact}}</textarea>
							@if($errors->has('impact'))
								<span class="w3-small w3-text-red">{{$errors->first('impact')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Reporting station<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('reporting_station_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="reporting_station_id">
								<option value="" disabled selected>Select a station reporting the error</option>
								@if($account_stations)
								@php $selection = null; @endphp
									@foreach($account_stations as $account_station)
										<option value="{{$account_station->station()->first()->uuid}}" 
											@if(old('reporting_station_id') == $account_station->station->first()->uuid && is_null($selection))
												@php $selection = 'selected'; @endphp
												{{$selection}}
											@elseif(is_null($selection) 
													&& $account_station->station()->first()->uuid == $error->reportingStation()->first()->uuid)
												@php $selection = 'selected'; @endphp
												{{$selection}}
											@endif
											>{{$account_station->station()->first()->name}}
										</option>
											
									@endforeach
								@endif
							 </select>
							@if($errors->has('reporting_station_id'))
								<span class="w3-small w3-text-red">{{$errors->first('reporting_station_id')}}</span>
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
									rows="2">{{(old('remarks'))?old('remarks'):$error->remarks}}</textarea>
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
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Update the error">Update&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
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

<script src="{{asset('js/error.js')}}"></script>


@endsection
