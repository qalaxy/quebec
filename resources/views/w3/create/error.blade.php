@extends('w3.layout.app')

@section('title')
<title>Error reporting</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Error reporting</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container">
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Function<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border w3-border-dark-gray" name="option">
								<option value="" disabled selected>Select the AIS function concerned</option>
								<option value="1">Option 1</option>
								<option value="2">Option 2</option>
								<option value="3">Option 3</option>
							 </select>
							<span class="w3-small w3-text-dark-gray">&nbsp;</span>
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Description<span class="w3-text-red">*</span></label>
							<textarea class="w3-input w3-border-dark-gray w3-border" placeholder="Describe the error" rows="5"></textarea>
							<span class="w3-small w3-text-dark-gray">&nbsp;</span>
						</div>
					</div>	
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Impact<span class="w3-text-red">*</span></label>
							<textarea class="w3-input w3-border-dark-gray w3-border" placeholder="Describe the impact of the error" rows="5"></textarea>
							<span class="w3-small w3-text-dark-gray">&nbsp;</span>
						</div>
					</div>
				</div>
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Station of origin<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border w3-border-dark-gray" name="option">
								<option value="" disabled selected>Select the station originating the error</option>
								<option value="1">Option 1</option>
								<option value="2">Option 2</option>
								<option value="3">Option 3</option>
							 </select>
							<span class="w3-small w3-text-dark-gray">&nbsp;</span>
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Responsibility<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border w3-border-dark-gray" name="option">
								<option value="" disabled selected>Are you responsible for the error?</option>
								<option value="1" disabled>Yes</option>
								<option value="0">No</option>
							 </select>
							<span class="w3-small w3-text-dark-gray">&nbsp;</span>
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Date of reporting<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border-dark-gray w3-border" type="text" placeholder="Enter the date you are reporting the error. Format DD-MM-YYYY">
							<span class="w3-small w3-text-dark-gray">&nbsp;</span>
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Time of reporting<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border-dark-gray w3-border" type="text" placeholder="Enter the time you are reporting the error. Format HH:MM">
							<span class="w3-small w3-text-dark-gray">&nbsp;</span>
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Remarks</label>
							<textarea class="w3-input w3-border-dark-gray w3-border" placeholder="Give your remarks" rows="1"></textarea>
							<span class="w3-small w3-text-dark-gray">&nbsp;</span>
						</div>
					</div>
				</div>
			</div>
			<div class="w3-row">
				<div class="w3-col w3-padding-small">
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Report a new error">Report&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
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

document.getElementById('logs').className += " w3-text-blue";
document.getElementById('menu-error').className += " w3-text-blue";
menuAcc('error');
w3_show_nav('menuQMS');

</script>


@endsection
