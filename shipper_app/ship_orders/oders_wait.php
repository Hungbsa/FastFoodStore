<?php
require_once '../../connection/connect.php';
session_start();

if (!isset($_SESSION['shipper'])) {
    header('Location: ../login.php');
    exit();
}
$shipper = $_SESSION['shipper'];
$name = $shipper['full_name'] ?? '';
$phone = $shipper['phone_number'] ?? '';
$shipper_id = $shipper['shipper_id'] ?? null;
$orders_wait = [];
// Lấy đơn hàng đang giao (shipper_id là shipper hiện tại, status = 'in process' hoặc 'Đang giao')
$orders_shipping = [];
$sql_shipping = "SELECT o.*, u.f_name, u.l_name, IFNULL(o.address, a.address) AS address_default FROM users_orders o LEFT JOIN users u ON o.u_id=u.u_id LEFT JOIN addresses a ON o.u_id=a.user_id AND a.is_default=1 WHERE o.shipper_id = '" . $db->real_escape_string($shipper_id) . "' AND (o.status = 'in process' OR o.status = 'Đang giao') ORDER BY o.order_time DESC";
$result_shipping = $db->query($sql_shipping);
while ($row = $result_shipping->fetch_assoc()) {
    $color = 'warning';
    $icon = 'fa-truck';
    $orders_shipping[] = [
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
    ];
}

