<?php
// ipn.php – PayPal IPN 처리 (PHP5 호환)

// 1) PayPal에서 보낸 원본 데이터 읽기
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();

foreach ($raw_post_array as $keyval) {
    $keyval = explode('=', $keyval);
    if (count($keyval) == 2) {
        $myPost[$keyval[0]] = urldecode($keyval[1]);
    }
}

// 2) PayPal에 검증 요청 만들기
$req = 'cmd=_notify-validate';
foreach ($myPost as $key => $value) {
    $value = urlencode($value);
    $req .= "&$key=$value";
}

// PayPal 검증 URL
$paypal_url = "https://ipnpb.paypal.com/cgi-bin/webscr";
// 샌드박스 테스트 시:
// $paypal_url = "https://ipnpb.sandbox.paypal.com/cgi-bin/webscr";

// 3) cURL로 PayPal에 검증 요청 보내기
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
if (!$res) {
    // 에러 로그 남기기
    curl_close($ch);
    exit;
}
curl_close($ch);

// 4) PayPal 응답 분석
if (strcmp(trim($res), "VERIFIED") == 0) {

    // PayPal이 보낸 주요 데이터
    $payment_status = $_POST['payment_status'];     // Completed 등
    $gross          = $_POST['mc_gross'];           // 결제 금액
    $currency       = $_POST['mc_currency'];        // 통화
    $item_number    = $_POST['item_number'];        // 상품코드
    $txn_id         = $_POST['txn_id'];             // 거래 ID (unique)
    $receiver_email = $_POST['receiver_email'];     // 우리 판매자 계정
    $custom         = $_POST['custom'];             // pay.php에서 보낸 값 (유저ID|주문번호 등)

    // 4-1) 여기서 추가 검증 (XSS, SQL 인젝션 피하기 위해 escape 필요)
    //  - receiver_email 이 우리 계정이 맞는지
    //  - currency / 금액이 우리가 예상한 값과 같은지
    //  - txn_id가 이미 처리된 적 없는지

    if ($payment_status == "Completed" && $receiver_email == "your-paypal-email@example.com") {
        // DB 연결 후, 결제 완료 처리
        // 예) $custom 파싱해서 유저ID, ORDER ID 추출
        //     해당 유저의 광고제거권 활성화, 결제 로그 저장 등
    }

} else if (strcmp(trim($res), "INVALID") == 0) {
    // 위조된 요청일 수 있으므로 로그만 남김
}
?>
