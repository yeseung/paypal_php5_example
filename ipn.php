<?php
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode('=', $keyval);
  if(count($keyval)==2) $myPost[$keyval[0]] = urldecode($keyval[1]);
}

$req = 'cmd=_notify-validate';
foreach ($myPost as $key => $value) {
  $value = urlencode($value);
  $req .= "&$key=$value";
}

$paypal_url = "https://ipnpb.paypal.com/cgi-bin/webscr";

$ch = curl_init($paypal_url);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

$res = curl_exec($ch);
curl_close($ch);

if (strcmp(trim($res), "VERIFIED") == 0) {
  $payment_status = $_POST['payment_status'];
  $receiver_email = $_POST['receiver_email'];
  if ($payment_status == "Completed" && $receiver_email == "your-paypal-email@example.com") {
    // TODO: 결제 완료 DB 처리
  }
}
?>
