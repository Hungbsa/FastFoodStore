
<?php
session_start();
require_once '../connection/connect.php';

$response = ['success' => false, 'message' => ''];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $label = mysqli_real_escape_string($db, $_POST['label']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $user_id = $_SESSION['user_id']; // Hoặc cách lấy user_id khác
    
    $query = "INSERT INTO addresses (user_id, label, address, phone) VALUES ('$user_id', '$label', '$address', '$phone')";
    
    if(mysqli_query($db, $query)) {
        $response['success'] = true;
        $response['message'] = 'Thêm địa chỉ thành công';
    } else {
        $response['message'] = 'Lỗi database: ' . mysqli_error($db);
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>