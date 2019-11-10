@extends('w3.layout.app')

@section('title')
<title>Edit role</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Edit role</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{($role->uuid) ? url('/update-role').'/'.$role->uuid : null}}">
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
									value="{{old('name') ? old('name') : $role->name}}" />
							@if($errors->has('name'))
								<span class="w3-small w3-text-red">{{$errors->first('name')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Display name<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('display_name')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="display_name"
									type="text"
									autocomplete="off"
									placeholder="Enter name to be displayed" 
									value="{{old('display_name') ? old('display_name') : $role->display_name}}" />
							@if($errors->has('display_name'))
								<span class="w3-small w3-text-red">{{$errors->first('display_name')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>	
				</div>
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Description<span class="w3-text-red">*</span></label>
							<textarea class="w3-input w3-border {{($errors->has('description')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									placeholder="Describe the role" 
									name="description"
									autocomplete="off"
									rows="5">{{old('description') ? old('description') : $role->description}}</textarea>
							@if($errors->has('description'))
								<span class="w3-small w3-text-red">{{$errors->first('description')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>	
				</div>
			</div>
			<div class="w3-row">
				<div class="w3-col w3-padding-small">
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Update permission">Update&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
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
