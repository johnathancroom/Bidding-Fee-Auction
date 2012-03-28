<?php require("Charlie.php");

$request = $_GET["request"];

if($request == "bid")
{
	$data = $charlie->bid($_GET["id"]);
	echo json_encode($data);
	//$pusher->trigger("presence-auction_data", "client-auction_data_updated", $_GET);
}
else if($request == "create")
{
	echo $charlie->auction_create();
	$pusher->trigger("refresh", "refresh", 1);
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