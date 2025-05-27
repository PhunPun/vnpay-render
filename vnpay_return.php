<?php
$responseCode = $_GET['vnp_ResponseCode'] ?? '';
$txnRef = $_GET['vnp_TxnRef'] ?? '';
$amount = $_GET['vnp_Amount'] ?? '';
$errorMessage = $_GET['vnp_Message'] ?? 'Không rõ lỗi';
$secureHash = $_GET['vnp_SecureHash'] ?? '';
$rawQuery = $_SERVER['REQUEST_URI'];

echo "<h2>Kết quả thanh toán:</h2>";

if ($responseCode === '00') {
    echo "<h3 style='color:green;'>✅ Thanh toán thành công!</h3>";
    echo "<p>Mã đơn hàng: $txnRef</p>";
    echo "<p>Số tiền: " . ($amount / 100) . " VND</p>";
} else {
    echo "<h3 style='color:red;'>❌ Thanh toán thất bại hoặc bị huỷ</h3>";
    echo "<p>Mã lỗi: $responseCode</p>";
    echo "<p>Thông báo từ VNPay: $errorMessage</p>";
}

// ✅ In ra toàn bộ URL để debug
echo "<hr>";
echo "<strong>Raw Query:</strong><br><code>$rawQuery</code>";
?>
