<?php
include("../connection/connect.php");
header('Content-Type: application/json');

$term = $_GET['term'] ?? '';
$res_id = intval($_GET['res_id']);

if (strlen($term) > 0 && $res_id > 0) {
    $stmt = $db->prepare("SELECT d_id as id, title, slogan, price FROM dishes 
                         WHERE rs_id = ? AND title LIKE ? LIMIT 5");
    $searchTerm = "%$term%";
    $stmt->bind_param("is", $res_id, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $dishes = [];
    while ($row = $result->fetch_assoc()) {
        $dishes[] = $row;
    }
    
    echo json_encode($dishes);
} else {
    echo json_encode([]);
}
?>