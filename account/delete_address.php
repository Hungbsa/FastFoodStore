<?php
include("../connection/connect.php");
session_start();
if(empty($_SESSION["user_id"])) die(json_encode(["success"=>false,"message"=>"Chưa đăng nhập"]));
$uid = $_SESSION["user_id"];
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if($id<=0) die(json_encode(["success"=>false,"message"=>"ID không hợp lệ"]));
$res = mysqli_query($db, "DELETE FROM addresses WHERE id='$id' AND user_id='$uid'");
if($res) {
    echo json_encode(["success"=>true]);
} else {
    echo json_encode(["success"=>false,"message"=>"Xoá thất bại"]);
}
