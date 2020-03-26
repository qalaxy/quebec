@extends('w3.layout.app')

@section('title')
<title>System error reporting</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">System error reporting</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/store-system-error')}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Station<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('station_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="station_id">
								<option value="" disabled selected>Select a station which the error occurred</option>
								@if($account_stations)
									@foreach($account_stations as $account_station)
										<option value="{{$account_station->station()->first()->uuid}}" {{(old('station_id') == $account_station->station()->first()->uuid)? 'selected':null}}>{{$account_station->station()->first()->name}}</option>
											
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
							<label class="w3-text-dark-gray">System<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('system_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									id="systems"
									name="system_id">
								<option value="" disabled selected>Select the faulty system</option>
								@if($systems)
									@foreach($systems as $system)
										
									<option value="{{$system->uuid}}" {{(old('system_id') == $system->uuid)? 'selected':null}}>{{$system->name}}</option>
											
									@endforeach
								@endif
							 </select>
							@if($errors->has('system_id'))
								<span class="w3-small w3-text-red">{{$errors->first('system_id')}}</span>
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
										rows="8">{{old('description')}}</textarea>
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
							<label class="w3-text-dark-gray">Solution<span class="w3-text-red">*</span></label>
							<textarea class="w3-input w3-border {{($errors->has('solution')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									placeholder="Describe the solution to the error" 
									name="solution"
									rows="2">{{old('solution')}}</textarea>
							@if($errors->has('solution'))
								<span class="w3-small w3-text-red">{{$errors->first('solution')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Form<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('from')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="from"
									type="date"
									autocomplete="off"
									placeholder="Enter date" 
									value="{{old('from')}}" />

								
							@if($errors->has('from'))
								<span class="w3-small w3-text-red">{{$errors->first('from')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">To<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('to')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="to"
									type="date"
									autocomplete="off"
									placeholder="Enter date" 
									value="{{old('to')}}" />

								
							@if($errors->has('to'))
								<span class="w3-small w3-text-red">{{$errors->first('to')}}</span>
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

document.getElementById('system-errors').className += " w3-text-blue";
/*document.getElementById('menu-error').className += " w3-text-blue";
menuAcc('error');*/
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
