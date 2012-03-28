<?php

require("lib/Charlie.php");

require("lib/Twig/Autoloader.php");
Twig_Autoloader::register();
$twig = new Twig_Environment(new Twig_Loader_Filesystem("templates"));

$query = $_SERVER[QUERY_STRING];

if($query == "")
{
	echo $twig->render("index.html", array(
		"loggedIn" => $loggedIn,
		"user" => $user,
		
		"auction_rows" => $charlie->db_select("auctions")
	));
}
else
{
	header("Status: 404 Not Found");
	echo "404 page";
}