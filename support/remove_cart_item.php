<?php
session_start();
header('Content-Type: application/json');
if (!isset($_POST['item_id'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã món']);
    exit;
}
$item_id = $_POST['item_id'];
if (!isset($_SESSION['cart_item'])) {
    echo json_encode(['success' => false, 'message' => 'Không có giỏ hàng']);
    exit;
}
$found = false;
foreach ($_SESSION['cart_item'] as $k => $item) {
    if ($item['d_id'] == $item_id) {
        unset($_SESSION['cart_item'][$k]);
        $found = true;
        break;
    }
}
// Đảm bảo chỉ số lại mảng
$_SESSION['cart_item'] = array_values($_SESSION['cart_item']);
if ($found) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy món']);
}
