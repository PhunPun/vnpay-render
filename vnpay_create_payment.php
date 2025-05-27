<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Thông tin cấu hình từ VNPAY
$vnp_TmnCode = "MQ230N7N"; // Mã website (Terminal ID)
$vnp_HashSecret = "BU33YQJVJDWVD8HT5HQUT6WZ8S886K66"; // Chuỗi bí mật
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "https://vnpay-render.onrender.com/vnpay_return.php";

// Dữ liệu từ Flutter gửi lên
$order_id = $_POST['order_id'] ?? time();
$amount = $_POST['amount'] ?? 100000;
$amount *= 100; // Nhân 100 vì VNPAY dùng đơn vị là x100

$expire = date('YmdHis', strtotime('+15 minutes')); // Hạn thanh toán

// Dữ liệu gửi đến VNPAY
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
    "vnp_ExpireDate" => $expire
);

// Sắp xếp mảng, tạo query & hash
ksort($inputData);
$hashdata = '';
$query = [];

foreach ($inputData as $key => $value) {
    $hashdata .= $key . "=" . $value . "&";
    $query[] = urlencode($key) . "=" . urlencode($value);
}

$hashdata = rtrim($hashdata, '&');
$vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
$query[] = 'vnp_SecureHash=' . $vnp_SecureHash;

$vnpUrl = $vnp_Url . '?' . implode('&', $query);

// Trả kết quả JSON về Flutter
header('Content-Type: application/json');
echo json_encode(['url' => $vnpUrl]);
