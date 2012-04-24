function AuctionUpdate(auction) {
  var window = $("[data-id="+auction.id+"]");
  
  var priceElement = $(".price", window);
  priceElement
    .stop().animate({ backgroundColor: "#FCC" }, 100, function() {
    	priceElement.animate({ backgroundColor: "transparent" }, 100);
    })
    .html(number_to_currency(auction.price));
}

$(".button_bid").on("click", function() {
  $.ajax({
    url: "/auctions/bid",
    data: {
      id: $(this).parent().attr("data-id")
    },
    method: "POST",
    success: function(data, status, xhr) {
      AuctionUpdate(data);
    }
  });
});

function getServerTime() {
	var local = new Date();
	var utc = local.getTime() + (local.getTimezoneOffset() * 60000);
	var server = new Date(utc + (3600000*-7)); //Arizona
	return parseInt(server.getTime()/1000);
}

function updateTime() {
	if(typeof window.lastUpdate == undefined)
	{
		window.lastUpdate = 0;
	}

	if(new Date().getTime() != window.lastUpdate)
	{
		window.lastUpdate = new Date().getTime()/1000;

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