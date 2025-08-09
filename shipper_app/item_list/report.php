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
// Tổng thu nhập có thể rút
$sql_income = "SELECT SUM(amount) as total FROM withdraw_requests WHERE shipper_id='$shipper_id' AND status='approved'";
$result_income = $db->query($sql_income);
if ($result_income) {
    $row_income = $result_income->fetch_assoc();
    $total_income = $row_income['total'] ?? 0;
} else {
    $total_income = 0;
}
// Thống kê đơn hàng, thu nhập, quãng đường, đánh giá TB
$sql_stats = "SELECT COUNT(*) as orders, SUM(total) as income, SUM(distance) as distance, AVG(rating) as avg_rating FROM users_orders WHERE shipper_id='$shipper_id'";
// Kiểm tra kết quả truy vấn trước khi gọi fetch_assoc
$result_stats = $db->query($sql_stats);
if ($result_stats) {
    $row_stats = $result_stats->fetch_assoc();
    $orders = $row_stats['orders'] ?? 0;
    $income = $row_stats['income'] ?? 0;
    $distance = $row_stats['distance'] ?? 0;
    $avg_rating = round($row_stats['avg_rating'] ?? 0, 1);
} else {
    $orders = 0;
    $income = 0;
    $distance = 0;
    $avg_rating = 0;
}
// Xử lý yêu cầu rút tiền
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['withdraw'])) {
    $amount = floatval($_POST['amount']);
    if ($amount > 0 && $amount <= $income) {
        $db->query("INSERT INTO withdraw_requests (shipper_id, amount, status) VALUES ('$shipper_id', '$amount', 'pending')");
        $msg = "Yêu cầu rút tiền đã được gửi!";
    } else {
        $msg = "Số tiền không hợp lệ.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../../images/img/shipper.png">
    <title>Báo cáo & Thống kê</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <style>
        body { background: #f7f7f7; }
        .sidebar { width: 260px; background: #fff; height: 100vh; position: fixed; left: 0; top: 0; box-shadow: 2px 0 8px #0001; }
        .sidebar-header { padding: 2rem 1rem 1rem 1rem; text-align: center; }
        .circle-logo { width: 48px; height: 48px; background: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 2rem; margin: 0 auto 1rem auto; }
        .sidebar-user { text-align: center; margin-bottom: 1.5rem; }
        .avatar { width: 56px; height: 56px; border-radius: 50%; margin-bottom: 0.5rem; }
        .user-info .name { font-weight: 600; font-size: 1.1rem; }
        .user-info .phone { color: #888; font-size: 0.95rem; }
        .sidebar-menu { margin-bottom: 2rem; }
        .sidebar-menu a { display: block; padding: 0.8rem 2rem; color: #222; text-decoration: none; border-left: 4px solid transparent; font-weight: 500; }
        .sidebar-menu a.active, .sidebar-menu a:hover { background: #f3f6fd; border-left: 4px solid #16a34a; color: #16a34a; }
        .sidebar-logout { position: absolute; bottom: 2rem; left: 0; width: 100%; text-align: center; }
        .sidebar-logout a { color: #ef4444; font-weight: 500; }
        .main-content { margin-left: 260px; padding: 2rem; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px #0001; padding: 2rem; margin-bottom: 2rem; }
        .card-green { background: linear-gradient(90deg,#16a34a 60%,#059669 100%); color: #fff; }
        .card-green .big { font-size: 2.5rem; font-weight: 700; }
        .btn-withdraw { background: #fff; color: #16a34a; border: none; padding: 0.6rem 1.4rem; border-radius: 6px; font-weight: 600; margin-top: 1rem; }
        .stats-row { display: flex; gap: 2rem; margin-bottom: 2rem; }
        .stats-box { flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 1.2rem; text-align: center; }
        .stats-box .num { font-size: 1.5rem; font-weight: 700; }
        .stats-box.green { color: #16a34a; }
        .stats-box.blue { color: #2563eb; }
        .stats-box.orange { color: #f59e42; }
        .stats-box.yellow { color: #fbbf24; }
        .report-row { display: flex; gap: 1.2rem; margin-bottom: 2rem; }
        .report-box { flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 1.2rem; text-align: center; border: 2px solid #eee; cursor: pointer; transition: border 0.2s; }
        .report-box.red { border-color: #ef4444; color: #ef4444; }
        .report-box.orange { border-color: #f59e42; color: #f59e42; }
        .report-box.blue { border-color: #2563eb; color: #2563eb; }
        .report-box.purple { border-color: #a855f7; color: #a855f7; }
        .report-box:hover { border-color: #16a34a; }
        /* Thêm CSS cho hiệu ứng và màu sắc mới */
        #withdrawBox { display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); }
        #withdrawBox .box-content { max-width:420px; margin:6% auto; background:#fff; border-radius:12px; box-shadow:0 4px 24px #05966944; padding:2.2rem 2rem; position:relative; border:2px solid #16a34a; }
        #withdrawBox h4 { margin-bottom:0.7rem; color:#16a34a; text-align:center; font-weight:700; letter-spacing:1px; }
        #withdrawBox .info-box { background:linear-gradient(90deg,#e6f4ea 60%,#d1fae5 100%); border-radius:8px; padding:1.2rem 1.5rem; margin-bottom:0.7rem; color:#222; font-size:1.12rem; box-shadow:0 2px 8px #16a34a22; }
        #withdrawBox .info-box div { margin-bottom:0.3rem; }
        #withdrawBox label { font-weight:600; }
        #withdrawBox input { padding:0.7rem; border-radius:6px; border:2px solid #2563eb; font-size:1.12rem; background:#f3f6fd; }
        #withdrawBox input:focus { border-color: #16a34a; background: #e6ffed; }
        #withdrawBox .btn-withdraw { background:#16a34a; color:#fff; font-weight:700; border-radius:6px; padding:0.7rem 1.5rem; font-size:1.08rem; box-shadow:0 2px 8px #16a34a22; transition:background 0.2s; }
        #withdrawBox .btn-withdraw:hover { background:#15803d; }
        #withdrawBox .btn-secondary { font-weight:600; border-radius:6px; padding:0.7rem 1.5rem; }
        #withdrawBox .close-btn { position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:#ef4444; cursor:pointer; }
        #withdrawBox .close-btn:hover { color:#dc2626; }
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
        <img src="<?= !empty($shipper['picture']) ? htmlspecialchars($shipper['picture']) : '../../images/shipper.png' ?>" class="avatar" alt="Avatar">
        <div class="user-info">
            <div class="name"><?= htmlspecialchars($name) ?></div>
            <div class="phone"><?= htmlspecialchars($phone) ?></div>
        </div>
    </div>
    <div class="sidebar-menu">
        <a href="../dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="../ship_orders/oders_wait.php"><i class="fa-solid fa-list"></i> Quản lý đơn hàng</a>
        <a href="#" class="active"><i class="fa-solid fa-chart-bar"></i> Báo cáo</a>
        <a href="../item_list/support.php"><i class="fa-solid fa-headset"></i> Hỗ trợ</a>
    </div>
    <div class="sidebar-logout">
        <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
    </div>
</div>
<div class="main-content">
    <div class="card card-green">
        <div>Tổng thu nhập có thể rút</div>
        <div class="big"><?= number_format($income, 0, ',', '.') ?>đ</div>
        <div style="margin-top:0.5rem; font-size:0.95rem;">Cập nhật lần cuối: <?= date('H:i:s d/m/Y') ?></div>
        <button type="button" class="btn-withdraw" onclick="showWithdrawBox()">Yêu cầu rút tiền</button>
        <div id="withdrawBox" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
            <div style="max-width:420px; margin:6% auto; background:#fff; border-radius:12px; box-shadow:0 4px 24px #05966944; padding:2.2rem 2rem; position:relative; border:2px solid #16a34a;">
                <form method="post" style="display:flex; flex-direction:column; gap:1.2rem;">
                    <h4 style="margin-bottom:0.7rem; color:#16a34a; text-align:center; font-weight:700; letter-spacing:1px;">Yêu cầu rút tiền</h4>
                    <div style="background:linear-gradient(90deg,#e6f4ea 60%,#d1fae5 100%); border-radius:8px; padding:1.2rem 1.5rem; margin-bottom:0.7rem; color:#222; font-size:1.12rem; box-shadow:0 2px 8px #16a34a22;">
                        <div style="margin-bottom:0.3rem;"><i class="fa fa-user" style="color:#16a34a;"></i> <b>Họ tên:</b> <span style="color:#059669; font-weight:600;"><?= htmlspecialchars($name) ?></span></div>
                        <div style="margin-bottom:0.3rem;"><i class="fa fa-phone" style="color:#16a34a;"></i> <b>SĐT:</b> <span style="color:#059669; font-weight:600;"><?= htmlspecialchars($phone) ?></span></div>
                        <div style="margin-bottom:0.3rem;"><i class="fa fa-car" style="color:#16a34a;"></i> <b>Biển số xe:</b> <span style="color:#059669; font-weight:600;"><?= htmlspecialchars($shipper['license_plate'] ?? '') ?></span></div>
                        <div><i class="fa fa-bicycle" style="color:#16a34a;"></i> <b>Loại xe:</b> <span style="color:#059669; font-weight:600;"><?= htmlspecialchars($shipper['vehicle_type'] ?? '') ?></span></div>
                    </div>
                    <label for="bank_account" style="font-weight:600; color:#2563eb;">Số tài khoản ngân hàng:</label>
                    <input type="text" name="bank_account" id="bank_account" style="padding:0.7rem; border-radius:6px; border:2px solid #2563eb; font-size:1.12rem; background:#f3f6fd;" required oninput="showShipperName()">
                    <label for="bank_name" style="font-weight:600; color:#2563eb;">Tên ngân hàng:</label>
                    <input type="text" name="bank_name" id="bank_name" style="padding:0.7rem; border-radius:6px; border:2px solid #2563eb; font-size:1.12rem; background:#f3f6fd;" required oninput="showShipperName()">
                    <div id="shipperNameBox" style="display:none; margin:0.5rem 0; color:#059669; font-weight:700; font-size:1.12rem; text-align:center; background:#e0f2fe; border-radius:6px; padding:0.5rem 0; transition:all 0.3s;">Tên chủ tài khoản: <?= htmlspecialchars($name) ?></div>
                    <label for="amount" style="font-weight:600; color:#f59e42;">Số tiền muốn rút:</label>
                    <input type="number" name="amount" min="10000" max="<?= $income ?>" style="padding:0.7rem; border-radius:6px; border:2px solid #f59e42; font-size:1.12rem; background:#fffbe6;" required oninput="animateAmount(this)">
                    <div style="display:flex; gap:1rem; margin-top:0.7rem; justify-content:center;">
                        <button type="submit" name="withdraw" class="btn-withdraw" style="background:#16a34a; color:#fff; font-weight:700; border-radius:6px; padding:0.7rem 1.5rem; font-size:1.08rem; box-shadow:0 2px 8px #16a34a22; transition:background 0.2s;">Gửi yêu cầu</button>
                        <button type="button" class="btn btn-secondary" onclick="hideWithdrawBox()" style="font-weight:600; border-radius:6px; padding:0.7rem 1.5rem;">Huỷ</button>
                    </div>
                </form>
                <button onclick="hideWithdrawBox()" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:#ef4444; cursor:pointer;" title="Đóng"><i class="fa fa-times-circle"></i></button>
            </div>
        </div>
        <?php if (!empty($msg)): ?><div style="margin-top:1rem; color:#fff; font-weight:600;"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    </div>
    <div class="stats-row">
        <div class="stats-box green">
            <div>Đơn hàng</div>
            <div class="num"><?= $orders ?></div>
        </div>
        <div class="stats-box green">
            <div>Thu nhập</div>
            <div class="num"><?= number_format($income, 0, ',', '.') ?>đ</div>
        </div>
        <div class="stats-box blue">
            <div>Quãng đường</div>
            <div class="num"><?= number_format($distance, 1) ?>km</div>
        </div>
        <div class="stats-box orange">
            <div>Đánh giá TB</div>
            <div class="num"><?= $avg_rating ?></div>
        </div>
    </div>
    <div class="card">
        <h5>Lịch sử giao hàng</h5>
        <div style="max-height:220px; overflow-y:auto;">
        <?php
        $sql_history = "SELECT o_id, title, total, order_time FROM users_orders WHERE shipper_id='$shipper_id' AND status='closed' ORDER BY order_time DESC LIMIT 20";
        $result_history = $db->query($sql_history);
        if ($result_history && $result_history->num_rows > 0):
            while ($row = $result_history->fetch_assoc()):
        ?>
            <div style="padding:0.5rem 0; border-bottom:1px solid #eee; font-size:1rem;">
                <b>#<?= $row['o_id'] ?></b> - <?= htmlspecialchars($row['title']) ?> - <?= number_format($row['total'],0,',','.') ?>đ <span style="color:#888; font-size:0.95rem;">(<?= date('d/m/Y H:i', strtotime($row['order_time'])) ?>)</span>
            </div>
        <?php endwhile; else: ?>
            <div style="color:#888; font-size:1rem;">Chưa có đơn hoàn thành.</div>
        <?php endif; ?>
        </div>
    </div>
    <div class="report-row">
        <div class="report-box red" onclick="showReportBox('Chậm trễ')">&#9888; Báo cáo chậm trễ</div>
        <div class="report-box orange" onclick="showReportBox('Hàng hóa hỏng hóc')">&#9888; Hàng hóa hỏng hóc</div>
        <div class="report-box blue" onclick="showReportBox('Khách hàng không nhận')">&#128100; Khách hàng không nhận</div>
        <div class="report-box purple" onclick="showReportBox('Sự cố khác')">&#10067; Sự cố khác</div>
    </div>
    <div id="reportModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
        <div style="max-width:400px; margin:8% auto; background:#fff; border-radius:8px; box-shadow:0 2px 16px #0002; padding:2rem; position:relative;">
            <form id="reportForm" onsubmit="sendReport(event)">
                <h5 id="reportTitle"></h5>
                <textarea id="reportContent" rows="4" style="width:100%; border-radius:6px; border:1px solid #ccc; padding:0.5rem; margin-top:1rem;" placeholder="Nhập nội dung báo cáo..."></textarea>
                <div style="margin-top:1rem; text-align:right;">
                    <button type="submit" class="btn btn-success">Gửi</button>
                    <button type="button" class="btn btn-secondary" onclick="closeReportBox()">Huỷ</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script>
    function showWithdrawBox() {
        document.getElementById('withdrawBox').style.display = 'block';
    }
    function hideWithdrawBox() {
        document.getElementById('withdrawBox').style.display = 'none';
    }
    function showShipperName() {
        var bankAcc = document.getElementById('bank_account').value.trim();
        var bankName = document.getElementById('bank_name').value.trim();
        var box = document.getElementById('shipperNameBox');
        if (bankAcc && bankName) {
            box.style.display = 'block';
            box.style.opacity = 1;
            box.style.transform = 'scale(1.05)';
            setTimeout(function(){ box.style.transform = 'scale(1)'; }, 300);
        } else {
            box.style.display = 'none';
            box.style.opacity = 0;
        }
    }
    function animateAmount(input) {
        input.style.background = '#fffbe6';
        input.style.borderColor = '#f59e42';
        if (input.value && input.value > 0) {
            input.style.background = '#fef9c3';
            input.style.borderColor = '#16a34a';
        }
    }
    function showReportBox(type) {
        document.getElementById('reportModal').style.display = 'block';
        document.getElementById('reportTitle').innerText = type;
        document.getElementById('reportContent').value = '';
    }
    function closeReportBox() {
        document.getElementById('reportModal').style.display = 'none';
    }
    function sendReport(e) {
        e.preventDefault();
        var type = document.getElementById('reportTitle').innerText;
        var content = document.getElementById('reportContent').value.trim();
        if (!content) { alert('Vui lòng nhập nội dung báo cáo!'); return; }
        // Gửi báo cáo qua AJAX sang Mng_shop/index.php
        fetch('../../Mng_shop/index.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=report&report_type=' + encodeURIComponent(type) + '&content=' + encodeURIComponent(content)
        })
        .then(res => res.text())
        .then(data => {
            alert('Đã gửi báo cáo!');
            closeReportBox();
        })
        .catch(() => {
            alert('Gửi báo cáo thất bại!');
        });
    }
</script>
</body>
</html>
