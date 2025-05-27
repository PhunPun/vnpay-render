<?php
$responseCode = $_GET['vnp_ResponseCode'] ?? '';
$txnRef = $_GET['vnp_TxnRef'] ?? '';
$amount = $_GET['vnp_Amount'] ?? '';

if ($responseCode === '00') {
    echo "<h2 style='color:green;'>Thanh toán thành công!</h2>";
    echo "<p>Đơn hàng: $txnRef</p><p>Số tiền: " . ($amount / 100) . " VND</p>";
} else {
    echo "<h2 style='color:red;'>Thanh toán thất bại hoặc bị hủy</h2>";
}
?>
