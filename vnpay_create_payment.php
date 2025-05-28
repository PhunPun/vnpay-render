<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Cấu hình Merchant
$vnp_TmnCode = "MQ230N7N";
$vnp_HashSecret = "BU33YQJVJDWVD8HT5HQUT6WZ8S886K66";
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "https://vnpay-render.onrender.com/vnpay_return.php";

// Dữ liệu từ phía Flutter POST lên
$order_id = $_POST['order_id'] ?? time();
$amount = ($_POST['amount'] ?? 100000) * 100;

$ipRaw = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
$vnp_IpAddr = trim(explode(',', $ipRaw)[0]);

// Tạo dữ liệu cần gửi
$inputData = [
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
    // "vnp_BankCode" => "NCB", // <- Nếu muốn chỉ định sẵn bank thì bỏ comment
];

// Bước 1: Sắp xếp key theo a-z
ksort($inputData);

// Bước 2: Tạo chuỗi hash
$hashDataArr = [];
foreach ($inputData as $key => $value) {
    $hashDataArr[] = urlencode($key) . '=' . urlencode($value);
}
$hashData = implode('&', $hashDataArr);

// Bước 3: Tạo chữ ký SHA512
$vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Bước 4: Thêm chữ ký vào data
$inputData['vnp_SecureHashType'] = 'SHA512';
$inputData['vnp_SecureHash'] = $vnp_SecureHash;

// Bước 5: Tạo link URL thanh toán
$vnpUrl = $vnp_Url . '?' . http_build_query($inputData, '', '&', PHP_QUERY_RFC3986);

// Trả JSON về cho Flutter
header('Content-Type: application/json');
echo json_encode([
    'url' => $vnpUrl,
    'hashData' => $hashData,
    'secureHash' => $vnp_SecureHash
]);
