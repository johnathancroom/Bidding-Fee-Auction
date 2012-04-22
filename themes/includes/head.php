<!doctype html>
<html>
<head>
	<title>Bidding Fee Auctions</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="/themes/css/stylesheet.css">
</head>
<body>
	<div class="topper"></div>
	<section class="container">
		<header class="sidebar">
  		<div class="logo"></div>
  		<nav class="nav">
  		  <a class="home" href="#">home</a>
  		  <a class="about" href="#">about</a>
  		  <a class="help" href="#">help</a>
  		</nav>
			<section class="user_functions">
				<!-- Login -->
				<div class="container">
				<? if(!$loggedIn): ?>
					<form id="form_login" action="#" method="POST">
						<input type="text" name="username" placeholder="Username">
						<input type="password" name="password" placeholder="Password">
						<input id="submit_login" type="submit" value="Login">
					</form>
				<? else: ?>
					<?= $user["username"]; ?>
					<span class="bids"><?= $user["bids"]; ?></span> bids
					<input id="logout" type="button" value="Logout">
				<? endif; ?>
				</div>
			</section>
			<div class="user_functions_decor"></div>
  		</header>
		<section class="content">