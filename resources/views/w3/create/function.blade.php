@extends('w3.layout.app')

@section('title')
<title>Create function</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Create function</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/store-function')}}">
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
									placeholder="Enter the name of the function" 
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
							<label class="w3-text-dark-gray">Abbreviation<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border {{($errors->has('abbreviation')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="abbreviation"
									type="text"
									autocomplete="off"
									placeholder="Enter the abbreviation of the function" 
									value="{{old('abbreviation')}}" />
							@if($errors->has('abbreviation'))
								<span class="w3-small w3-text-red">{{$errors->first('abbreviation')}}</span>
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
									placeholder="Describe the AIM function" 
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
			</div>
			<div class="w3-row">
				<div class="w3-col w3-padding-small">
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Create function">Create&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
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

document.getElementById('functions').className += " w3-text-blue";
document.getElementById('menu-administration').className += " w3-text-blue";
menuAcc('administration');
w3_show_nav('menuQMS');

</script>


@endsection
