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
			$query = mysql_query("SELECT * FROM auctions WHERE end_time > '".time()."' ORDER BY end_time") or die(mysql_error());
			while($row = mysql_fetch_array($query, MYSQL_ASSOC))
			{
				$rows[$row[id]] = $row;
			}
			return $rows;
		}
	}
	function bid($id, $extra_time) {
		$query = mysql_query("SELECT end_time, price FROM auctions WHERE id='$id'") or die(mysql_error());
		$row = mysql_fetch_array($query, MYSQL_ASSOC);
		$end_time = $row[end_time];
		$price = $row[price]*100;
		$price += 1;
		$price /= 100;
		mysql_query("UPDATE auctions SET price='$price' WHERE id='$id'") or die(mysql_error());
		
		//Time update
		if($extra_time > 0 && time() < $end_time)
		{
			mysql_query("UPDATE auctions SET end_time='".($end_time+$extra_time)."' WHERE id='$id'");
		}
		
		// History Update
		mysql_query("INSERT INTO history (auction_id, price, time) VALUES('$id', '$price', '".time()."')");
		mysql_query("UPDATE auctions SET history_id='".mysql_insert_id()."' WHERE id='$id'");
	}
	function auction_create() {
		$query = mysql_query("INSERT INTO auctions (name, end_time) 
			VALUES (
				'Generated Name',
				'".(time()+13)."'
			)") or die(mysql_error());
	}
	function login($username, $pass) {
		$query = mysql_query("SELECT * FROM users WHERE username='".mysql_escape_string($username)."' AND password='".mysql_escape_string(md5($pass))."'") or die(mysql_error());
		$row = mysql_fetch_array($query, MYSQL_ASSOC);
		if(mysql_num_rows($query) == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

// Initialization
require("Pusher.php");
$pusher = new Pusher("3a1bac2553ed8b533ac0", "1a2dec72f789117966f7", "16968"); // key, secret, app_id

$charlie = new Charlie();
$charlie->db_init();