// Lấy đơn hàng chờ nhận (shipper_id IS NULL, status = 'pending' hoặc 'in process')
$sql_orders = "SELECT o.*, u.f_name, u.l_name, IFNULL(o.address, a.address) AS address_default FROM users_orders o LEFT JOIN users u ON o.u_id=u.u_id LEFT JOIN addresses a ON o.u_id=a.user_id AND a.is_default=1 WHERE o.shipper_id IS NULL AND (o.status = 'pending' OR o.status = 'in process') ORDER BY o.order_time DESC";
$result_orders = $db->query($sql_orders);
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
    $orders_wait[] = [
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
    ];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../../images/img/shipper.png">
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assess/dashboard.css">
    <style>
        .bg-primary-light { background: #f3f6fd; }
        .bg-success-light { background: #eafbe7; }
        .bg-warning-light { background: #fffbe6; }
        .bg-danger-light { background: #fdeaea; }
        .order-icon.primary { color: #2563eb; }
        .order-icon.success { color: #16a34a; }
        .order-icon.warning { color: #f59e42; }
        .order-icon.danger { color: #ef4444; }
        .order-status.primary { color: #2563eb; }
        .order-status.success { color: #16a34a; }
        .order-status.warning { color: #f59e42; }
        .order-status.danger { color: #ef4444; }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-header">
        <div class="circle-logo"><i class="fa-solid fa-truck"></i></div>
        <h2>ShipperPro</h2>
        <small>Dashboard</small>
    </div>
    <div class="sidebar-user">
        <img src="../../images/shipper.png" class="avatar" alt="Avatar">
        <div class="user-info">
            <div class="name"><?= htmlspecialchars($name) ?></div>
            <div class="phone"><?= htmlspecialchars($phone) ?></div>
        </div>
    </div>
    <div class="sidebar-menu">
        <a href="../dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="#" class="active"><i class="fa-solid fa-list"></i> Quản lý đơn hàng</a>
        <a href="../item_list/report.php"><i class="fa-solid fa-chart-bar"></i> Báo cáo</a>
        <a href="../item_list/support.php"><i class="fa-solid fa-headset"></i> Hỗ trợ</a>
    </div>
    <div class="sidebar-logout">
        <a href="../logout.php" style="color:#ef4444;"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
    </div>
</div>
<div class="main-content">
    <div class="orders-header">Quản lý đơn hàng</div>
    <div class="tab-bar">
        <button class="tab-btn active" id="tabWait" onclick="showTab('wait')">Chờ nhận <span class="tab-badge"><?= count($orders_wait) ?></span></button>
        <button class="tab-btn" id="tabShipping" onclick="showTab('shipping')">Đang giao <span class="tab-badge"><?= count($orders_shipping) ?></span></button>
        <button class="tab-btn" disabled>Hoàn thành <span class="tab-badge">0</span></button>
    </div>
    <div class="orders-list">
        <div id="ordersWait">
        <?php if (count($orders_wait) === 0): ?>
            <div style="padding:2rem; color:#888; text-align:center; font-size:1.2rem;">Không có đơn hàng nào.</div>
        <?php else: ?>
            <?php foreach ($orders_wait as $order): ?>
            <div class="order-item bg-<?= $order['color'] ?>-light" style="display:flex;align-items:center;box-shadow:0 2px 8px #0001; margin-bottom:1.2rem; border-radius:8px;">
                <div class="order-icon <?= $order['color'] ?>" style="font-size:2rem; margin-right:1.2rem;"><i class="fa-solid <?= $order['icon'] ?>"></i></div>
                <div class="order-info" style="flex:1;">
                    <div class="order-id" style="font-weight:700; font-size:1.1rem; color:#222;"><?= $order['id'] ?></div>
                    <div class="order-name" style="color:#333; font-size:1rem; margin-top:0.2rem;"><?= $order['name'] ?> - <?= $order['address'] ?></div>
                </div>
                <div class="order-status <?= $order['color'] ?>" style="font-weight:500; margin-right:1.2rem;"><?= $order['status'] ?></div>
                <button class="btn-detail" style="background:#16a34a; color:#fff; border:none; padding:0.5rem 1.2rem; border-radius:6px; font-size:1rem; font-weight:500;" onclick="showOrderDetail(<?= htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8') ?>)">Chi tiết đơn</button>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
        <div id="ordersShipping" style="display:none;">
        <?php if (count($orders_shipping) === 0): ?>
            <div style="padding:2rem; color:#888; text-align:center; font-size:1.2rem;">Không có đơn đang giao.</div>
        <?php else: ?>
            <?php foreach ($orders_shipping as $order): ?>
            <div class="order-item bg-<?= $order['color'] ?>-light" style="display:flex;align-items:center;box-shadow:0 2px 8px #0001; margin-bottom:1.2rem; border-radius:8px;">
                <div class="order-icon <?= $order['color'] ?>" style="font-size:2rem; margin-right:1.2rem;"><i class="fa-solid <?= $order['icon'] ?>"></i></div>
                <div class="order-info" style="flex:1;">
                    <div class="order-id" style="font-weight:700; font-size:1.1rem; color:#222;"><?= $order['id'] ?></div>
                    <div class="order-name" style="color:#333; font-size:1rem; margin-top:0.2rem;"><?= $order['name'] ?> - <?= $order['address'] ?></div>
                </div>
                <div class="order-status <?= $order['color'] ?>" style="font-weight:500; margin-right:1.2rem;"><?= $order['status'] ?></div>
                <button class="btn-detail" style="background:#f59e42; color:#fff; border:none; padding:0.5rem 1.2rem; border-radius:6px; font-size:1rem; font-weight:500;" onclick="showOrderDetail(<?= htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8') ?>)">Chi tiết đơn</button>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
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
</div>
<script>
let currentOrder = null;
let currentTab = 'wait';
function showTab(tab) {
    currentTab = tab;
    document.getElementById('ordersWait').style.display = tab === 'wait' ? '' : 'none';
    document.getElementById('ordersShipping').style.display = tab === 'shipping' ? '' : 'none';
    document.getElementById('tabWait').classList.toggle('active', tab === 'wait');
    document.getElementById('tabShipping').classList.toggle('active', tab === 'shipping');
}
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
    if (!currentOrder || !currentOrder.o_id) return;
    // Gửi AJAX nhận đơn
    fetch('accept_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'o_id=' + encodeURIComponent(currentOrder.o_id)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            showTab('shipping');
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(() => {
        alert('Có lỗi xảy ra, vui lòng thử lại!');
    });
    closeOrderModal();
}
function rejectOrder() {
    alert('Từ chối đơn: ' + currentOrder.id);
    closeOrderModal();
}
</script>
</body>
</html>
