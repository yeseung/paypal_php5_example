<?php
// PayPal 환경 설정
$paypal_url   = "https://www.paypal.com/cgi-bin/webscr"; // 실서버
// $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr"; // 샌드박스 테스트용
$paypal_id    = "your-paypal-email@example.com"; // PayPal 판매자 계정 이메일

// 상품 정보 (예: 광고 제거 연간 이용권)
$item_name    = "광고 제거 연간 이용권";
$item_number  = "ADFREE_YEAR_001";      // 내부용 상품 코드
$amount       = "12.00";                // 결제 금액 (USD 기준 예시)
$currency     = "USD";                  // 통화 코드 (KRW 사용 시 KRW)
$return_url   = "https://your-site.com/paypal/success.php";
$cancel_url   = "https://your-site.com/paypal/cancel.php";
$notify_url   = "https://your-site.com/paypal/ipn.php"; // IPN 처리용(선택)
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>PayPal 결제</title>
</head>
<body>
  <h1>PayPal 결제 테스트</h1>
  <p>상품명: <?php echo htmlspecialchars($item_name); ?></p>
  <p>가격: <?php echo htmlspecialchars($amount . ' ' . $currency); ?></p>

  <form action="<?php echo $paypal_url; ?>" method="post">
    <!-- 반드시 필요한 필드 -->
    <input type="hidden" name="business" value="<?php echo $paypal_id; ?>">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="item_name" value="<?php echo $item_name; ?>">
    <input type="hidden" name="item_number" value="<?php echo $item_number; ?>">
    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
    <input type="hidden" name="currency_code" value="<?php echo $currency; ?>">

    <!-- 돌아올 URL들 -->
    <input type="hidden" name="return" value="<?php echo $return_url; ?>">
    <input type="hidden" name="cancel_return" value="<?php echo $cancel_url; ?>">
    <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>">

    <!-- 사용자 정의 데이터(옵션: 유저ID, 주문번호 등) -->
    <input type="hidden" name="custom" value="<?php echo 'USER123|ORDER456'; ?>">

    <button type="submit">PayPal로 결제하기</button>
  </form>
</body>
</html>
