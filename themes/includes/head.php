<!doctype html>
<html>
<head>
	<title>Bidding Fee Auctions</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="/themes/css/stylesheet.css">
</head>
<body>
	<nav>
		<!-- Login -->
		<div id="login">
		<? if(!$loggedIn): ?>
			<form id="form_login" action="#" method="POST">
				<input class="input_login" type="text" name="username" placeholder="Username">
				<input class="input_login" type="password" name="password" placeholder="Password">
				<input id="submit_login" type="submit" value="Login">
			</form>
		<? else: ?>
			<?= $user["username"]; ?>
			<span class="bids"><?= $user["bids"]; ?></span> bids
			<input id="logout" type="button" value="Logout">
		<? endif; ?>
		</div>
	</nav>