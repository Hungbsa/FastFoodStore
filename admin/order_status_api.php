<?php
include('../connection/connect.php');
session_start();
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $o_id = intval($_POST['form_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if ($o_id > 0 && $status) {
        $sql = "UPDATE users_orders SET status=? WHERE o_id=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('si', $status, $o_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật CSDL!']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu!']);
    }
    exit;
}
echo json_encode(['success' => false, 'message' => 'Sai phương thức!']);