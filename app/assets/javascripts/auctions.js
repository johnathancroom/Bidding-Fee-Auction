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