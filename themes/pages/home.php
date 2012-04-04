<!-- Actions --> 
<!--<input type="button" id="new_auction" value="Create New Auction">-->
<!-- Bidding Tiles -->
<? foreach($auction_rows as $row): ?>
	<? include("themes/includes/bid-window.php"); ?>
<? endforeach; ?>