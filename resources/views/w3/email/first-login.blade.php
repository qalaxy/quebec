<!DOCTYPE html>
<html>
	<head>
		<title>AIM Email</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<style>
			p{
				color:#000000;
			}
		</style>
	</head>
	<body>
		<div class="w3-container">
			<h2 class="w3-blue">Registration to AIM System</h2>
			<p class="w3-leftbar">You have been registered anew by <strong>{{$user->account()->first()->owner()->first()->name}}</strong> in AIM System.</p>
			<p class="w3-leftbar">Click <a class="w3-button w3-blue" href="{{config('app.url').'/first-login/'.encrypt($user->uuid)}}">here<a> to log in</p>
		</div>
	</body>
</html>