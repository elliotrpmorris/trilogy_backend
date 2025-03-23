<!doctype html>

<html lang="en">

<head>

<title><?=$this->config->config["pageTitle"];?> :: ADMIN</title>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" href="<?= base_url();?>assets/login/A.style.css.pagespeed.cf.eQk9-CoeFP.css">



<!-- Favicon -->

<link rel="icon" href="<?php echo base_url();?>assets/favicon/favicon.ico" type="image/x-icon"/>

<link rel="shortcut icon" href="<?php echo base_url();?>assets/favicon/favicon.ico" type="image/x-icon"/>

</head>

<body class="img js-fullheight" ><!-- style="background-image:url(<?= base_url();?>assets/images/NU-splash3.jpg); width: 100%; height: auto;" -->

<section class="ftco-section">

<div class="container">

<div class="row justify-content-center">

	<div class="col-md-6 text-center mb-5">

		<img src="<?= base_url();?>assets/images/logo.png">

		<!-- <h2 class="heading-section" style="color: ghostwhite; font-size: 16px; font-style: initial; font-weight:700;">Admin Login</h2> -->

	</div>

</div>

<div class="row justify-content-center">

	<div class="col-md-6 col-lg-4">

		<div class="login-wrap p-0">

			<!-- <h3 class="mb-4 text-center">Admin Login</h3> -->

			<h2 class="mb-4 text-center" style="color: ghostwhite; font-size: 16px; font-style: initial; font-weight:700;">Admin Login</h2>

			<form action="<?= site_url();?>auth/is_admin_login" method="post" class="signin-form">

				<div class="form-group">

					<input type="text" name="email" class="form-control" placeholder="Email" required autocomplete="off">

					<span toggle="#password-field" class="fa fa-fw fa-envelope-o field-icon toggle-password1"></span>

				</div>

				<div class="form-group">

					<input name="password" id="password-field" type="password" class="form-control" placeholder="Password" required autocomplete="off">

					<span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>

				</div>

				<div class="form-group">

					<button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>

				</div>

			</form>

		</div>

	</div>

</div>

</div>

</div>

</section>

<script src="<?= base_url();?>assets/login/jquery.min.js"></script>

<script src="<?= base_url();?>assets/login/popper.js+bootstrap.min.js+main.js.pagespeed.jc.9eD6_Mep8S.js"></script>

<script>eval(mod_pagespeed_T07FyiNNgg);</script>

<script>eval(mod_pagespeed_zB8NXha7lA);</script>

<script>eval(mod_pagespeed_xfgCyuItiV);</script>

<!-- <script defer src="https://static.cloudflareinsights.com/beacon.min.js" data-cf-beacon='{"rayId":"68da3987db532e5f","token":"cd0b4b3a733644fc843ef0b185f98241","version":"2021.8.1","si":10}'></script> -->

</body>

</html>

