@extends('w3.layout.app')

@section('title')
<title>Account station</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Add station for: {{($account)?$account->first_name:null}}</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/store-account-station/'.$account->uuid)}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Station<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('station_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" name="station_id">
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
							<label class="w3-text-dark-gray">Status<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('status')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" name="status">
								<option value="" disabled selected>Select the status of officer at the station</option>
								<option value="0" {{(old('status') == '0')? 'selected':null}}>Inactive</option>
								<option value="1" {{(old('status') == '1')? 'selected':null}}>Active</option>
							 </select>
							@if($errors->has('status'))
								<span class="w3-small w3-text-red">{{$errors->first('status')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
				</div>
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">From<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('from')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="from"
									type="date"
									autocomplete="off"
									placeholder="Enter start date"
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
							<label class="w3-text-dark-gray">To</label>
							<input class="w3-input w3-border {{($errors->has('to')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="to"
									type="date"
									autocomplete="off"
									placeholder="Enter date officer left the station" 
									value="{{old('to')}}" />
							@if($errors->has('to'))
								<span class="w3-small w3-text-red">{{$errors->first('to')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="w3-row">
				<div class="w3-col w3-padding-small">
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Add a station for the user">Add&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
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

document.getElementById('accounts').className += " w3-text-blue";
document.getElementById('menu-administration').className += " w3-text-blue";
menuAcc('administration');
w3_show_nav('menuQMS');

</script>


@endsection
