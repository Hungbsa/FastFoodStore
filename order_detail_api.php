<?php
header('Content-Type: application/json; charset=utf-8');
include_once "connection/connect.php";
session_start();

if (empty($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Chưa đăng nhập!"]);
    exit;
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id <= 0) {
    echo json_encode(["success" => false, "message" => "Thiếu order_id"]);
    exit;
}

// Lấy đơn hàng
$sql = "SELECT * FROM users_orders WHERE o_id='$order_id' AND u_id='" . $_SESSION['user_id'] . "' LIMIT 1";
$res = mysqli_query($db, $sql);
if (!$res || mysqli_num_rows($res) == 0) {
    echo json_encode(["success" => false, "message" => "Không tìm thấy đơn hàng!"]);
    exit;
}
$order = mysqli_fetch_assoc($res);

// Lấy thông tin
$user = 'user id: ' . $_SESSION['user_id'];

$restaurant = '';
if (!empty($order['r_id'])) {
    $rres = mysqli_query($db, "SELECT title FROM restaurant WHERE rs_id='" . $order['r_id'] . "' LIMIT 1");
    if ($rres && $r = mysqli_fetch_assoc($rres)) {
        $restaurant = $r['title'];
    }
}
if ($restaurant == '') $restaurant = 'FastFood';

// Lấy các món trong đơn (nếu có bảng order_items thì lấy, nếu không thì chỉ lấy 1 món)
$items = [];
$order_items_table = false;
$check = mysqli_query($db, "SHOW TABLES LIKE 'order_items'");
if ($check && mysqli_num_rows($check) > 0) {
    $order_items_table = true;
}
if ($order_items_table) {
    $q = mysqli_query($db, "SELECT * FROM order_items WHERE order_id='$order_id'");
    while ($item = mysqli_fetch_assoc($q)) {
        $items[] = [
            'title' => $item['title'],
            'quantity' => (int)$item['quantity'],
            'price' => (float)$item['price']
        ];
    }
}
if (!$items) {
    // fallback: chỉ có 1 món trong users_orders
    $items[] = [
        'title' => $order['title'],
        'quantity' => (int)$order['quantity'],
        'price' => (float)$order['price']
    ];
}

// Địa chỉ giao hàng: lấy từ users table
$address = '';
$user_id = $_SESSION['user_id'];
$userq = mysqli_query($db, "SELECT address FROM users WHERE u_id='$user_id' LIMIT 1");
if ($userq && $udata = mysqli_fetch_assoc($userq)) {
    $address = $udata['address'];
}
if (!$address) $address = isset($order['address']) ? $order['address'] : '';
// Thời gian đặt
$date = date('d/m/Y H:i', strtotime($order['date']));
// Trạng thái
$status = $order['status'];
if ($status == '' || $status == 'NULL') $status = 'Đang xử lý';
else if ($status == 'in process') $status = 'Đang giao';
else if ($status == 'closed') $status = 'Hoàn thành';
else if ($status == 'rejected') $status = 'Đã hủy';
// Tổng cộng
$total = 0;
foreach ($items as $it) {
    $total += $it['price'] * $it['quantity'];
}
// Giảm giá
$discount = isset($order['discount']) ? (float)$order['discount'] : 0;
// Phí giao hàng
$fee = isset($order['fee']) ? (float)$order['fee'] : 0;
// Tổng cuối cùng
$total_final = $total + $fee - $discount;
if ($total_final < 0) $total_final = 0;

// Trả về JSON
// Thông tin shipper
$shipper_id = isset($order['shipper_id']) ? $order['shipper_id'] : '';
$shipper_name = '';
$shipper_phone = '';
$current_latitude = '';
$current_longitude = '';
if($shipper_id) {
    $shipperq = mysqli_query($db, "SELECT full_name, phone_number, current_latitude, current_longitude FROM shippers WHERE shipper_id='$shipper_id' LIMIT 1");
    if($shipperq && $shipper = mysqli_fetch_assoc($shipperq)) {
        $shipper_name = $shipper['full_name'];
        $shipper_phone = $shipper['phone_number'];
        $current_latitude = $shipper['current_latitude'];
        $current_longitude = $shipper['current_longitude'];
    }
}

$data = [
    'success' => true,
    'restaurant' => $restaurant,
    'user' => $user,
    'items' => array_map(function($item) {
        $item['price'] = number_format($item['price'], 2, '.', ',') . ' VNĐ';
        return $item;
    }, $items),
    'date' => $date,
    'status' => $status,
    'subtotal' => number_format($total, 2, '.', ',') . ' VNĐ',
    'discount' => number_format($discount, 2, '.', ',') . ' VNĐ',
    'fee' => number_format($fee, 2, '.', ',') . ' VNĐ',
    'total' => number_format($total_final, 2, '.', ',') . ' VNĐ',
    'address' => $address,
    'shipper_id' => $shipper_id,
    'shipper_name' => $shipper_name,
    'shipper_phone' => $shipper_phone,
    'current_latitude' => $current_latitude,
    'current_longitude' => $current_longitude,
    'o_id' => $order_id
];
echo json_encode($data);
