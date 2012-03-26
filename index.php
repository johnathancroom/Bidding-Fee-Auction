<?php

require("lib/Charlie.php");

require("lib/Twig/Autoloader.php");
Twig_Autoloader::register();
$twig = new Twig_Environment(new Twig_Loader_Filesystem("templates"));

echo $twig->render("index.html", array(
	"loggedIn" => $loggedIn,
	"user" => $user,
	
	"auction_rows" => $charlie->db_select("auctions")
));