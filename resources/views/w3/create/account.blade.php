@extends('w3.layout.app')

@section('title')
<title>Create user account</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Create user account</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/store-account')}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">First name<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('first_name')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="first_name"
									type="text"
									autocomplete="off"
									placeholder="Enter first name" 
									value="{{old('first_name')}}" />
							@if($errors->has('first_name'))
								<span class="w3-small w3-text-red">{{$errors->first('first_name')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Middle name</label>
							<input class="w3-input w3-border {{($errors->has('middle_name')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="middle_name"
									type="text"
									autocomplete="off"
									placeholder="Enter middle name" 
									value="{{old('middle_name')}}" />
							@if($errors->has('middle_name'))
								<span class="w3-small w3-text-red">{{$errors->first('middle_name')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Last name<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('last_name')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="last_name"
									type="text"
									autocomplete="off"
									placeholder="Enter last name" 
									value="{{old('last_name')}}" />
							@if($errors->has('last_name'))
								<span class="w3-small w3-text-red">{{$errors->first('last_name')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
				</div>
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Personal number<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('p_number')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="p_number"
									type="text"
									autocomplete="off"
									placeholder="Enter personal number" 
									value="{{old('p_number')}}" />
							@if($errors->has('p_number'))
								<span class="w3-small w3-text-red">{{$errors->first('p_number')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>	
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Phone number<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('phone_number')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="phone_number"
									type="text"
									autocomplete="off"
									placeholder="Enter phone number" 
									value="{{old('phone_number')}}" />
							@if($errors->has('phone_number'))
								<span class="w3-small w3-text-red">{{$errors->first('phone_number')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Email address<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('email')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="email"
									type="email"
									autocomplete="off"
									placeholder="Enter email address" 
									value="{{old('email')}}" />
							@if($errors->has('email'))
								<span class="w3-small w3-text-red">{{$errors->first('email')}}</span>
							@else
								<span>&nbsp;</span>
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

document.getElementById('accounts').className += " w3-text-blue";
document.getElementById('menu-administration').className += " w3-text-blue";
menuAcc('administration');
w3_show_nav('menuQMS');

</script>


@endsection
