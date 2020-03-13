@extends('w3.layout.app')

@section('title')
<title>Edit account credentials</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Edit {{(Auth::user()->account()->first()->uuid == $account->uuid)? 'your account credentials' : 'account credentials for '.$account->user()->first()->name}}</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/update-account-credentials/'.$account->uuid)}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Email<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('email')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="email"
									type="email"
									autocomplete="off"
									placeholder="Enter login email" 
									value="{{(old('email')?old('email'):$account->user()->first()->email)}}" />
							@if($errors->has('email'))
								<span class="w3-small w3-text-red">{{$errors->first('email')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Old Password<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('old_password')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="old_password"
									type="password"
									autocomplete="off"
									placeholder="Enter old password" 
									value="" />
							@if($errors->has('old_password'))
								<span class="w3-small w3-text-red">{{$errors->first('old_password')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
				</div>
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">New password<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('password')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="password"
									type="password"
									autocomplete="off"
									placeholder="Enter new password" 
									value="" />
							@if($errors->has('password'))
								<span class="w3-small w3-text-red">{{$errors->first('password')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>	
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Confirm new password<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('password_confirmation')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="password_confirmation"
									type="password"
									autocomplete="off"
									placeholder="Enter new password again" 
									value="" />
							@if($errors->has('password_confirmation'))
								<span class="w3-small w3-text-red">{{$errors->first('password_confirmation')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="w3-row">
				<div class="w3-col w3-padding-small">
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Update account credentials">Update&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
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
