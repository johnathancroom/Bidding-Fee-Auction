$(document).ready(function() {
///////////////////////////////////////////////////////////////////////
//PUSHER
///////////////////////////////////////////////////////////////////////
	
	//Pusher logging
	Pusher.log = function(message) {
		//if (window.console && window.console.log) window.console.log(message);
	};
	WEB_SOCKET_DEBUG = true; //Flash logging
	
	
	Pusher.channel_auth_endpoint = "/lib/Pusher_auth.php"; // Authentication for presence or private channels
	var pusher = new Pusher("7da53c26a313d349592f");
	var channel = pusher.subscribe("presence-auction_data");
	var refresh = pusher.subscribe("refresh");//temp
	
	channel.bind("pusher:subscription_succeeded", function(members) {			
		channel.bind("client-auction_data_updated", function(data) {
			update(data.id, data.price, data.end_time, data.highest_bidder);
		})
	})
	
	//temp
	refresh.bind("refresh", function(data) {
		location.reload(true);
	})
	
///////////////////////////////////////////////////////////////////////
//FUNCTIONS
///////////////////////////////////////////////////////////////////////
	
	function getServerTime() {
		var local = new Date();
		var utc = local.getTime() + (local.getTimezoneOffset() * 60000);
		var server = new Date(utc + (3600000*-7));//Arizona
		return parseInt(server.getTime()/1000);
	}
	
	function update(id, price, end_time, highest_bidder) {
		var auction = $("[data-id="+id+"]");
		
		//Update Price
		var priceElement = $(".price", auction);
		var number = price;
		var temp = number.toString();
		temp = temp.split(".");
		if(temp[1] == undefined)
		{
			number += ".00";
		}
		else if(temp[1].length == 1)
		{
			number += "0";
		}
		
		priceElement
			.stop().animate({backgroundColor: "#FCC"}, 100, function() {
				priceElement.animate({backgroundColor: "transparent"}, 100);
			})
			.html("$"+number);
			
		//Update time
		$(".end_time", auction)
			.attr("data-end-time", parseInt(end_time));
			
		//Update bidder
		$(".highest_bidder", auction)
			.html(highest_bidder);
	}
	
	function updateTime() {
		if(typeof lastUpdate == undefined)
		{
			var lastUpdate = 0;
		}
		
		if(new Date().getTime() != lastUpdate)
		{
			lastUpdate = new Date().getTime()/1000;
			
			$(".end_time").each(function() {
				var server_time = getServerTime();
				
				var end_time = $(this).attr("data-end-time");
				var time_left = end_time-server_time;
				
				if(time_left > 0)
				{
					var temp_time = time_left;
					var h = Math.floor(time_left/3600).toString();
					if(h.length < 2) h = "0"+h;
					var m = (Math.floor(time_left/60)%60).toString();
					if(m.length < 2) m = "0"+m;
					var s = (Math.floor(time_left/1)%60).toString();
					if(s.length < 2) s = "0"+s;
				
					var msg_time = h+":"+m+":"+s;
				}
				else if(time_left == 0)
				{
					var msg_time = "GOING";
				}
				else 
				{
					var msg_time = "ENDED";
				}
				
				if(time_left <= 10)
				{
					$(this).html("<span class='ending'>"+msg_time+"</span>");
				}
				else 
				{
					$(this).html(msg_time);
				}
			})
		}
	}
	updateTime();
	setInterval(updateTime, 100);
	
///////////////////////////////////////////////////////////////////////
//INTERACTIONS
///////////////////////////////////////////////////////////////////////
	
	var functions = "/lib/functions.php";
	
	$(".button_bid").on("click", function() {
		var id = $(this).parent().attr("data-id");
		
		//Server update
		$.ajax({
			url: functions, 
			data: {
				request: "bid",
				id: id
			},
			success: function(data) {
				data = $.parseJSON(data);
				
				if(data.error != undefined)
				{
					console.log(data.error);
				}
				else
				{
					//Self update
					update(id, data.price, data.end_time, data.highest_bidder);
					
					//Client update
					var triggered = channel.trigger("client-auction_data_updated", {
						id: id,
						price: data.price,
						end_time: data.end_time,
						highest_bidder: data.highest_bidder
					});
				}
			}
		})
	})
	
	$("#new_auction").on("click", function() {
		$.get(functions, {
			request: "create"
		})
	})
	
	$("#form_login").submit(function(e) {
		var data = $(this).serialize();
		$.get(functions, {
			request: "login",
			data: data
		}, function(data) {
			//Boolean returned
		})
		
		e.preventDefault();//Don't submit
	})
	$("#logout").on("click", function() {
		$.get(functions, {
			request: "logout"
		})
	})
})