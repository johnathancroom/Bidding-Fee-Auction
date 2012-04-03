<!doctype html>
<html>
<head>
	<title>Bidding Fee Auctions</title>
	<meta charset="utf-8">
	<style type="text/css">
		.ending {
			color: red;
		}
	</style>
	<link rel="stylesheet" type="text/css" href="/themes/css/bid-window.css">
</head>

<body>
	<!-- Login -->
	<? if(!$loggedIn): ?>
		<form id="form_login" action="#" method="POST">
			<input type="text" name="username" placeholder="Username">
			<input type="password" name="password" placeholder="Password">
			<input type="submit" id="submit_login" value="Login">
		</form>
	<? else: ?>
		<?= $user["username"]; ?>
		<span class="bids"><?= $user["bids"]; ?></span> bids
		<input type="button" id="logout" value="Logout">
	<? endif; ?>
	
	<!-- Separator -->
	<hr>