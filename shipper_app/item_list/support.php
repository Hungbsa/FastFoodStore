<?php
session_start();
$name = $_SESSION['shipper']['full_name'] ?? '';
$phone = $_SESSION['shipper']['phone_number'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../../images/img/shipper.png">
    <title>Hỗ trợ & Trợ giúp</title>
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
        .card-orange { background: linear-gradient(90deg,#f59e42 60%,#ea580c 100%); color: #fff; }
        .faq-list { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px #0001; padding: 2rem; margin-bottom: 2rem; }
        .faq-item { border-bottom: 1px solid #eee; padding: 1rem 0; cursor: pointer; }
        .faq-item:last-child { border-bottom: none; }
        .faq-question { display: flex; align-items: center; font-weight: 500; font-size: 1.08rem; }
        .faq-arrow { margin-left: auto; font-size: 1.2rem; color: #888; transition: transform 0.2s; }
        .faq-answer { display: none; color: #444; font-size: 1rem; margin-top: 0.7rem; }
        .faq-item.active .faq-arrow { transform: rotate(90deg); color: #16a34a; }
        .faq-item.active .faq-answer { display: block; }
        .contact-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px #0001; padding: 2rem; margin-bottom: 2rem; }
        .support-channels { display: flex; gap:2rem; margin-bottom:2rem; }
        .support-box { flex:1; border-radius:12px; padding:2rem; color:#fff; font-size:1.1rem; }
        .support-box.green { background: linear-gradient(90deg,#16a34a 60%,#059669 100%); }
        .support-box.orange { background: linear-gradient(90deg,#f59e42 60%,#ea580c 100%); }
        .support-btn { background:#fff; color:#16a34a; border:none; padding:0.6rem 1.4rem; border-radius:6px; font-weight:600; margin-top:1rem; }
        .hotline-btn { background:#fff; color:#ea580c; border:none; padding:0.6rem 1.4rem; border-radius:6px; font-weight:600; margin-top:1rem; }
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
        <a href="../ship_orders/oders_wait.php"><i class="fa-solid fa-list"></i> Quản lý đơn hàng</a>
        <a href="../item_list/report.php"><i class="fa-solid fa-chart-bar"></i> Báo cáo</a>
        <a href="#" class="active"><i class="fa-solid fa-headset"></i> Hỗ trợ</a>
    </div>
    <div class="sidebar-logout">
        <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
    </div>
</div>
<div class="main-content">
    <h2 style="font-weight:700; margin-bottom:2rem;">Hỗ trợ & Trợ giúp</h2>
    <div class="support-channels">
        <div class="support-box green">
            <div style="font-size:1.2rem; font-weight:600;">Hỗ trợ trực tiếp</div>
            <div style="margin-top:0.5rem;">Kết nối với CSKH ngay lập tức</div>
            <button class="support-btn"><i class="fa fa-comments"></i> Chat ngay</button>
        </div>
        <div class="support-box orange">
            <div style="font-size:1.2rem; font-weight:600;">Hotline hỗ trợ</div>
            <div style="margin-top:0.5rem;">Gọi ngay khi cần hỗ trợ khẩn cấp</div>
            <button class="hotline-btn"><i class="fa fa-phone"></i> 1900 1234</button>
        </div>
    </div>
    <div class="faq-list">
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">Làm thế nào để nhận đơn hàng mới?<span class="faq-arrow">&#9654;</span></div>
            <div class="faq-answer">Trên trang Dashboard, bấm vào nút "Nhận đơn mới" hoặc vào mục "Quản lý đơn hàng" và chọn tab "Chờ nhận". Bấm "Nhận đơn" trên đơn hàng bạn muốn giao.</div>
        </div>
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">Khi nào tôi nhận được tiền công?<span class="faq-arrow">&#9654;</span></div>
            <div class="faq-answer">Tiền công sẽ được chuyển vào ví sau khi hoàn thành đơn hàng và được duyệt rút tiền. Bạn có thể kiểm tra trạng thái rút tiền trong mục Báo cáo.</div>
        </div>
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">Tôi bị trễ giao hàng, phải làm gì?<span class="faq-arrow">&#9654;</span></div>
            <div class="faq-answer">Bạn nên báo cáo sự cố ngay trong mục Báo cáo để được hỗ trợ kịp thời. Ngoài ra, hãy liên hệ hotline nếu cần xử lý gấp.</div>
        </div>
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">Làm sao để cải thiện đánh giá của tôi?<span class="faq-arrow">&#9654;</span></div>
            <div class="faq-answer">Luôn giao hàng đúng giờ, lịch sự với khách hàng và chủ động hỗ trợ khi có vấn đề phát sinh.</div>
        </div>
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">Tôi không thể đăng nhập được?<span class="faq-arrow">&#9654;</span></div>
            <div class="faq-answer">Kiểm tra lại số điện thoại và mật khẩu. Nếu vẫn không đăng nhập được, hãy liên hệ CSKH hoặc hotline để được hỗ trợ.</div>
        </div>
    </div>
    <div class="contact-card">
        <div style="font-weight:600; margin-bottom:0.7rem;">Thông tin liên hệ</div>
        <div>Giờ hoạt động<br>Thứ 2 - Chủ nhật: 6:00 - 22:00<br>Hỗ trợ khẩn cấp: 24/7</div>
        <div style="margin-top:1.2rem; font-weight:600;">Kênh hỗ trợ</div>
        <div style="margin-top:0.5rem;">
            <i class="fa fa-phone" style="color:#16a34a;"></i> Hotline: 1900 1234<br>
            <i class="fa fa-envelope" style="color:#16a34a;"></i> Email: support@shipperpro.com<br>
            <i class="fa fa-facebook" style="color:#16a34a;"></i> Facebook: ShipperPro Vietnam
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script>
function toggleFaq(item) {
    item.classList.toggle('active');
}
</script>
</body>
</html>
