<?php
include("../connection/connect.php");
session_start();
if(empty($_SESSION["user_id"])) die(json_encode(["success"=>false,"message"=>"Chưa đăng nhập"]));
$uid = $_SESSION["user_id"];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id<=0) die(json_encode(["success"=>false,"message"=>"ID không hợp lệ"]));
$res = mysqli_query($db, "SELECT * FROM addresses WHERE id='$id' AND user_id='$uid' LIMIT 1");
if($row = mysqli_fetch_assoc($res)) {
    echo json_encode(["success"=>true,"address"=>$row]);
} else {
    echo json_encode(["success"=>false,"message"=>"Không tìm thấy địa chỉ"]);
}
