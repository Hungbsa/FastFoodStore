<?php
require_once '../../connection/connect.php';


$order_count = 0;
$order_total = 0;
$new_customers = 0;
$popular_dish = '';
$avg_order_value = 0;
$return_rate = 0;
$popular_time = '';

// Dữ liệu doanh thu từng ngày (7 ngày gần nhất)
$revenue_labels = [];
$revenue_data = [];
$revenue_sql = "SELECT DATE(order_time) as day, SUM(total) as revenue FROM users_orders GROUP BY day ORDER BY day DESC LIMIT 7";
$revenue_res = mysqli_query($db, $revenue_sql);
if ($revenue_res) {
    $tmp_labels = [];
    $tmp_data = [];
    while ($row = mysqli_fetch_assoc($revenue_res)) {
        $tmp_labels[] = date('d/m', strtotime($row['day']));
        $tmp_data[] = (float)$row['revenue'];
    }
    // Đảo ngược để hiển thị từ cũ đến mới
    $revenue_labels = array_reverse($tmp_labels);
    $revenue_data = array_reverse($tmp_data);
}

$order_sql = "SELECT COUNT(*) as cnt, COALESCE(SUM(total),0) as total FROM users_orders WHERE MONTH(order_time) = MONTH(CURDATE()) AND YEAR(order_time) = YEAR(CURDATE())";
$order_res = mysqli_query($db, $order_sql);
if ($order_res && ($order_row = mysqli_fetch_assoc($order_res))) {
    $order_count = $order_row['cnt'];
    $order_total = $order_row['total'];
}
// Khách hàng mới trong tháng
$user_sql = "SELECT COUNT(*) as cnt FROM users WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())";
$user_res = mysqli_query($db, $user_sql);
if ($user_res && ($user_row = mysqli_fetch_assoc($user_res))) {
    $new_customers = $user_row['cnt'];
}
// Món ăn phổ biến nhất (dựa trên title trong users_orders)
$dish_sql = "SELECT title, COUNT(*) as cnt FROM users_orders WHERE MONTH(order_time) = MONTH(CURDATE()) AND YEAR(order_time) = YEAR(CURDATE()) GROUP BY title ORDER BY cnt DESC LIMIT 1";
$dish_res = mysqli_query($db, $dish_sql);
if ($dish_res && ($dish_row = mysqli_fetch_assoc($dish_res))) {
    $popular_dish = $dish_row['title'];
}
// Giá trị đơn hàng trung bình
if ($order_count > 0) {
    $avg_order_value = round($order_total / $order_count);
}
// Tỷ lệ khách quay lại (số khách có >1 đơn hàng / tổng khách, dùng u_id)
$return_sql = "SELECT COUNT(DISTINCT u_id) as total, SUM(order_count > 1) as returned FROM (SELECT u_id, COUNT(*) as order_count FROM users_orders GROUP BY u_id) t";
$return_res = mysqli_query($db, $return_sql);
if ($return_res && ($return_row = mysqli_fetch_assoc($return_res))) {
    $return_rate = $return_row['total'] > 0 ? round($return_row['returned'] / $return_row['total'] * 100) : 0;
}
// Thời gian truy cập nhiều nhất (dựa trên order_time)
$time_sql = "SELECT HOUR(order_time) as hour, COUNT(*) as cnt FROM users_orders WHERE MONTH(order_time) = MONTH(CURDATE()) AND YEAR(order_time) = YEAR(CURDATE()) GROUP BY hour ORDER BY cnt DESC LIMIT 1";
$time_res = mysqli_query($db, $time_sql);
if ($time_res && ($time_row = mysqli_fetch_assoc($time_res))) {
    $h = $time_row['hour'];
    $popular_time = sprintf('%02d:00 - %02d:00', $h, $h+2);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phân tích & báo cáo</title>
    <link rel="icon" href="../../images/img/shopp.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/index.css">
    <style>
        body { background: #f7f8fa; }
        .card { margin-bottom: 24px; }
        .stat-box { background: #eef2ff; border-radius: 12px; padding: 24px; text-align: center; }
        .stat-title { font-size: 18px; color: #555; }
        .stat-value { font-size: 32px; font-weight: bold; margin-bottom: 8px; }
        .stat-desc { color: #888; }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="logo mb-4">
            <i class="fa-solid fa-store"></i> <span>Restaurant Manager</span>
        </div>
        <ul class="sidebar-menu">
            <li><a href="../index.php"><i class="fa fa-chart-line me-2"></i> <span>Tổng quan</span></a></li>
            <li><a href="../Shop_authMng/shop_manage.php"><i class="fa fa-store me-2"></i> <span>Quản lý Cửa hàng</span></a></li>
            <li><a href="../Shop_authMng/order_manage.php"><i class="fa fa-money-check-alt me-2"></i> <span>Xử lý Đơn hàng</span></a></li>
            <li><a href="../menuitem/Menu_manage.php"><i class="fa fa-utensils me-2"></i> <span>Quản lý Thực đơn</span></a></li>
            <li><a href="../menuitem/listmenu_manage.php"><i class="fa fa-cube me-2"></i> <span>Quản lý Kho</span></a></li>
            <li><a href="../Shop_authMng/staff_manage.php"><i class="fa fa-user-friends me-2"></i> <span>Quản lý Nhân sự</span></a></li>
            <li><a href="../Listexp/vourcher.php"><i class="fa fa-percent me-2"></i> <span>Khuyến mãi</span></a></li>
            <li class="active"><a href="../Listexp/analyst.php"><i class="fa fa-chart-pie me-2"></i> <span>Phân tích & Báo cáo</span></a></li>
            <li><a href="../Listexp/settings.php"><i class="fa fa-cog me-2"></i> <span>Cài đặt</span></a></li>
            <li><a href="../logout.php" class="text-danger"><i class="fa fa-sign-out-alt me-2"></i> <span>Đăng xuất</span></a></li>
        </ul>
    </div>
<div class="main-content" id="mainContent">
        <div class="topbar">
            <span class="hamburger" id="hamburger"><i class="fa fa-bars"></i></span>
            <span class="title">Phân tích & báo cáo</span>
            <span class="user ms-auto">Xin chào, <?php echo $_SESSION['username'] ?? 'Quản lý'; ?>!</span>
        </div>
    <div class="container py-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Phân tích doanh thu</h5>
                        <canvas id="revenueChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Hành vi khách hàng</h5>
                        <ul class="list-unstyled">
                            <li>Thời gian truy cập nhiều nhất <span class="float-end fw-bold"><?= $popular_time ?: '-' ?></span></li>
                            <li>Món ăn phổ biến nhất <span class="float-end fw-bold"><?= $popular_dish ?: '-' ?></span></li>
                            <li>Giá trị đơn hàng trung bình <span class="float-end fw-bold"><?= $avg_order_value ? number_format($avg_order_value,0,',','.') . 'đ' : '-' ?></span></li>
                            <li>Tỷ lệ khách hàng quay lại <span class="float-end fw-bold"><?= $return_rate ?>%</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-value text-primary"><?= $order_count ?></div>
                    <div class="stat-title">Đơn hàng trong tháng</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-value text-success"><?= number_format($order_total,0,',','.') ?>đ</div>
                    <div class="stat-title">Doanh thu trong tháng</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-value text-purple"><?= $new_customers ?></div>
                    <div class="stat-title">Khách hàng mới</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Real-time Chat Bubble for Mng_shop -->
    <div id="ws-chat-bubble" style="position:fixed;bottom:36px;right:36px;z-index:99999;">
      <button id="ws-chat-toggle" style="width:64px;height:64px;border-radius:50%;background:#00b14f;color:#fff;border:none;box-shadow:0 2px 16px rgba(0,0,0,0.13);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.18s;padding:0;">
        <img src="../../images/img/iconss.png" alt="Chat" style="width:48px;height:48px;border-radius:50%;box-shadow:0 1px 6px rgba(0,0,0,0.10);background:#fff;">
      </button>
      <div id="ws-chat-box" style="display:none;position:absolute;bottom:80px;right:0;width:340px;background:#fff;border-radius:18px;box-shadow:0 2px 16px rgba(0,0,0,0.13);padding:16px;">
        <div style="font-weight:600;font-size:1.08rem;margin-bottom:8px;color:#00b14f;display:flex;justify-content:space-between;align-items:center;">
          Chăm sóc khách hàng
          <button id="ws-chat-close" style="background:none;border:none;font-size:1.3rem;color:#888;cursor:pointer;">&times;</button>
        </div>
        <div id="chat-messages" style="height:180px;overflow-y:auto;margin-bottom:12px;background:#f9f9f9;border-radius:8px;padding:8px;"></div>
        <div style="display:flex;gap:8px;">
          <input type="text" id="chat-input" placeholder="Nhập tin nhắn..." style="flex:1;padding:8px;border-radius:6px;border:1px solid #eee;">
          <button onclick="sendMessage()" style="padding:8px 18px;background:#00b14f;color:#fff;border:none;border-radius:6px;font-weight:600;">Gửi</button>
        </div>
      </div>
    </div>
    <style>
      #ws-chat-bubble {z-index:99999;}
      #ws-chat-toggle {transition:background 0.18s;}
      #ws-chat-toggle:hover {background:#ffe0b2;color:#00b14f;}
      #ws-chat-box {animation: wsChatFadeIn 0.22s;}
      @keyframes wsChatFadeIn {from{opacity:0;transform:scale(0.95);}to{opacity:1;transform:scale(1);}}
      .ws-chat-row {display:flex;align-items:flex-end;gap:10px;margin-bottom:8px;}
      .ws-chat-row.user {justify-content:flex-end;}
      .ws-chat-avatar {width:38px;height:38px;border-radius:50%;background:#00b14f;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;font-weight:bold;box-shadow:0 2px 8px rgba(0,0,0,0.07);}
      .ws-chat-avatar.mng_shop {
        background: #fff;
        color: #00b14f;
        border: 2.5px solid #00b14f;
        background-image: url('../../images/img/ql2.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        box-shadow: 0 2px 8px rgba(0,177,79,0.10);
      }
      .ws-chat-bubble {max-width:75%;padding:12px 18px;border-radius:16px;font-size:1.08rem;line-height:1.5;box-shadow:0 1px 6px rgba(0,0,0,0.04);word-break:break-word;}
      .ws-chat-row.user .ws-chat-bubble {background:#00b14f;color:#fff;border-bottom-right-radius:6px;}
      .ws-chat-row.mng_shop .ws-chat-bubble {background:#eee;color:#222;border-bottom-left-radius:6px;}
    </style>
    <script>
        var wsChatToggle = document.getElementById('ws-chat-toggle');
        var wsChatBox = document.getElementById('ws-chat-box');
        var wsChatClose = null;
        var chatMessagesDiv = document.getElementById('chat-messages');
        var chatHistory = [];
        function renderChatMessages() {
          chatMessagesDiv.innerHTML = '';
          chatHistory.forEach(function(msg) {
            var row = document.createElement('div');
            row.className = 'ws-chat-row ' + (msg.sender === 'mng_shop' ? 'mng_shop' : 'user');
            var avatar = document.createElement('div');
            avatar.className = 'ws-chat-avatar ' + (msg.sender === 'mng_shop' ? 'mng_shop' : '');
            if(msg.sender === 'mng_shop') {
              avatar.innerHTML = '';
            } else {
              avatar.innerHTML = '<i class="fa fa-user"></i>';
            }
            var bubble = document.createElement('div');
            bubble.className = 'ws-chat-bubble';
            bubble.textContent = msg.content;
            if(msg.sender === 'mng_shop') {
              row.appendChild(avatar);
              row.appendChild(bubble);
            } else {
              row.appendChild(bubble);
              row.appendChild(avatar);
            }
            chatMessagesDiv.appendChild(row);
          });
          chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
        }
        wsChatToggle.onclick = function() {
          wsChatBox.style.display = 'block';
          wsChatToggle.style.display = 'none';
          wsChatClose = document.getElementById('ws-chat-close');
          if(wsChatClose) wsChatClose.onclick = function(){
            wsChatBox.style.display = 'none';
            wsChatToggle.style.display = 'flex';
          };
          setTimeout(function(){document.getElementById('chat-input').focus();},200);
          renderChatMessages();
        };
        var ws = new WebSocket('ws://localhost:9000');
        ws.onmessage = function(e) {
          var msg = JSON.parse(e.data);
          if(msg.receiver === 'mng_shop' || msg.sender === 'mng_shop' || msg.sender === 'user') {
            chatHistory.push(msg);
            renderChatMessages();
          }
        };
        function sendMessage() {
          var msg = document.getElementById('chat-input').value;
          if (!msg) return;
          var data = {sender: 'mng_shop', receiver: 'user', content: msg};
          ws.send(JSON.stringify(data));
          chatHistory.push(data);
          renderChatMessages();
          document.getElementById('chat-input').value = '';
        }
        // Thêm sự kiện Enter để gửi tin nhắn
        document.getElementById('chat-input').addEventListener('keydown', function(e) {
          if (e.key === 'Enter') sendMessage();
        });
    </script>
<!-- Real-time Chat Bubble for Mng_shop -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Sidebar toggle logic
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const hamburger = document.getElementById('hamburger');
    hamburger.addEventListener('click', function() {
        sidebar.classList.toggle('hide');
        mainContent.classList.toggle('full');
    });
    // Chart.js doanh thu
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($revenue_labels); ?>,
            datasets: [
                {
                    label: 'Doanh thu (VNĐ)',
                    data: <?php echo json_encode($revenue_data); ?>,
                    backgroundColor: 'rgba(37,99,235,0.3)',
                    borderColor: '#2563eb',
                    borderWidth: 2,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7
                },
                {
                    label: 'Xu hướng',
                    data: <?php echo json_encode($revenue_data); ?>,
                    type: 'line',
                    borderColor: '#eab308',
                    backgroundColor: 'rgba(234,179,8,0.1)',
                    fill: false,
                    tension: 0.3,
                    pointRadius: 3,
                    pointBackgroundColor: '#eab308',
                    order: 2
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#eee' }
                }
            }
        }
    });
</script>
</body>
</html>
