<?php require("lib/Charlie.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<title>LindenBid</title>
	<meta charset="utf-8">
	<style type="text/css">
		li {
			background-color: #eee;
			margin: 5px 0;
		}
		li input {
			float: right;
		}
		.ending {
			color: red;
		}
	</style>
</head>

<body>
	<!-- Login -->
	<?php 
	if(!$loggedIn)
	{
		?>
		<form id="form_login" action="#" method="POST">
			<input type="text" name="username" placeholder="Username">
			<input type="password" name="password" placeholder="Password">
			<input type="submit" id="submit_login" value="Login">
		</form>
		<?php 
	}
	else
	{
		echo $user[username];
		?>
		<input type="button" id="logout" value="Logout">
		<?php
	}
	?>
	
	<!-- Separator -->
	<hr>
	
	<!-- Bidding Tiles -->
	<input type="button" id="new_auction" value="Create New Auction">
	<input type="button" id="test" value="Test clicks">
	<ul>
		<?php
			$auction_rows = $charlie->db_select("auctions");
			foreach($auction_rows as $row => $data)
			{
				echo "<li data-id='".$data[id]."'>";
					echo $data[name]." ($<span class='price'>".$data[price]."</span>) ";
					
					echo "<span class='end_time' data-end-time='".$data[end_time]."'></span>";
					
					echo "<input type='button' class='button_bid' value='Bid!'>";
				echo "</li>";
			}
		?>
	</ul>
	
	<!-- External JavaScript -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
	<!-- Internal JavaScript -->
	<script src="/js/pusher.min.js"></script>
	<script src="/js/jquery.color.js" type="text/javascript"></script>
	<script src="/js/Charlie.js" type="text/javascript"></script>
</body>
</html>