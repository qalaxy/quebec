<!DOCTYPE html>
<html>
	<head>
		<title>W3.CSS</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="icon" href="{{asset('public/images/logo/kcaa.png')}}">
		<style>
			.center{
				display: block;
				margin: auto;
				width: 50%;
			}
			@media only screen and (max-width: 1200px) {
			  .center {
				display: block;
				margin: auto;
				width: 100%;
			  }
			}
		</style>
	</head>
<body class="w3-sand">
	<div class="w3-container" style="padding:50px;">
		<div class="w3-card-4 w3-white center" style="max-width:600px">
			<div class="w3-center"><br>
				<img src="{{asset('public/images/logo/kcaa.png')}}" alt="AIM" style="width:30%" class="w3-margin-top">
			</div>
			@if (session('status'))
				<div class="w3-panel w3-display-container w3-leftbar w3-text-brown w3-pale-green w3-border-green">
					<span onclick="this.parentElement.style.display='none'"
					class="w3-button w3-pale-green w3-large w3-display-topright">&times;</span>
					<p>{{ session('status') }}</p>
				</div>
			@endif
			<form class="w3-container" method="POST" action="{{ route('password.update') }}">
				@csrf
				
				<input type="hidden" name="token" value="{{ $token }}">
				<div class="w3-section">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m12 l12 w3-left">
							<label class="w3-text-dark-gray">Email address</label>
							<input class="w3-input w3-border {{($errors->has('email')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="email"
									type="text"
									placeholder="Enter email to recover your logging in credentials" 
									value="{{ $email ?? old('email') }}" 
									readonly />
							@if($errors->has('email'))
								<span class="w3-small w3-text-red">{{$errors->first('email')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m12 l12 w3-left">
							<label class="w3-text-dark-gray">New password</label>
							<input class="w3-input w3-border {{($errors->has('password')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="password"
									type="password" 
									placeholder="Enter new password" />
							@if($errors->has('password'))
								<span class="w3-small w3-text-red">{{$errors->first('password')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m12 l12 w3-left">
							<label class="w3-text-dark-gray">Confirm password</label>
							<input class="w3-input w3-border w3-border-dark-gray" 
									name="password_confirmation"
									type="password" 
									placeholder="Confirm the above password" />
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m12 l12 w3-left">
							<button class="w3-button w3-block w3-blue w3-section w3-padding w3-hover-light-blue w3-large" type="submit">Send</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
