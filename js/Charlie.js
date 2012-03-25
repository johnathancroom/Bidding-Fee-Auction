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
	var pusher = new Pusher("3a1bac2553ed8b533ac0");
	var channel = pusher.subscribe("presence-auction_data");
	var refresh = pusher.subscribe("presence-refresh");//temp
	
	channel.bind("pusher:subscription_succeeded", function(members) {			
		channel.bind("client-auction_data_updated", function(data) {
			update(data.id, data.extra_time);
		})
	})
	
	//temp
	refresh.bind("client-refresh", function(data) {
		location.reload(true);
	})
	
///////////////////////////////////////////////////////////////////////
//FUNCTIONS
///////////////////////////////////////////////////////////////////////
	
	function getServerTime() {
		var local = new Date();
		var utc = local.getTime() + (local.getTimezoneOffset() * 60000);
		var server = new Date(utc + (3600000*-7));
		return parseInt(server.getTime()/1000);
	}
	
	function update(id, extra_time) {
		//Update Price
		var price = $("li[data-id="+id+"] .price");
		var number = Math.round((((parseFloat(price.html())*100)+1)/100)*100)/100;
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
		
		price
			.stop().animate({backgroundColor: "#FCC"}, 100, function() {
				price.animate({backgroundColor: "transparent"}, 100);
			})
			.html(number);
			
		//Update time
		if(extra_time > 0)
		{
			var time = $("li[data-id="+id+"] .end_time");
			time.attr("data-end-time", parseInt(time.attr("data-end-time"))+extra_time);
		}
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
		
		//Check time
		var server_time = getServerTime();
		var end_time = $(this).prev(".end_time").attr("data-end-time");
		var time_left = end_time-server_time;
		if(time_left < 0)
		{//Auction ended
			return;
		}
		else if(time_left < 15)
		{//Bounce time forward
			var extra_time = 15-time_left;
		}
		console.log(time_left);
		
		//Server update
		$.ajax({
			url: functions, 
			data: {
				request: "bid",
				id: id,
				extra_time: extra_time
			}
		})
		
		//Client update
		var triggered = channel.trigger("client-auction_data_updated", {
			id: id,
			extra_time: extra_time
		});
		
		//Update self
		update(id, extra_time);
	})
	
	$("#new_auction").on("click", function() {
		$.get(functions, {
			request: "create"
		})
		var triggered = refresh.trigger("client-refresh", {});//temp
	})
	
	$("#test").on("click", function() {
		for(i=0;i<5;i++)
		{
			$(".button_bid").click();
		}
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