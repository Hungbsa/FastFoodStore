<?php
include("../connection/connect.php");
header('Content-Type: application/json');

$current_res_id = intval($_GET['current_res_id']);
$limit = intval($_GET['limit'] ?? 10);

// Lấy thông tin nhà hàng hiện tại để tìm các nhà hàng tương tự
$current_restaurant = $db->query("SELECT * FROM restaurant WHERE rs_id = $current_res_id")->fetch_assoc();

if ($current_restaurant) {
    // Trong thực tế, bạn nên tìm kiếm theo category hoặc đặc điểm nào đó
    // Ở đây tôi giả lập tìm các nhà hàng khác cùng khu vực
    $stmt = $db->prepare("SELECT r.*, 
                         (SELECT AVG(rating) FROM rating WHERE rs_id = r.rs_id) as rating,
                         (SELECT COUNT(*) FROM rating WHERE rs_id = r.rs_id) as reviews
                         FROM restaurant r
                         WHERE rs_id != ? 
                         ORDER BY RAND() LIMIT ?");
    $stmt->bind_param("ii", $current_res_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $restaurants = [];
    while ($row = $result->fetch_assoc()) {
        $restaurants[] = $row;
    }
    
    echo json_encode($restaurants);
} else {
    echo json_encode([]);
}
?>