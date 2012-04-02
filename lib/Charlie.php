<?php 
class Charlie {
	function db_init() {
		date_default_timezone_set("America/Phoenix");
		mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die(mysql_error());
		mysql_select_db("lindenbid") or die(mysql_error());
	}
	function db_select($choice, $id=null) {
		if($choice == "auctions")
		{
			$query = mysql_query("SELECT *, auctions.id AS auction_id, users.id AS user_id FROM auctions, users WHERE auctions.end_time > '".mysql_escape_string(time())."' AND auctions.bidder_id=users.id ORDER BY auctions.end_time") or die(mysql_error());
			while($row = mysql_fetch_array($query, MYSQL_ASSOC))
			{
				$rows[$row["auction_id"]] = $row;
			}
			return $rows;
		}
		else if($choice == "auction")
		{
			$query = mysql_query("SELECT *, auctions.id AS auction_id, users.id AS user_id FROM auctions, users WHERE auctions.id='".mysql_escape_string($id)."' AND auctions.bidder_id=users.id ORDER BY auctions.end_time") or die(mysql_error());
			return mysql_fetch_array($query, MYSQL_ASSOC);
		}
	}
	function bid($id) {
		global $user;
		
		$query = mysql_query("SELECT end_time, price, bidder_id FROM auctions WHERE id='".mysql_escape_string($id)."'") or die(mysql_error());
		$row = mysql_fetch_array($query, MYSQL_ASSOC);
		$end_time = $row["end_time"];
		if(time() <= $end_time && $row["bidder_id"] != $user["id"])//Not ended & New bidder
		{
			//Update auction
			$price = $row["price"]*100;
			$price += 1;
			$price /= 100;
			
			mysql_query("UPDATE auctions SET price='".mysql_escape_string($price)."', bidder_id='".mysql_escape_string($user["id"])."' WHERE id='".mysql_escape_string($id)."'");
			
			$data["price"] = $price;
			
			//Push time back to x seconds 
			$time_left = $end_time-time();
			$add_time = 0;
			if($time_left <= 15)
			{
				$add_time = 15-$time_left; 
				mysql_query("UPDATE auctions SET end_time='".mysql_escape_string($end_time+$add_time)."' WHERE id='".mysql_escape_string($id)."'");
			}
			
			$data["end_time"] = $end_time+$add_time;
			
			//Bidder updates
			$data["highest_bidder"] = $user["username"];
			mysql_query("UPDATE users SET bids='".mysql_escape_string($user["bids"]-1)."' WHERE username='".mysql_escape_string($user["username"])."'");
			
			//History Update
			mysql_query("INSERT INTO history (auction_id, price, bidder_id, time) VALUES(
				'".mysql_escape_string($id)."',
				'".mysql_escape_string($price)."',
				'".mysql_escape_string($user["id"])."',
				'".mysql_escape_string(time())."'
			)");

			//Return appropriate data
			return $data;
		}
		else
		{
			return array("error"=>"error msg");
		}
	}
	function auction_create() {
		$query = mysql_query("INSERT INTO auctions (name, end_time) 
			VALUES (
				'Generated Name',
				'".mysql_escape_string(time()+10)."'
			)") or die(mysql_error());
	}
	function getLoggedInUser() {
		if(isset($_COOKIE["login"]))
		{
			global $loggedIn;
			$query = mysql_query("SELECT * FROM users WHERE md5(id)='".mysql_escape_string($_COOKIE["login"])."'") or die(mysql_error());
			$loggedIn = 1;
			return mysql_fetch_array($query);
		}
	}
	function login($username, $pass) {
		$query = mysql_query("SELECT * FROM users WHERE username='".mysql_escape_string($username)."' AND password='".mysql_escape_string(md5($pass))."'") or die(mysql_error());
		$row = mysql_fetch_array($query, MYSQL_ASSOC);
		if(mysql_num_rows($query) == 1)
		{
			setcookie("login", md5($row["id"]), time()+3600*24*30, "/");
			return true;
		}
		else
		{
			return false;
		}
	}
	function logout() {
		setcookie("login", "", time()-3600, "/");
	}
	function render() {
		global $user, $loggedIn;
		//Init
		require("Twig/Autoloader.php");
		Twig_Autoloader::register();
		$twig = new Twig_Environment(new Twig_Loader_Filesystem("themes"));
		
		//Query
		$query = $_SERVER["QUERY_STRING"];
		$query_split = explode("/", $query);
		if(!isset($query_split[1])) $query_split[1] = "home";
		
		//Decide where to go
		if($query_split[1] == "home")
		{//Page: home
			$content = $twig->render("pages/".$query_split[1].".html", array(
				"auction_rows" => $this->db_select("auctions")
			));
		}
		else if($query_split[1] == "auction")
		{//Page: auction
			$auction = $this->db_select("auction", $query_split[2]);
			if($auction != "")
			{//Auction exists
				$content = $twig->render("pages/".$query_split[1].".html", array(
					"row" => $auction
				));
			}
		}
		else if(file_exists("themes/pages/".$query_split[1].".html"))
		{//Page: other
			$content = $twig->render("pages/".$query_split[1].".html");
		}
		
		if(!isset($content))
		{//Page: not found
			header("Status: 404 Not Found");
			$content = $twig->render("pages/404.html");
		}
		
		//Render
		return
			//Head 
			$twig->render("includes/head.html", array(
				"loggedIn" => $loggedIn,
				"user" => $user
			))
			//Content
			.$content
			//Foot
			.$twig->render("includes/foot.html");
	}
}

//Config
define("PATH", "/Applications/MAMP/htdocs/Bidding-Fee-Auction/");
$config_file = PATH."config.php";
file_exists($config_file) or die("config.php file not loaded!");
require($config_file);

//Initialization
require("Pusher.php");
$pusher = new Pusher("7da53c26a313d349592f", PUSHER_SECRET, "16968"); //key, secret, app_id

$charlie = new Charlie();
$charlie->db_init();

$user = $charlie->getLoggedInUser();