<?php
include_once("connection/connect.php");
session_start();

// Xử lý AJAX POST cho huỷ đơn và đặt lại đơn
if (!empty($_POST['action'])) {
	header('Content-Type: application/json');
	$action = $_POST['action'];
	if ($action === 'cancel_order') {
		$order_id = intval($_POST['order_id']);
		$user_id = $_SESSION['user_id'];
		// Chỉ cho phép huỷ đơn của chính mình và chưa bị huỷ/hoàn thành
		$q = mysqli_query($db, "SELECT status FROM users_orders WHERE o_id='$order_id' AND u_id='$user_id' LIMIT 1");
		$row = mysqli_fetch_assoc($q);
		if ($row && $row['status'] != 'rejected' && $row['status'] != 'closed') {
			$update = mysqli_query($db, "UPDATE users_orders SET status='rejected' WHERE o_id='$order_id' AND u_id='$user_id'");
			if ($update) {
				echo json_encode(['success' => true]);
			} else {
				echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái đơn hàng!']);
			}
		} else {
			echo json_encode(['success' => false, 'message' => 'Đơn hàng không hợp lệ hoặc đã bị huỷ/hoàn thành!']);
		}
		exit;
	}
	if ($action === 'reorder') {
		$order_id = intval($_POST['order_id']);
		$user_id = $_SESSION['user_id'];
		// Lấy thông tin đơn cũ
		$q = mysqli_query($db, "SELECT * FROM users_orders WHERE o_id='$order_id' AND u_id='$user_id' LIMIT 1");
		$row = mysqli_fetch_assoc($q);
		if ($row && $row['status'] == 'rejected') {
			// Tạo đơn mới dựa trên đơn cũ
			$fields = "u_id, d_id, title, quantity, price, rs_id, address, date, status";
			$values = "'{$row['u_id']}', '{$row['d_id']}', '".mysqli_real_escape_string($db, $row['title'])."', '{$row['quantity']}', '{$row['price']}', '{$row['rs_id']}', '".mysqli_real_escape_string($db, $row['address'])."', NOW(), ''";
			$insert = mysqli_query($db, "INSERT INTO users_orders ($fields) VALUES ($values)");
			if ($insert) {
				echo json_encode(['success' => true]);
			} else {
				echo json_encode(['success' => false, 'message' => 'Không thể tạo lại đơn hàng!']);
			}
		} else {
			echo json_encode(['success' => false, 'message' => 'Chỉ có thể đặt lại đơn đã huỷ!']);
		}
		exit;
	}
	// Nếu không phải 2 action trên thì bỏ qua
	echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ!']);
	exit;
}

// Giữ lại code cũ cho các chức năng khác (giỏ hàng...)
if(!empty($_GET["action"])) 
{
$productId = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
$quantity = isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '';
switch($_GET["action"])
 {
	case "add":
		if(!empty($quantity)) {
			$stmt = $db->prepare("SELECT * FROM dishes where d_id= ?");
			$stmt->bind_param('i',$productId);
			$stmt->execute();
			$productDetails = $stmt->get_result()->fetch_object();
			$itemArray = array($productDetails->d_id=>array(
				'title'=>$productDetails->title,
				'd_id'=>$productDetails->d_id,
				'rs_id'=>$productDetails->rs_id, // Thêm rs_id vào giỏ hàng
				'quantity'=>$quantity,
				'price'=>$productDetails->price
			));
			if(!empty($_SESSION["cart_item"])) 
			{
				if(in_array($productDetails->d_id,array_keys($_SESSION["cart_item"]))) 
				{
					foreach($_SESSION["cart_item"] as $k => $v) 
					{
						if($productDetails->d_id == $k) 
						{
							if(empty($_SESSION["cart_item"][$k]["quantity"])) 
							{
								$_SESSION["cart_item"][$k]["quantity"] = 0;
							}
							$_SESSION["cart_item"][$k]["quantity"] += $quantity;
						}
					}
				}
				else 
				{
					$_SESSION["cart_item"] = $_SESSION["cart_item"] + $itemArray;
				}
			} 
			else 
			{
				$_SESSION["cart_item"] = $itemArray;
			}
		}
		break;
	case "remove":
		if(!empty($_SESSION["cart_item"]))
		{
			foreach($_SESSION["cart_item"] as $k => $v) 
			{
				if($productId == $v['d_id'])
					unset($_SESSION["cart_item"][$k]);
			}
		}
		break;
	case "empty":
		unset($_SESSION["cart_item"]);
		break;
	case "check":
		header("location:checkout.php");
		break;
	}
}