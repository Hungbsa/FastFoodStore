<?php
require_once '../connection/connect.php'; // Giả sử file này tạo biến $db là kết nối cơ sở dữ liệu
session_start();

if (!isset($_SESSION['shipper'])) {
    header('Location: login.php');
    exit();
}
$shipper = $_SESSION['shipper'];
$name = $shipper['full_name'] ?? '';
$phone = $shipper['phone_number'] ?? '';

// Sửa từ 'id' thành 'shipper_id' để khớp với tên cột trong bảng shippers
$shipper_id = $shipper['shipper_id'] ?? null; 
$orders_today = 0;
$income_today = 0;
$orders_week = 0;
$target_week = 50;
$rating = 4.8;
$recent_orders = [];
$show_toast = isset($_GET['login']) && $_GET['login'] == 'success';

if ($shipper_id) {
    // Get today's date
    $today = date('Y-m-d');
    $week_start = date('Y-m-d', strtotime('monday this week'));
    $week_end = date('Y-m-d', strtotime('sunday this week'));

    // Sửa $conn thành $db cho tất cả các truy vấn
    // Query today's orders
    $sql_today = "SELECT COUNT(*) as total, SUM(total) as income FROM users_orders WHERE shipper_id=? AND DATE(order_time)=?";
    $stmt_today = $db->prepare($sql_today); 
    $stmt_today->bind_param('is', $shipper_id, $today);
    $stmt_today->execute();
    $res_today = $stmt_today->get_result()->fetch_assoc();
    $orders_today = $res_today['total'] ?? 0;
    $income_today = $res_today['income'] ?? 0;
    $stmt_today->close();

    // Query weekly orders
    $sql_week = "SELECT COUNT(*) as total FROM users_orders WHERE shipper_id=? AND DATE(order_time) BETWEEN ? AND ?";
    $stmt_week = $db->prepare($sql_week);
    $stmt_week->bind_param('iss', $shipper_id, $week_start, $week_end);
    $stmt_week->execute();
    $res_week = $stmt_week->get_result()->fetch_assoc();
    $orders_week = $res_week['total'] ?? 0;
    $stmt_week->close();

    // Hiển thị đơn chưa nhận (shipper_id IS NULL, status = 'pending' hoặc 'in process')
    // và đơn đã nhận của shipper hiện tại (shipper_id = $shipper_id, status != 'closed', != 'rejected')
    $sql_orders = "SELECT o.*, u.f_name, u.l_name, IFNULL(o.address, a.address) AS address_default FROM users_orders o LEFT JOIN users u ON o.u_id=u.u_id LEFT JOIN addresses a ON o.u_id=a.user_id AND a.is_default=1 WHERE (o.shipper_id IS NULL AND (o.status = 'pending' OR o.status = 'in process')) OR (o.shipper_id=? AND o.status != 'closed' AND o.status != 'rejected') ORDER BY o.order_time DESC";
    $stmt_orders = $db->prepare($sql_orders);
    $stmt_orders->bind_param('i', $shipper_id);
    $stmt_orders->execute();
    $result_orders = $stmt_orders->get_result();
    while ($row = $result_orders->fetch_assoc()) {
        $color = 'primary';
        $icon = 'fa-cube';
        if ($row['status'] == 'Đã giao' || $row['status'] == 'closed' || $row['status'] == 'delivered') { 
            $color = 'success';
            $icon = 'fa-box';
        } elseif ($row['status'] == 'Đang giao' || $row['status'] == 'in process') {
            $color = 'warning';
            $icon = 'fa-truck';
        } elseif ($row['status'] == 'Chờ giao' || $row['status'] == 'pending') {
            $color = 'primary';
            $icon = 'fa-cube';
        } elseif ($row['status'] == 'rejected') {
            $color = 'danger';
            $icon = 'fa-ban';
        }
        $recent_orders[] = [
            "o_id" => $row['o_id'],
            "id" => "#SP" . str_pad($row['o_id'], 3, '0', STR_PAD_LEFT),
            "name" => $row['f_name'] . ' ' . $row['l_name'],
            "address" => $row['address'] ? $row['address'] : $row['address_default'],
            "status" => $row['status'],
            "color" => $color,
            "icon" => $icon,
            "title" => $row['title'],
            "quantity" => $row['quantity'],
            "price" => $row['price'],
            "total" => $row['total'],
            "order_time" => $row['order_time'],
            "coupon_code" => $row['coupon_code'],
            "discount" => $row['discount'],
            "date" => $row['date'],
            "shipper_id" => $row['shipper_id'],
        ];
    }
    $stmt_orders->close();
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../images/img/shipper.png">
    <title>ShipperPro Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../shipper_app/assess/dashboard.css">
</head>
<body>
<div class="sidebar">
    <div class="sidebar-header">
        <div class="circle-logo"><i class="fa-solid fa-truck"></i></div>
        <h2>ShipperPro</h2>
        <small>Dashboard</small>
    </div>
    <div class="sidebar-user">
        <img src="../images/shipper.png" class="avatar" alt="Avatar">
        <div class="user-info">
            <div class="name"><?= htmlspecialchars($name) ?></div>
            <div class="phone"><?= htmlspecialchars($phone) ?></div>
        </div>
    </div>
    <div class="sidebar-menu">
        <a href="#" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="../shipper_app/ship_orders/oders_wait.php"><i class="fa-solid fa-list"></i> Quản lý đơn hàng</a>
        <a href="../shipper_app/item_list/report.php"><i class="fa-solid fa-chart-bar"></i> Báo cáo</a>
        <a href="../shipper_app/item_list/support.php"><i class="fa-solid fa-headset"></i> Hỗ trợ</a>
    </div>
    <div class="sidebar-logout">
        <a href="logout.php" style="color:#ef4444;"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
    </div>
</div>
<div class="main-content">
    <?php if ($show_toast): ?>
    <div class="top-toast">
        <div class="toast-box">
            <i class="fa-solid fa-circle-check"></i> Đăng nhập thành công<br>Chào mừng <?= htmlspecialchars($name) ?>!
        </div>
    </div>
    <script>
        setTimeout(function(){
            document.querySelector('.top-toast').style.display = 'none';
        }, 3500);
    </script>
    <?php endif; ?>
    <div class="dashboard-header">Chào mừng trở lại!</div>
    <div class="dashboard-sub">
        Hôm nay bạn có <b><?= $orders_today ?></b> đơn hàng cần giao
    </div>
    <div class="dashboard-cards">
        <div class="dashboard-card">
            <div class="card-title">Hôm nay</div>
            <div class="card-value"><?= $orders_today ?></div>
            <div class="card-icon"><i class="fa-solid fa-box"></i></div>
            <div class="card-extra">Đơn hàng</div>
        </div>
        <div class="dashboard-card">
            <div class="card-title">Thu nhập</div>
            <div class="card-value"><?= number_format($income_today) ?>đ</div>
            <div class="card-icon"><i class="fa-solid fa-coins"></i></div>
            <div class="card-extra">Hôm nay</div>
        </div>
        <div class="dashboard-card">
            <div class="card-title">Tuần này</div>
            <div class="card-value"><?= $orders_week ?></div>
            <div class="card-icon"><i class="fa-solid fa-calendar"></i></div>
            <div class="card-extra">Mục tiêu: <?= $target_week ?></div>
        </div>
        <div class="dashboard-card rating">
            <div class="card-title">Đánh giá</div>
            <div class="card-value"><?= $rating ?></div>
            <div class="card-icon"><i class="fa-solid fa-star"></i></div>
            <div class="card-extra"><span style="color:#f59e42;">★★★★★</span></div>
        </div>
    </div>
    <div class="quick-actions">
        <button class="quick-btn green" onclick="window.location.href='ship_orders/oders_wait.php'"><i class="fa-solid fa-plus"></i> Nhận đơn mới<br><span style="font-weight:400; font-size:0.98rem;"></span></button>
        <button class="quick-btn orange"><i class="fa-solid fa-truck"></i> Đang giao<br><span style="font-weight:400; font-size:0.98rem;"></span></button>
        <button class="quick-btn blue"><i class="fa-solid fa-exclamation-triangle"></i> Báo cáo sự cố<br><span style="font-weight:400; font-size:0.98rem;">Nhanh chóng</span></button>
    </div>
    <div class="orders-section">
        <div class="orders-header">Đơn hàng gần đây <a href="#" style="float:right; font-size:0.98rem; color:#16a34a; font-weight:500;">Xem tất cả</a></div>
        <div class="orders-list">
            <?php if (count($recent_orders) === 0): ?>
                <div style="padding:1rem; color:#888; text-align:center;">Không có đơn hàng nào.</div>
            <?php else: ?>
                <?php foreach ($recent_orders as $order): ?>
                <div class="order-item">
                    <div class="order-icon <?= $order['color'] ?>"><i class="fa-solid <?= $order['icon'] ?>"></i></div>
                    <div class="order-info">
                        <div class="order-id"><?= $order['id'] ?></div>
                        <div class="order-name"><?= $order['name'] ?> - <?= $order['address'] ?></div>
                    </div>
                    <div class="order-status <?= $order['color'] ?>"><?= $order['status'] ?></div>
                    <button class="btn btn-sm btn-outline-success" style="margin-left:1rem;" onclick="showOrderDetail(<?= htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8') ?>)">Chi tiết đơn</button>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div id="orderDetailModal" class="modal" tabindex="-1" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
            <div class="modal-dialog" style="max-width:400px; margin:5% auto; background:#fff; border-radius:8px; box-shadow:0 2px 16px #0002; padding:2rem; position:relative;">
                <div id="orderDetailContent"></div>
                <div style="margin-top:2rem; text-align:center;">
                    <button class="btn btn-success" onclick="acceptOrder()">Nhận đơn</button>
                    <button class="btn btn-danger" onclick="rejectOrder()">Từ chối</button>
                    <button class="btn btn-secondary" onclick="closeOrderModal()">Đóng</button>
                </div>
            </div>
        </div>
        <script>
        let currentOrder = null;
        function showOrderDetail(order) {
            currentOrder = order;
            let html = `<h4>Chi tiết đơn hàng</h4>`;
            html += `<div><b>Mã đơn:</b> ${order.id}</div>`;
            html += `<div><b>Khách hàng:</b> ${order.name}</div>`;
            html += `<div><b>Địa chỉ:</b> ${order.address}</div>`;
            html += `<div><b>Món ăn:</b> ${order.title}</div>`;
            html += `<div><b>Số lượng:</b> ${order.quantity}</div>`;
            html += `<div><b>Giá:</b> ${order.price}đ</div>`;
            html += `<div><b>Tổng tiền:</b> ${order.total}đ</div>`;
            html += `<div><b>Mã giảm giá:</b> ${order.coupon_code || '-'}</div>`;
            html += `<div><b>Giảm giá:</b> ${order.discount}đ</div>`;
            html += `<div><b>Thời gian đặt:</b> ${order.order_time}</div>`;
            html += `<div><b>Trạng thái:</b> ${order.status}</div>`;
            document.getElementById('orderDetailContent').innerHTML = html;
            document.getElementById('orderDetailModal').style.display = 'block';
        }
        function closeOrderModal() {
            document.getElementById('orderDetailModal').style.display = 'none';
        }
        function acceptOrder() {
            alert('Nhận đơn: ' + currentOrder.id);
            closeOrderModal();
        }
        function rejectOrder() {
            alert('Từ chối đơn: ' + currentOrder.id);
            closeOrderModal();
        }
        </script>
    </div>
</div>
</body>
</html>