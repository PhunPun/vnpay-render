<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

$vnp_TmnCode = "MQ230N7N";
$vnp_HashSecret = "BU33YQJVJDWVD8HT5HQUT6WZ8S886K66";
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "https://vnpay-render.onrender.com/vnpay_return.php";

$order_id = $_POST['order_id'] ?? time();
$amount = ($_POST['amount'] ?? 100000) * 100;

$ipRaw = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
$vnp_IpAddr = trim(explode(',', $ipRaw)[0]);

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
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_ExpireDate" => date('YmdHis', strtotime('+15 minutes')),
);

ksort($inputData);

// Sửa đoạn hashdata này
$hashdata = '';
foreach ($inputData as $key => $value) {
    $hashdata .= $key . '=' . $value . '&';
}
$hashdata = rtrim($hashdata, '&');

$vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

$inputData['vnp_SecureHash'] = $vnp_SecureHash;

// OK dùng PHP_QUERY_RFC3986 cho URL thực tế
$vnpUrl = $vnp_Url . '?' . http_build_query($inputData, '', '&', PHP_QUERY_RFC3986);

header('Content-Type: application/json');
echo json_encode(['url' => $vnpUrl]);
