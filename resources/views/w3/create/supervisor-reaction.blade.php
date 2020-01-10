@extends('w3.layout.app')

@section('title')
<title>Supervisor reaction</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Supervisor reaction to error: <a class="w3-hover-text-blue" href="{{url('error/'.$func_error->uuid)}}" style="text-decoration:none;" target="_blank">
{{$func_error->reportedStation()->first()->abbreviation}}/{{$func_error->func()->first()->abbreviation}}/{{$func_error->number}}/{{date_format(date_create($func_error->created_at), 'y')}} </a>
  </h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/store-error-supervisor-reaction/'.$func_error->uuid)}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Reaction<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('supervisor_reaction')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="supervisor_reaction"
									onchange="document.getElementById('remarks').value = null;">
								<option value="" disabled selected>Do you agree with the corretive action given?</option>
								<option value="0" {{(old('supervisor_reaction') == '0')? 'selected':null}}>No</option>
								<option value="1" {{(old('supervisor_reaction') == '1')? 'selected':null}}>Yes</option>
							 </select>
							@if($errors->has('supervisor_reaction'))
								<span class="w3-small w3-text-red">{{$errors->first('supervisor_reaction')}}</span>
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
										id="remarks"
										name="remarks"
										rows="3">{{old('remarks')}}</textarea>
							@if($errors->has('remarks'))
								<span class="w3-small w3-text-red">{{$errors->first('remarks')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="w3-row">
				<div class="w3-col w3-padding-small">
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Save the your reaction">Save&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
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

document.getElementById('errors').className += " w3-text-blue";
document.getElementById('menu-error').className += " w3-text-blue";
menuAcc('error');
w3_show_nav('menuQMS');

</script>


@endsection