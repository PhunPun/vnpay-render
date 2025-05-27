<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

$vnp_TmnCode = "MQ230N7N";
$vnp_HashSecret = "BU33YQJVJDWVD8HT5HQUT6WZ8S886K66";
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "https://vnpay-render.onrender.com/vnpay_return.php"; // ✅ sửa đúng domain

$order_id = $_POST['order_id'] ?? time();
$amount = $_POST['amount'] ?? 100000;
$amount *= 100;

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_Command" => "pay",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $amount,
    "vnp_CurrCode" => "VND",
    "vnp_TxnRef" => $order_id,
    "vnp_OrderInfo" => "Thanh toan don hang $order_id",
    "vnp_OrderType" => "billpayment",
    "vnp_Locale" => "vn",
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_SecureHashType" => "SHA512"
);

ksort($inputData);

// ✅ build query string và hashdata tách biệt đúng chuẩn
$hashdata = '';
$query = [];
foreach ($inputData as $key => $value) {
    $hashdata .= $key . "=" . $value . '&';
    $query[] = urlencode($key) . "=" . urlencode($value);
}
$hashdata = rtrim($hashdata, '&');

$vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
$query[] = 'vnp_SecureHash=' . $vnp_SecureHash;

$vnpUrl = $vnp_Url . '?' . implode('&', $query);

// ✅ trả JSON cho Flutter
header('Content-Type: application/json');
echo json_encode(['url' => $vnpUrl]);
