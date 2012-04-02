<?php require("Charlie.php");

if(1==1)
{//authorized
	echo $pusher->socket_auth($_POST["channel_name"], $_POST["socket_id"]);
}
else
{
	header("", true, 403);
	echo "Not authorized";
}