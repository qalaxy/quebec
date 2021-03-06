<!DOCTYPE html>
<html>
	<head>
		<title>Email</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="{{asset('css/w3.css')}}">
		<link rel="icon" href="{{asset('images/logo/kcaa.png')}}">
		<style>
			.center{
				display: block;
				margin-left: auto;
				margin-right: auto;
				width: 50%;
			}
			@media only screen and (max-width: 1200px) {
			  .center {
				display: block;
				margin-left: auto;
				margin-right: auto;
				width: 100%;
			  }
			}
		</style>
	</head>
<body class="w3-sand">
	<div class="w3-container" style="padding:50px;">
		<div class="w3-card-4 w3-white center" style="max-width:600px">
			<div class="w3-center"><br>
				<img src="{{asset('images/logo/kcaa.png')}}" alt="AIM" style="width:30%" class="w3-margin-top">
			</div>
			@if (session('status'))
				<div class="w3-panel w3-display-container w3-leftbar w3-text-brown w3-pale-green w3-border-green">
					<span onclick="this.parentElement.style.display='none'"
					class="w3-button w3-pale-green w3-large w3-display-topright">&times;</span>
					<p>{{ session('status') }}</p>
				</div>
			@endif
			<form class="w3-container" method="POST" action="{{ route('password.email') }}">
				@csrf
				<div class="w3-section">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m12 l12 w3-left">
							<label class="w3-text-dark-gray">Email address</label>
							<input class="w3-input w3-border {{($errors->has('email')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="email"
									type="text"
									placeholder="Enter email to recover your logging in credentials" 
									value="{{old('email')}}" />
							@if($errors->has('email'))
								<span class="w3-small w3-text-red">{{$errors->first('email')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m12 l12 w3-left">
							<button class="w3-button w3-block w3-blue w3-section w3-padding w3-hover-light-blue w3-large" type="submit">Send</button>
						</div>
					</div>
				</div>
			</form>
			<div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
				<span class="w3-right w3-padding w3-hide-small w3-text-blue w3-hover-text-dark-gray w3-large">
					<a href="{{ route('login') }}" style="text-decoration:none;">Go back to login form.</a>
				</span>
			</div>
		</div>
	</div>
</body>
</html>
