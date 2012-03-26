<?php require("Charlie.php");

$request = $_GET["request"];

if($request == "bid")
{
	$charlie->bid($_GET["id"], $_GET["extra_time"]);
	//$pusher->trigger("presence-auction_data", "client-auction_data_updated", $_GET);
}
else if($request == "create")
{
	$charlie->auction_create();
}
else if($request == "login")
{
	parse_str($_GET["data"], $credentials);
	echo $charlie->login($credentials[username], $credentials[password]); //boolean
}
else if($request == "logout")
{
	$charlie->logout();
}