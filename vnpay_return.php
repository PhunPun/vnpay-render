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
$computedHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// 🧾 Lấy thông tin thanh toán
$responseCode = $_GET['vnp_ResponseCode'] ?? null;
$txnRef = $_GET['vnp_TxnRef'] ?? null;
$amount = $_GET['vnp_Amount'] ?? null;
$errorMessage = $_GET['vnp_Message'] ?? 'Không rõ lỗi';
$rawQuery = $_SERVER['REQUEST_URI'];

// 📤 Trả về JSON
header('Content-Type: application/json');
echo json_encode([
    'status' => $computedHash === $vnp_SecureHash ? 'valid' : 'invalid',
    'responseCode' => $responseCode,
    'txnRef' => $txnRef,
    'amount' => $amount,
    'errorMessage' => $errorMessage,
    'computedHash' => $computedHash,
    'receivedHash' => $vnp_SecureHash,
    'hashData' => $hashData,
    'rawQuery' => $rawQuery,
]);
?>
