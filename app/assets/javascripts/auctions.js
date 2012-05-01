$(document).ready(function() {
/////////////////////////////////////////////////////////
//
//  Initialize
//
/////////////////////////////////////////////////////////
charles.listen('updateAuction', function(data) {
  auctionUpdate(data);
});

/////////////////////////////////////////////////////////
//
//  Interactions
//
/////////////////////////////////////////////////////////
$(".button_bid").on("click", function() {
  $.ajax({
    url: "/auctions/bid",
    data: {
    
      id: $(this).parent().parent().attr("data-id")
      
    },
    type: "POST",
    success: function(data, status, xhr) {
      if(!data.error)
      {
        auctionUpdate(data); // Update self
        charles.send('updateAuction', data); // Update others
      }
      else if(data.error == "bids") // No more bids
      {
        alert("No bids!");
      }
      else
      {
        triggerError(data.error);
      }
    },
    error: function(xhr, status, error) {
      if(parseInt(xhr.status) == 403) // Not logged in
      {
        alert("Please log in");
      }
      else 
      {
        triggerError(status);
      }
    }
  });
});

$(".gridded, .listed").on("click", function(e) {
  if($(this).hasClass("gridded")) var type = "window";
  if($(this).hasClass("listed")) var type = "list-item";
  
  $.ajax({
    url: "/auctions/view_as/"+type,
    type: "POST",
    success: function(data, status, xhr) {
      
      $(".auction, .title-bar").each(function(index, element) {
        $(this).removeClass("window list-item").addClass(type);
      });
      
    },
    error: function(xhr, status, error) {
      triggerError(status);
    }
  });
  
  e.preventDefault();
});

/////////////////////////////////////////////////////////
//
//  Auction Functions
//
/////////////////////////////////////////////////////////
function auctionUpdate(auction) {
  var window = $("[data-id="+auction.id+"]");
  
  var priceElement = $(".price", window);
  priceElement
    .stop().animate({ backgroundColor: "#FCC" }, 100, function() {
    	priceElement.animate({ backgroundColor: "transparent" }, 100);
    })
    .html(auction.price);
    
  $(".highest_bidder", window).html(auction.username);
  
  $(".user-bids").html(auction.bids+" bids");
}

/////////////////////////////////////////////////////////
//
//  Timer Functions
//
/////////////////////////////////////////////////////////
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
		  if($(this).parent().hasClass("title-bar")) return; // Skip over .title-bar
		  
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

/////////////////////////////////////////////////////////
});