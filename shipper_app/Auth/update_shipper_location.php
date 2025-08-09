<?php
require_once '../../connection/connect.php';
header('Content-Type: application/json');

$shipper_id = $_POST['shipper_id'] ?? null;
$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;

if (!$shipper_id || !$latitude || !$longitude) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

// Kiểm tra shipper tồn tại
$stmt = $db->prepare("SELECT shipper_id FROM shippers WHERE shipper_id = ?");
$stmt->bind_param("s", $shipper_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Shipper not found']);
    exit;
}
$stmt->close();

// Cập nhật vị trí
$stmt = $db->prepare("UPDATE shippers SET current_latitude=?, current_longitude=?, last_location_update=NOW() WHERE shipper_id=?");
$stmt->bind_param("dds", $latitude, $longitude, $shipper_id);
$success = $stmt->execute();
$stmt->close();

echo json_encode([
    'success' => $success,
    'message' => $success ? 'Location updated' : 'Update failed'
]);