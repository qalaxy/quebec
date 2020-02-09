@extends('w3.layout.app')

@section('title')
<title>Edit station supervisor</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Edit station supervisor for: {{($station)?$station->name:null}}</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/update-station-supervisor/'.$station->uuid.'/'.$supervisor->uuid)}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Officer<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('account_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" name="account_id">
								<option value="" disabled>Select an officer</option>
								@if($accounts)
									@php $selected = null; @endphp
									@foreach($accounts as $account)
										<option value="{{$account->uuid}}" 
										@if(old('account_id') == $account->uuid) @php $selected = 'selected'; @endphp {{$selected}}
											@elseif(is_null($selected) && $supervisor->account_id == $account->id) @php $selected = 'selected'; @endphp {{$selected}}
										@endif
										>{{$account->first_name}} {{$account->middle_name}} {{$account->last_name}}</option>
											
									@endforeach
								@endif
							 </select>
							@if($errors->has('account_id'))
								<span class="w3-small w3-text-red">{{$errors->first('account_id')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Status<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('status')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" name="status">
								<option value="" disabled>Select user status at the station</option>
								@php $selected = null; @endphp
								<option value="0" 
									@if(old('status') == '0') @php $selected = 'selected'; @endphp {{$selected}}
											@elseif(is_null($selected) && $supervisor->status == '0') @php $selected = 'selected'; @endphp {{$selected}}
										@endif
								>Inactive</option>
								<option value="1" 
								@if(old('status') == '1') @php $selected = 'selected'; @endphp {{$selected}}
											@elseif(is_null($selected) && $supervisor->status == '1') @php $selected = 'selected'; @endphp {{$selected}}
										@endif
								>Active</option>
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
									value="{{(old('from'))?old('from'):$supervisor->from}}" />
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
									value="{{(old('to'))?old('to'):$supervisor->to}}" />
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
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Update station supervisor">Update&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
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

document.getElementById('stations').className += " w3-text-blue";
document.getElementById('menu-administration').className += " w3-text-blue";
menuAcc('administration');
w3_show_nav('menuQMS');

</script>


@endsection
