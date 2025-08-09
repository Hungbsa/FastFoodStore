<?php
include("../connection/connect.php");
session_start();
if(empty($_SESSION["user_id"])) die(json_encode(["success"=>false,"message"=>"Chưa đăng nhập"]));
$uid = $_SESSION["user_id"];
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$label = trim($_POST['label'] ?? '');
$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
if($id<=0 || !$label || !$fullname || !$email || !$phone || !$address) die(json_encode(["success"=>false,"message"=>"Thiếu thông tin"]));
$sql = "UPDATE addresses SET label=?, fullname=?, email=?, phone=?, address=? WHERE id=? AND user_id=?";
$stmt = $db->prepare($sql);
$stmt->bind_param("ssssssi", $label, $fullname, $email, $phone, $address, $id, $uid);
if($stmt->execute()) {
    echo json_encode(["success"=>true]);
} else {
    echo json_encode(["success"=>false,"message"=>"Cập nhật thất bại"]);
}
