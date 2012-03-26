<?php 
class Charlie {
	function db_init() {
		date_default_timezone_set("America/Phoenix");
		mysql_connect("localhost", "root", "root") or die(mysql_error());
		mysql_select_db("lindenbid") or die(mysql_error());
	}
	function db_select($choice, $id) {
		if($choice == "auctions")
		{
			$query = mysql_query("SELECT *, auctions.id AS auction_id, users.id AS user_id FROM auctions, users WHERE auctions.end_time > '".mysql_escape_string(time())."' AND auctions.bidder_id=users.id ORDER BY auctions.end_time") or die(mysql_error());
			while($row = mysql_fetch_array($query, MYSQL_ASSOC))
			{
				$rows[$row[auction_id]] = $row;
			}
			return $rows;
		}
	}
	function bid($id, $extra_time) {
		$query = mysql_query("SELECT end_time, price FROM auctions WHERE id='".mysql_escape_string($id)."'") or die(mysql_error());
		$row = mysql_fetch_array($query, MYSQL_ASSOC);
		$end_time = $row[end_time];
		$price = $row[price]*100;
		$price += 1;
		$price /= 100;
		
		$user = $this->checkLogin();
		mysql_query("UPDATE auctions SET price='".mysql_escape_string($price)."', bidder_id='".mysql_escape_string($user[id])."' WHERE id='".mysql_escape_string($id)."'") or die(mysql_error());
		
		//Time update
		if($extra_time > 0 && time() < $end_time)
		{
			mysql_query("UPDATE auctions SET end_time='".mysql_escape_string($end_time+$extra_time)."' WHERE id='$id'");
		}
		
		//History Update
		mysql_query("INSERT INTO history (auction_id, price, bidder_id, time) VALUES(
			'".mysql_escape_string($id)."',
			'".mysql_escape_string($price)."',
			'".mysql_escape_string($user[id])."',
			'".mysql_escape_string(time())."'
		)");
	}
	function auction_create() {
		$query = mysql_query("INSERT INTO auctions (name, end_time) 
			VALUES (
				'Generated Name',
				'".mysql_escape_string(time()+12)."'
			)") or die(mysql_error());
	}
	function checkLogin() {
		if(isset($_COOKIE[login]))
		{
			global $loggedIn;
			$query = mysql_query("SELECT * FROM users WHERE md5(id)='".mysql_escape_string($_COOKIE[login])."'") or die(mysql_error());
			$loggedIn = 1;
			return mysql_fetch_array($query);
		}
	}
	function login($username, $pass) {
		$query = mysql_query("SELECT * FROM users WHERE username='".mysql_escape_string($username)."' AND password='".mysql_escape_string(md5($pass))."'") or die(mysql_error());
		$row = mysql_fetch_array($query, MYSQL_ASSOC);
		if(mysql_num_rows($query) == 1)
		{
			setcookie("login", md5($row[id]), time()+3600*24*30, "/");
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
}

// Initialization
require("Pusher.php");
$pusher = new Pusher("3a1bac2553ed8b533ac0", "1a2dec72f789117966f7", "16968"); //key, secret, app_id

$charlie = new Charlie();
$charlie->db_init();

$user = $charlie->checkLogin();