<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'report') {
    $report_type = isset($_POST['report_type']) ? trim($_POST['report_type']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $shipper_id = isset($_SESSION['shipper']['shipper_id']) ? $_SESSION['shipper']['shipper_id'] : 'unknown';
    $shipper_name = isset($_SESSION['shipper']['full_name']) ? $_SESSION['shipper']['full_name'] : 'unknown';
    if ($report_type && $content) {
        // Lưu vào bảng mng_reports (nếu chưa có, tạo bảng này)
        require_once '../connection/connect.php';
        $stmt = $db->prepare("INSERT INTO mng_reports (shipper_id, shipper_name, report_type, content, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param('isss', $shipper_id, $shipper_name, $report_type, $content);
        $stmt->execute();
        $stmt->close();
        echo 'success';
    } else {
        echo 'error';
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Manager Dashboard</title>
    <link rel="icon" href="../images/img/shopp.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../Mng_shop/css/index.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="logo mb-4">
            <i class="fa-solid fa-store"></i> <span>Restaurant Manager</span>
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="#"><i class="fa fa-chart-line me-2"></i> <span>Tổng quan</span></a></li>
            <li><a href="../Mng_shop/Shop_authMng/shop_manage.php"><i class="fa fa-store me-2"></i> <span>Quản lý Cửa hàng</span></a></li>
            <li><a href="../Mng_shop/Shop_authMng/order_manage.php"><i class="fa fa-money-check-alt me-2"></i> <span>Xử lý Đơn hàng</span></a></li>
            <li><a href="../Mng_shop/menuitem/Menu_manage.php"><i class="fa fa-utensils me-2"></i> <span>Quản lý Thực đơn</span></a></li>
            <li><a href="../Mng_shop/menuitem/listmenu_manage.php"><i class="fa fa-cube me-2"></i> <span>Quản lý Kho</span></a></li>
            <li><a href="../Mng_shop/Shop_authMng/staff_manage.php"><i class="fa fa-user-friends me-2"></i> <span>Quản lý Nhân sự</span></a></li>
            <li><a href="../Mng_shop/Listexp/vourcher.php"><i class="fa fa-percent me-2"></i> <span>Khuyến mãi</span></a></li>
            <li><a href="../Mng_shop/Listexp/analyst.php"><i class="fa fa-chart-pie me-2"></i> <span>Phân tích & Báo cáo</span></a></li>
            <li><a href="../Mng_shop/Listexp/settings.php"><i class="fa fa-cog me-2"></i> <span>Cài đặt</span></a></li>
            <li><a href="logout.php" class="text-danger"><i class="fa fa-sign-out-alt me-2"></i> <span>Đăng xuất</span></a></li>
        </ul>
    </div>
    <div class="main-content" id="mainContent">
        <div class="topbar">
            <span class="hamburger" id="hamburger"><i class="fa fa-bars"></i></span>
            <span class="title">Bảng điều khiển cửa hàng</span>
            <span class="user ms-auto">Xin chào, <?php echo $_SESSION['username'] ?? 'Quản lý'; ?>!</span>
            <span class="notify-icon ms-3" id="notifyBell" style="position:relative;cursor:pointer;">
                <i class="fa fa-bell" style="font-size:1.5rem;color:#ff9800;"></i>
                <span id="notifyBadge" style="position:absolute;top:-6px;right:-6px;background:#ef4444;color:#fff;border-radius:50%;padding:2px 7px;font-size:0.9rem;display:none;">1</span>
            </span>
        </div>
        <div id="notifyPopup" style="display:none;position:absolute;top:56px;right:36px;z-index:9999;background:#fff;border-radius:8px;box-shadow:0 2px 16px #0002;padding:1rem 1.2rem;min-width:260px;max-width:350px;max-height:400px;overflow-y:auto;">
            <div style="font-weight:600;color:#ff9800;margin-bottom:8px;"><i class="fa fa-bell"></i> Thông báo</div>
            <div id="notifyContent" style="color:#222;font-size:1rem;">
            <?php
            require_once '../connection/connect.php';
            // Lấy thông báo mới nhất: đơn hàng mới hoặc shipper nhận đơn
            $result_reports = $db->query("SELECT shipper_name, report_type, content, created_at FROM mng_reports ORDER BY created_at DESC LIMIT 10");
            $fastfood_pdo = null;
            try {
                $fastfood_pdo = new PDO('mysql:host=localhost;dbname=fastfood;charset=utf8', 'root', '');
                $fastfood_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {}
            $order_notify = [];
            if ($fastfood_pdo) {
                // Đơn hàng mới trong 24h
                $orders = $fastfood_pdo->query("SELECT o_id, title, order_time FROM users_orders WHERE order_time >= NOW() - INTERVAL 1 DAY ORDER BY order_time DESC LIMIT 10");
                foreach ($orders as $o) {
                    $order_notify[] = [
                        'type' => 'order',
                        'title' => $o['title'],
                        'o_id' => $o['o_id'],
                        'order_time' => $o['order_time']
                    ];
                }
            }
            $shipper_notify = [];
            if ($result_reports && $result_reports->num_rows > 0) {
                while ($r = $result_reports->fetch_assoc()) {
                    // Nếu là nhận đơn thì label rõ ràng
                    if (strpos(strtolower($r['report_type']), 'nhận đơn') !== false || strpos(strtolower($r['content']), 'nhận đơn') !== false) {
                        $shipper_notify[] = [
                            'type' => 'shipper',
                            'shipper_name' => $r['shipper_name'],
                            'report_type' => $r['report_type'],
                            'content' => $r['content'],
                            'created_at' => $r['created_at']
                        ];
                    } else {
                        // Các báo cáo khác
                        $shipper_notify[] = [
                            'type' => 'report',
                            'shipper_name' => $r['shipper_name'],
                            'report_type' => $r['report_type'],
                            'content' => $r['content'],
                            'created_at' => $r['created_at']
                        ];
                    }
                }
            }
            $notify_count = count($order_notify) + count($shipper_notify);
            // Hiển thị thông báo đơn hàng mới
            foreach ($order_notify as $o) {
                echo '<div style="margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:1px solid #eee;">'
                    .'<b style="color:#2563eb">Đơn hàng mới</b> - <span style="color:#f59e42">'.htmlspecialchars($o['title']).'</span><br>'
                    .'<span>Mã đơn: #'.$o['o_id'].'</span><br>'
                    .'<span style="color:#888;font-size:0.95rem">'.date('d/m/Y H:i', strtotime($o['order_time'])).'</span>'
                    .'</div>';
            }
            // Hiển thị thông báo shipper nhận đơn
            foreach ($shipper_notify as $s) {
                if ($s['type'] === 'shipper') {
                    echo '<div style="margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:1px solid #eee;">'
                        .'<b style="color:#00b14f">Shipper nhận đơn</b> - <span style="color:#f59e42">'.htmlspecialchars($s['shipper_name']).'</span><br>'
                        .'<span>'.nl2br(htmlspecialchars($s['content'])).'</span><br>'
                        .'<span style="color:#888;font-size:0.95rem">'.date('d/m/Y H:i', strtotime($s['created_at'])).'</span>'
                        .'</div>';
                } else {
                    // Các báo cáo khác
                    echo '<div style="margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:1px solid #eee;">'
                        .'<b style="color:#16a34a">'.htmlspecialchars($s['shipper_name']).'</b> - <span style="color:#f59e42">'.htmlspecialchars($s['report_type']).'</span><br>'
                        .'<span>'.nl2br(htmlspecialchars($s['content'])).'</span><br>'
                        .'<span style="color:#888;font-size:0.95rem">'.date('d/m/Y H:i', strtotime($s['created_at'])).'</span>'
                        .'</div>';
                }
            }
            if ($notify_count == 0) {
                echo '<div style="color:#888;">Không có thông báo mới.</div>';
            }
            ?>
            </div>
            <script>
            // Cập nhật số thông báo trên chuông
            document.addEventListener('DOMContentLoaded', function() {
                var notifyBadge = document.getElementById('notifyBadge');
                notifyBadge.textContent = '<?php echo $notify_count; ?>';
                notifyBadge.style.display = <?php echo ($notify_count > 0 ? "'inline-block'" : "'none'"); ?>;
            });
            </script>
        </div>
        <?php
        // Kết nối tới database fastfood
        try {
            $fastfood_pdo = new PDO('mysql:host=localhost;dbname=fastfood;charset=utf8', 'root', '');
            $fastfood_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            $fastfood_pdo = null;
        }
        // Lấy dữ liệu từ fastfood
        $total_dishes = $total_users = $total_orders = 0;
        $avg_rating = 0;
        $revenue_data = [];
        $revenue_labels = [];
        if ($fastfood_pdo) {
            $total_dishes = $fastfood_pdo->query('SELECT COUNT(*) FROM dishes')->fetchColumn();
            $total_users = $fastfood_pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
            $total_orders = $fastfood_pdo->query('SELECT COUNT(*) FROM users_orders')->fetchColumn();
            // Lấy điểm đánh giá trung bình
            $avg_rating = $fastfood_pdo->query('SELECT ROUND(AVG(rating),1) FROM rating')->fetchColumn();
            // Lấy tổng doanh thu theo ngày (7 ngày gần nhất)
            $stmt = $fastfood_pdo->query("SELECT DATE(order_time) as day, SUM(total) as revenue FROM users_orders GROUP BY day ORDER BY day DESC LIMIT 7");
            $revenue_data = [];
            $revenue_labels = [];
            foreach ($stmt as $row) {
                $revenue_labels[] = date('d/m', strtotime($row['day']));
                $revenue_data[] = (float)$row['revenue'];
            }
            // Đảo ngược để hiển thị từ cũ đến mới
            $revenue_labels = array_reverse($revenue_labels);
            $revenue_data = array_reverse($revenue_data);
        }
        ?>
        <div class="dashboard-content">
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card p-4 text-center">
                        <div class="stat-icon"><i class="fa fa-utensils"></i></div>
                        <div class="stat-title">Tổng món ăn</div>
                        <div class="stat-value"><?php echo $total_dishes; ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-4 text-center">
                        <div class="stat-icon"><i class="fa fa-user"></i></div>
                        <div class="stat-title">Tổng người dùng</div>
                        <div class="stat-value"><?php echo $total_users; ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-4 text-center">
                        <div class="stat-icon"><i class="fa fa-shopping-cart"></i></div>
                        <div class="stat-title">Tổng đơn hàng</div>
                        <div class="stat-value"><?php echo $total_orders; ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-4 text-center">
                        <div class="stat-icon"><i class="fa fa-star"></i></div>
                        <div class="stat-title">Đánh giá trung bình</div>
                        <div class="stat-value"><?php echo $avg_rating ? $avg_rating : 'N/A'; ?>/5</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card p-4">
                        <h5 class="mb-3">Biểu đồ doanh thu tuần</h5>
                        <canvas id="revenueChart" height="120"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4">
                        <h5 class="mb-3">Đơn hàng gần đây</h5>
                        <ul class="list-group">
                        <?php
                        if ($fastfood_pdo) {
                            $recent_orders = $fastfood_pdo->query("SELECT o_id, title, total, order_time FROM users_orders ORDER BY order_time DESC LIMIT 5");
                            foreach ($recent_orders as $order) {
                                echo '<li class="list-group-item">#'.$order['o_id'].' - '.$order['title'].' - '.number_format($order['total'],0,'.').'đ</li>';
                            }
                        }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Real-time Chat Bubble for Mng_shop -->
    <div id="ws-chat-bubble" style="position:fixed;bottom:36px;right:36px;z-index:99999;">
      <button id="ws-chat-toggle" style="width:64px;height:64px;border-radius:50%;background:#00b14f;color:#fff;border:none;box-shadow:0 2px 16px rgba(0,0,0,0.13);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.18s;padding:0;">
        <img src="../images/img/iconss.png" alt="Chat" style="width:48px;height:48px;border-radius:50%;box-shadow:0 1px 6px rgba(0,0,0,0.10);background:#fff;">
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
        background-image: url('../images/img/ql2.png');
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
        // Chart.js
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
        // Thông báo tạm thời
        const notifyBell = document.getElementById('notifyBell');
        const notifyPopup = document.getElementById('notifyPopup');
        const notifyBadge = document.getElementById('notifyBadge');
        let notifyTimeout = null;
        // Giả lập có thông báo mới (bạn có thể thay bằng logic thực tế)
        function showNotify(msg) {
            notifyBadge.style.display = 'inline-block';
            notifyPopup.style.display = 'block';
            document.getElementById('notifyContent').innerText = msg;
            clearTimeout(notifyTimeout);
            notifyTimeout = setTimeout(() => {
                notifyBadge.style.display = 'none';
                notifyPopup.style.display = 'none';
            }, 3500);
        }
        // Khi click vào chuông thì hiện popup
        notifyBell.addEventListener('click', function() {
            if (notifyPopup.style.display === 'block') {
                notifyPopup.style.display = 'none';
                notifyBadge.style.display = 'none';
            } else {
                notifyPopup.style.display = 'block';
                notifyBadge.style.display = 'inline-block';
            }
        });
        // Ví dụ: khi nhận đơn thành công, gọi hàm này
        // showNotify('Đơn hàng mới đã được nhận bởi shipper!');
    </script>
</body>
</html>
