@extends('w3.layout.app')

@section('title')
<title>Account role</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Add role for: {{($account)?$account->first_name:null}}</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/store-account-role/'.$account->uuid)}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Role<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('role_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" name="role_id">
								<option value="" disabled selected>Select a role to assign {{$account->first_name}}</option>
								@if($roles)
									@foreach($roles as $role)
										
										<option value="{{$role->uuid}}" {{(old('role_id') == $role->uuid)? 'selected':null}}>{{$role->display_name}}</option>
											
									@endforeach
								@endif
							 </select>
							@if($errors->has('role_id'))
								<span class="w3-small w3-text-red">{{$errors->first('role_id')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="w3-row">
				<div class="w3-col w3-padding-small">
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Add account role">Add&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
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
