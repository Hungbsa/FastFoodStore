<?php
session_start();
$owner = $_SESSION['username'] ?? 'Bạn';
$group_name = $_POST['group_name'] ?? '';
$res_id = isset($_POST['res_id']) ? intval($_POST['res_id']) : (isset($_GET['res_id']) ? intval($_GET['res_id']) : 0);
$code = strtoupper(substr(md5(uniqid()), 0, 6));
if ($group_name && $res_id) {
    $_SESSION['group'] = [
        'owner' => $owner,
        'name' => $group_name,
        'code' => $code,
        'members' => [$owner],
        'res_id' => $res_id
    ];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
