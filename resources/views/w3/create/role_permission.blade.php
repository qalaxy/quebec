@extends('w3.layout.app')

@section('title')
<title>Add role permission</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Add permission for: {{($role)?$role->display_name:null}}</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{(isset($role))?url('/store-role-permission/'.$role->uuid):null}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Permission<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('permission')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" name="permission">
								<option value="" disabled selected>Select a permission</option>
								@if($permissions)
									@foreach($permissions as $permission)
										@php $role_perms = $role->permission()->get(); @endphp
										@foreach($role_perms as $role_perm)
											@if($role_perm->name != $permission->name)
												<option value="{{$permission->uuid}}" {{(old('permission'))? 'selected':null}}>{{$permission->display_name}}</option>
											@endif
										@endforeach
									@endforeach
								@endif
							 </select>
							@if($errors->has('permission'))
								<span class="w3-small w3-text-red">{{$errors->first('permission')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>	
				</div>
			</div>
			<div class="w3-row">
				<div class="w3-col w3-padding-small">
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Create permission for a role">Create&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
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

</script>


@endsection
