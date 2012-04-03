<? 
if($user["stripe_id"] != null)
{
	$customer = Stripe_Customer::retrieve($user["stripe_id"]);
	?>
	<h2>Previous credit card</h2>
	<? echo $customer["active_card"]["last4"];
}
?>
<h2>New credit card</h2>
<form action="" method="POST" id="payment_form">
	<label>Card Number</label>
	<input type="text" size="20" autocomplete="off" class="card-number" value="4242424242424242">
	
	<br>
	
	<label>CVC</label>
	<input type="text" size="4" autocomplete="off" class="card-cvc" value="1234">
	
	<br>
	
	<label>Expiration (MM/YYYY)</label>
	<input type="text" size="2" class="card-expiry-month" value="06">
	<span> / </span>
	<input type="text" size="4" class="card-expiry-year" value="2014">
	
	<br>
	
	<button type="submit" class="submit-button">Submit Payment</button>
</form>