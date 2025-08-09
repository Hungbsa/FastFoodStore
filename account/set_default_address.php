<?php
include("../connection/connect.php");
session_start();
if(empty($_SESSION["user_id"])) die(json_encode(["success"=>false,"message"=>"Chưa đăng nhập"]));
$uid = $_SESSION["user_id"];
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if($id<=0) die(json_encode(["success"=>false,"message"=>"ID không hợp lệ"]));
// Đặt tất cả is_default=0
mysqli_query($db, "UPDATE addresses SET is_default=0 WHERE user_id='$uid'");
// Đặt địa chỉ này là mặc định
$res = mysqli_query($db, "UPDATE addresses SET is_default=1 WHERE id='$id' AND user_id='$uid'");
if($res) {
    echo json_encode(["success"=>true]);
} else {
    echo json_encode(["success"=>false,"message"=>"Cập nhật thất bại"]);
}
