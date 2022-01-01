<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
	<title>Dopamine</title>
	<meta http-equiv="x-ua-compatible" content="ie=edge" />
	<meta name="viewport" content="ie=edge" />

	<link rel="stylesheet" href="/fontawesome/css/all.css">
	<link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="/dopamine/css/style.css">

	<script src="/jquery/dist/jquery.min.js"></script>
	<script src="/bootstrap/dist/js/popper.min.js"></script>
	<script src="/tether/dist/js/tether.min.js"></script>
	<script src="/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="/dopamine/js/script.js"></script>
</head>
<body>

	<!--<div class="nav">
		<div class="navbar-default"></div>
	</div>-->

	<div class="login-sidenav">
		<div class="login-main-text text-light">
			<div><img src='/dopamine/images/dopamine.png' class="invert"></div>
			<h2><span>Dopamine</span></h2>
			<hr>
			<span class="lead">Greetings!</span>
			<p class="font-weight-light">Get your complete financial picture at a glance. With Dopamine, you can view your banking, investment, retirement, and credit card accounts - all in one place.</p>
			
			<div class="d-flex align-tems-center justify-content-center my-5">
				<button class="d-inline-flex align-tems-center justify-content-center btn-theme rounded-circle p-2 mx-2" tabindex="-1" data-id="#co1">
					<span class="d-inline-flex align-tems-center justify-content-center btn-theme-dark rounded-circle p-3">
						<i class="fa fa-boxes"></i>
					</span>
				</button>
				<button class="d-inline-flex align-tems-center justify-content-center btn-theme rounded-circle p-2 mx-2" tabindex="-1" data-id="#co2">
					<span class="d-inline-flex align-tems-center justify-content-center btn-theme-dark rounded-circle p-3">
						<i class="fa fa-coins"></i>
					</span>
				</button>
				<button class="d-inline-flex align-tems-center justify-content-center btn-theme rounded-circle p-2 mx-2" tabindex="-1" data-id="#co3">
					<span class="d-inline-flex align-tems-center justify-content-center btn-theme-dark rounded-circle p-3">
						<i class="fa fa-chart-line"></i>
					</span>
				</button>
				<button class="d-inline-flex align-tems-center justify-content-center btn-theme rounded-circle p-2 mx-2" tabindex="-1" data-id="#co4">
					<span class="d-inline-flex align-tems-center justify-content-center btn-theme-dark rounded-circle p-3">
						<i class="fa fa-cogs"></i>
					</span>
				</button>
			</div>

			<div class="text-center">
			<h3 class="font-weight-light card-option" id="co1">Manage your inventories</h3>
			<h3 class="font-weight-light card-option" id="co2">Manage your finances</h3>
			<h3 class="font-weight-light card-option" id="co3">Assess your business's performance</h3>
			<h3 class="font-weight-light card-option" id="co4">Personalize your interface</h3>
			</div>
		</div>
	</div>
	<div class="login-main my-5">
		<div class="col-md-6 col-sm-12">
			<div class="login-form">

				<form method="post" action="/Login/index" class="collapse show form">
						<?php if (isset($data['error'])) : ?>
					<div id="loginTag" class="card-tag card-tag-danger collapse show">
							<i class="fa fa-exclamation-triangle text-danger fa-2x mr-2"></i>
							<span class="h5 text-danger">
								<?php echo $data['error']; ?>
							</span>
							<hr>
						<?php else : ?>
					<button type="button" class="btn btn-xs float-right" data-toggle="collapse" data-target="#loginTag"><i class="fa fa-question-circle"></i> Help</button>
					<div id="loginTag" class="card-tag card-tag-info collapse">
						<?php endif; ?>
						If you donot have an anccount, <a href="#" data-toggle="collapse" data-target=".form" >signup here</a> and wait for approval
						<br>
						<a href="#">Forgot password?</a>
					</div>
					<div class="form-group">
						<input type="text" class="username" name="user" placeholder="Username" autofocus="true">
					</div>
					<div class="form-group">
						<input type="password" class="password" name="pass" placeholder="Password">
					</div>
					<div class="row p-3">
						<button type="button" data-toggle="collapse" data-target=".form" class="btn btn-light col">Sign up</button>
						<button type="submit" class="btn btn-theme-dark col ml-1">Login</button>
					</div>
				</form>

				<form method="post" action="/Login/signup" id="signup-form" class="collapse form silent">
					<div class="form-group row">
						<input type="text" class="form-control col-md ml-md-3 mr-md-1 mx-3" name="first_name" placeholder="First name" required>
						<input type="text" class="form-control col-md mr-md-3 mx-3 mt-md-0 mt-3" name="last_name" placeholder="Last name" required>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="username" id="username" placeholder="Username" aria-describedby="username-help" required>
						<small id="username-help" class="form-text">This will be used for loggin in</small>
					</div>
					<div class="form-group">
						<input type="email" class="form-control" name="email" id="email" placeholder="Email" aria-describedby="email-help" required>
						<small id="email-help" class="form-text">This will be used to cantact you</small>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="password" placeholder="Password" required>
					</div>
					<div class="row p-3">
						<button type="button" data-toggle="collapse" data-target=".form" class="btn btn-light col">Login</button>
						<button type="submit" class="btn btn-theme col ml-1">Sign up</button>
					</div>
				</form>

			</div>
		</div>
	</div>
	<div class="toasts fixed-bottom pl-3 pb-3"></div>
</body>
<script>

	var id;

	$(function(){
		$(document.documentElement).removeClass('dark');
	});

	if(window.innerWidth > 768){
		$('.login-sidenav').height(window.innerHeight);
	}

	$('.card-option:not(#co1)').hide();
	$('button[data-id]').mouseover(function() {
		if(id != $(this).data('id')) {
			id = $(this).data('id');
			$('.card-option:visible').fadeOut(300, function() {
				$(id).fadeIn(300);
			});
		}
	});

	$('#username, #email').keyup(function(){
		if($(this).is('#username')) {
			$.post('/Login/availability', 'username=' + $('#username').val(), function(data) {
				data = JSON.parse(data);
				if(data.status || $('#username').val() == '') {
					$('#signup-form').submit(function() { event.preventDefault(); });
					$("#username").removeClass('is-valid').addClass('is-invalid');
					$('#username-help').removeClass('valid-feedback').addClass('invalid-feedback').text('Username is taken');
				} else {
					$('#signup-form').unbind('submit');
					$("#username").removeClass('is-invalid').addClass('is-valid');
					$('#username-help').removeClass('invalid-feedback').addClass('valid-feedback').text('Username is valid');
				}
			});
		} else {
			$.post('/Login/availability', 'email=' + $('#email').val(), function(data) {
				data = JSON.parse(data);
				if(data.status || $('#email').val() == '') {
					$('#signup-form').submit(function() { event.preventDefault(); });
					$("#email").removeClass('is-valid').addClass('is-invalid');
					$('#email-help').removeClass('valid-feedback').addClass('invalid-feedback').text('Email is taken');
				} else {
					$('#signup-form').unbind('submit');
					$("#email").removeClass('is-invalid').addClass('is-valid');
					$('#email-help').removeClass('invalid-feedback').addClass('valid-feedback').text('Email is valid');
				}
			});
		}
	});

</script>
</html>