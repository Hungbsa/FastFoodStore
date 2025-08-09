<?php
require_once '../../connection/connect.php';
header('Content-Type: application/json');

$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Missing order_id']);
    exit;
}

$sql = "SELECT o.*, s.full_name AS shipper_name, s.phone_number AS shipper_phone,
               s.current_latitude, s.current_longitude
        FROM users_orders o
        LEFT JOIN shippers s ON o.shipper_id = s.shipper_id
        WHERE o.o_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'order' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
}
$stmt->close();