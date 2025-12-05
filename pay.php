<?php
$paypal_url   = "https://www.paypal.com/cgi-bin/webscr";
$paypal_id    = "your-paypal-email@example.com";

$item_name    = "광고 제거 연간 이용권";
$item_number  = "ADFREE_YEAR_001";
$amount       = "12.00";
$currency     = "USD";

$return_url   = "https://your-site.com/paypal/success.php";
$cancel_url   = "https://your-site.com/paypal/cancel.php";
$notify_url   = "https://your-site.com/paypal/ipn.php";
?>
<!DOCTYPE html>
<html><body>
<form action="<?php echo $paypal_url; ?>" method="post">
<input type="hidden" name="business" value="<?php echo $paypal_id; ?>">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="item_name" value="<?php echo $item_name; ?>">
<input type="hidden" name="item_number" value="<?php echo $item_number; ?>">
<input type="hidden" name="amount" value="<?php echo $amount; ?>">
<input type="hidden" name="currency_code" value="<?php echo $currency; ?>">
<input type="hidden" name="return" value="<?php echo $return_url; ?>">
<input type="hidden" name="cancel_return" value="<?php echo $cancel_url; ?>">
<input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>">
<button type="submit">PayPal 결제하기</button>
</form>
</body></html>
