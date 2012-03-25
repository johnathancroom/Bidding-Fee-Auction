<?php require("Charlie.php");

if(1==1)
{//logged in
	$presence_data = array("name" => $user->$name);
	echo $pusher->presence_auth($_POST[channel_name], $_POST[socket_id], md5(rand()), $presence_data);
}
else
{
	header("", true, 403);
	echo "Not authorized";
}