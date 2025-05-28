<?php
error_reporting(E_ALL);
date_default_timezone_set('Asia/Ho_Chi_Minh');

// 🔐 Secret dùng để xác minh chữ ký
$vnp_HashSecret = "BU33YQJVJDWVD8HT5HQUT6WZ8S886K66";

// 🔄 Lấy dữ liệu từ URL
$vnpData = $_GET;
$vnp_SecureHash = $vnpData['vnp_SecureHash'] ?? '';
unset($vnpData['vnp_SecureHash']);
unset($vnpData['vnp_SecureHashType']);

// ✅ Tạo lại chữ ký từ dữ liệu
ksort($vnpData);
$hashData = '';
foreach ($vnpData as $key => $value) {
    $hashData .= $key . '=' . $value . '&';
}
$hashData = rtrim($hashData, '&');
file_put_contents('debug_return.txt', $hashData);
$computedHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// 🧾 Lấy các thông tin cần hiển thị
$responseCode = $_GET['vnp_ResponseCode'] ?? null;
$txnRef = $_GET['vnp_TxnRef'] ?? null;
$amount = $_GET['vnp_Amount'] ?? null;
$errorMessage = $_GET['vnp_Message'] ?? 'Không rõ lỗi';

$rawQuery = $_SERVER['REQUEST_URI'];

// 🖥️ Hiển thị kết quả
echo "<h2>Kết quả thanh toán:</h2>";

if ($computedHash !== $vnp_SecureHash) {
    echo "<h3 style='color:red;'>❌ Chữ ký không hợp lệ - dữ liệu có thể đã bị chỉnh sửa!</h3>";
    echo "<p>Vui lòng không tin tưởng kết quả này.</p>";
} else {
    if ($responseCode === '00') {
        echo "<h3 style='color:green;'>✅ Thanh toán thành công!</h3>";
        echo "<p>Mã đơn hàng: <strong>$txnRef</strong></p>";
        echo "<p>Số tiền: <strong>" . number_format($amount / 100, 0, ',', '.') . " VND</strong></p>";
    } else {
        echo "<h3 style='color:red;'>❌ Thanh toán thất bại hoặc bị huỷ</h3>";
        echo "<p>Mã lỗi: <strong>$responseCode</strong></p>";
        echo "<p>Thông báo từ VNPay: $errorMessage</p>";
    }
}

// 🔍 Debug thông tin đầy đủ
echo "<hr>";
echo "<strong>Raw Query:</strong><br><code>$rawQuery</code>";
echo "<hr><strong>Computed Hash:</strong><br><code>$computedHash</code>";
echo "<br><strong>Received Hash:</strong><br><code>$vnp_SecureHash</code>";
?>
