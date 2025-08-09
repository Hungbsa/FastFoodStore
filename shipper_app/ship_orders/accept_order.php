<?php
require_once '../../connection/connect.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['shipper'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
    exit();
}
$shipper = $_SESSION['shipper'];
$shipper_id = $shipper['shipper_id'] ?? null;

$o_id = isset($_POST['o_id']) ? intval($_POST['o_id']) : 0;
if (!$o_id || !$shipper_id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin đơn hàng']);
    exit();
}
// Cập nhật trạng thái đơn hàng
$stmt = $db->prepare("UPDATE users_orders SET status='in process', shipper_id=? WHERE o_id=? AND (shipper_id IS NULL OR shipper_id=0)");
$stmt->bind_param('ii', $shipper_id, $o_id);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Nhận đơn thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Đơn hàng đã được nhận hoặc không tồn tại']);
}
$stmt->close();
