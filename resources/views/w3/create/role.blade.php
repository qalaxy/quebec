@extends('w3.layout.app')

@section('title')
<title>Create role</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Create role</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/store-role')}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Name<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('name')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="name"
									type="text"
									autocomplete="off"
									placeholder="Enter name" 
									value="{{old('name')}}" />
							@if($errors->has('name'))
								<span class="w3-small w3-text-red">{{$errors->first('name')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Description<span class="w3-text-red">*</span></label>
							<textarea class="w3-input w3-border {{($errors->has('description')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									placeholder="Describe the permission" 
									name="description"
									autocomplete="off"
									rows="5">{{old('description')}}</textarea>
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
							<label class="w3-text-dark-gray" onclick="getUserStations();">Applicability<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('global')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="global"
									onchange="getStations(this.value)">
								<option value="" disabled selected>Which stations do this role applies?</option>
								@if(Auth::user()->account()->first() && Auth::user()->account()->first()->accountStation()->get())
									<option value="1" {{(old('global') == '1')? 'selected':null}}>
										@php 
											$account_stations = Auth::user()->account()->first()->accountStation()->get();
											$i = 1;
											$len = count($account_stations);
										@endphp
										@foreach($account_stations as $account_station)
											@php $i++; @endphp
											@if($i == $len)
												{{$account_station->station()->first()->abbreviation}}&nbsp;&#38;
											@endif
											@if($i < $len)
												{{$account_station->station()->first()->abbreviation}}&#44;&nbsp;
											@endif
											@if($i > $len)
												{{$account_station->station()->first()->abbreviation}}
											@endif
												
										@endforeach
									</option>
								@endif
								<option value="2" {{(old('global') == '2')? 'selected':null}}>Selected stations</option>
								<option value="3" {{(old('global') == '3')? 'selected':null}}>All stations</option>
							 </select>
							@if($errors->has('global'))
								<span class="w3-small w3-text-red">{{$errors->first('global')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small" id="stations" style="display:{{(old('global') == '1' || old('global') == '2')?'block':'none'}};">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Stations<span class="w3-text-red">*</span></label>
							@if(old('global') == '1' || old('global') == '2')
							<select class="w3-select w3-border {{($errors->has('stations')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" name="stations[]" multiple size="6">
								<option value="" disabled>Select a stations applying for role</option>
								@if($stations)
									@foreach($stations as $station)
										@if(old('stations'))
											@foreach(old('stations') as $old_stn)
												@if($old_stn == $station->uuid)
													<option value="{{$station->uuid}}" selected >{{$station->name}}</option>
													@php continue 2; @endphp
												@endif
											@endforeach	
										<option value="{{$station->uuid}}">{{$station->name}}</option>
										@elseif(old('global') == '1')
											@foreach(Auth::user()->account()->first()->accountStation()->get() as $account_station)
												@if($station->id == $account_station->station()->first()->id)
													<option value="{{$station->uuid}}" disabled selected>{{$station->name}}</option>
													@php continue 2; @endphp
												@endif
											@endforeach
											<option value="{{$station->uuid}}" disabled>{{$station->name}}</option>
										@else
											<option value="{{$station->uuid}}">{{$station->name}}</option>
										@endif
										
									@endforeach
								@endif
							 </select>
							@if($errors->has('stations'))
								<span class="w3-small w3-text-red">{{$errors->first('stations')}}</span>
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
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Create role">Create&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
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

document.getElementById('roles').className += " w3-text-blue";
document.getElementById('menu-administration').className += " w3-text-blue";
menuAcc('administration');
w3_show_nav('menuQMS');


function getStations(global){
	let stations_div = document.getElementById('stations');
	if(global == 2 || global == 1){
		let input = '<label class="w3-text-dark-gray">Stations<span class="w3-text-red">*</span></label>';
		input += '<select class="w3-select w3-border w3-border-dark-gray" name="stations[]" multiple size="6">';
		
		let option = '<option value="" disabled>Select a stations applying for role</option>';
		
		let xhr = new XMLHttpRequest();
		
		if(global == 1){
			xhr.open("GET", "{{url('get-role-user-stations')}}");
			xhr.send();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 && xhr.status == 200){
					let data = JSON.parse(xhr.responseText);
					let stations = data.stations;
					let user_stations = data.user_stations;
					
					Loop1:
					for(i in stations){
						Loop2:
						for(j in user_stations){
							if(stations[i]['id'] == user_stations[j]['id']){
								option += '<option value="'+ stations[i]['id']+'" disabled selected>'+stations[i]['name']+'</option>';
								continue Loop1;
							}
						}
						option += '<option value="'+ stations[i]['id']+'" disabled>'+stations[i]['name']+'</option>';
					}
					input += option + '</select><span>&nbsp;</span>';
					stations_div.children[0].innerHTML = input;
					stations_div.style.display = 'block';
					console.log(stations_div.children[0]);
				}
			}
		}
		else if(global == 2){
			xhr.open("GET", "{{url('get-role-stations')}}");
			xhr.send();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 && xhr.status == 200){
					let stations = JSON.parse(xhr.responseText);				
					for(i in stations){
						option += '<option value="'+ stations[i]['id']+'">'+stations[i]['name']+'</option>';
					}
					input += option + '</select><span>&nbsp;</span>';
					stations_div.children[0].innerHTML = input;
					stations_div.style.display = 'block';
					console.log(stations_div.children[0]);
				}
			}
		}
		
	}else{
		stations_div.children[0].innerHTML = null;
		stations_div.style.display = 'none';
	}
}


</script>


@endsection
