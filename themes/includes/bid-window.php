<div class="window" data-id="<?= $row["auction_id"]; ?>">
	<a href="/auction/<?= $row["auction_id"]; ?>" class="item_name"><?= $row["name"]; ?></a>
	<a href="/auction/<?= $row["auction_id"]; ?>"><img src="/themes/images/ipad.png" alt="ipad"></a>
	<div class="retail">$4,000 retail</div>
	<div class="end_time" data-end-time="<?= $row["end_time"]; ?>">00:00:00</div>
	<div class="price">$<?= $row["price"]; ?></div>
	<div class="highest_bidder"><?= $row["username"]; ?></div>
	<input type="button" class="button_bid" value="BID">
</div>