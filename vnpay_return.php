<?php
error_reporting(E_ALL);
date_default_timezone_set('Asia/Ho_Chi_Minh');

// ✅ Lấy tham số từ URL
$responseCode = $_GET['vnp_ResponseCode'] ?? null;
$txnRef = $_GET['vnp_TxnRef'] ?? null;
$amount = $_GET['vnp_Amount'] ?? null;
$errorMessage = $_GET['vnp_Message'] ?? 'Không rõ lỗi';

$rawQuery = $_SERVER['REQUEST_URI'];

// ✅ Hiển thị kết quả
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

// ✅ Debug URL
echo "<hr>";
echo "<strong>Raw Query:</strong><br><code>$rawQuery</code>";
?>
