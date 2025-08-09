<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../connection/connect.php';
// Kết nối tới database fastfood
try {
    $fastfood_pdo = new PDO('mysql:host=localhost;dbname=fastfood;charset=utf8', 'root', '');
    $fastfood_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    $fastfood_pdo = null;
}
// Xử lý cập nhật trạng thái đơn hàng
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $fastfood_pdo) {
    if (isset($_POST['update_order'])) {
        $o_id = $_POST['o_id'];
        $status = $_POST['status'];
        $stmt = $fastfood_pdo->prepare('UPDATE users_orders SET status=? WHERE o_id=?');
        $stmt->execute([$status, $o_id]);
        $msg = 'Cập nhật trạng thái đơn hàng thành công!';
    }
}
// Lọc theo cửa hàng
$filter_rs_id = $_GET['rs_id'] ?? '';
$where = '';
$params = [];
if ($filter_rs_id) {
    $where = 'WHERE uo.rs_id = ?';
    $params[] = $filter_rs_id;
}
// Lấy danh sách cửa hàng
$restaurants = $fastfood_pdo->query('SELECT rs_id, title FROM restaurant ORDER BY title ASC')->fetchAll(PDO::FETCH_ASSOC);
// Lấy danh sách đơn hàng kèm tên cửa hàng
$sql = "SELECT uo.*, r.title AS res_title
        FROM users_orders uo
        LEFT JOIN restaurant r ON uo.rs_id = r.rs_id
        $where
        ORDER BY uo.o_id DESC";
$stmt = $fastfood_pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xử lý Đơn Hàng</title>
    <link rel="icon" href="../../images/img/shopp.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/index.css">
    <style>
        .order-status { font-weight: bold; }
    </style>
</head>
<body>
    <!-- sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo mb-4">
            <i class="fa-solid fa-store"></i> <span>Restaurant Manager</span>
        </div>
        <ul class="sidebar-menu">
            <li><a href="../index.php"><i class="fa fa-chart-line me-2"></i> <span>Tổng quan</span></a></li>
            <li><a href="shop_manage.php"><i class="fa fa-store me-2"></i> <span>Quản lý Cửa hàng</span></a></li>
            <li class="active"><a href="../Shop_authMng/order_manage.php"><i class="fa fa-money-check-alt me-2"></i> <span>Xử lý Đơn hàng</span></a></li>
            <li><a href="../menuitem/Menu_manage.php"><i class="fa fa-utensils me-2"></i> <span>Quản lý Thực đơn</span></a></li>
            <li><a href="../menuitem/listmenu_manage.php"><i class="fa fa-cube me-2"></i> <span>Quản lý Kho</span></a></li>
            <li><a href="../Shop_authMng/staff_manage.php"><i class="fa fa-user-friends me-2"></i> <span>Quản lý Nhân sự</span></a></li>
            <li><a href="../Listexp/vourcher.php"><i class="fa fa-percent me-2"></i> <span>Khuyến mãi</span></a></li>
            <li><a href="../Listexp/analyst.php"><i class="fa fa-chart-pie me-2"></i> <span>Phân tích & Báo cáo</span></a></li>
            <li><a href="../Listexp/settings.php"><i class="fa fa-cog me-2"></i> <span>Cài đặt</span></a></li>
            <li><a href="../logout.php" class="text-danger"><i class="fa fa-sign-out-alt me-2"></i> <span>Đăng xuất</span></a></li>
        </ul>
    </div>
    <div class="main-content" id="mainContent">
        <div class="topbar">
            <span class="hamburger" id="hamburger"><i class="fa fa-bars"></i></span>
            <span class="title">Xử lý Đơn Hàng</span>
            <span class="user ms-auto">Xin chào, <?php echo $_SESSION['username'] ?? 'Quản lý'; ?>!</span>
        </div>
        <div class="container py-4">
            <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
            <form class="row mb-3" method="get">
                <div class="col-md-4">
                    <select name="rs_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Tất cả cửa hàng --</option>
                        <?php foreach ($restaurants as $res): ?>
                            <option value="<?= $res['rs_id'] ?>" <?= $filter_rs_id == $res['rs_id'] ? 'selected' : '' ?>><?= htmlspecialchars($res['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary"><i class="fa fa-filter"></i> Lọc</button>
                </div>
            </form>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Cửa hàng</th>
                        <th>Món ăn</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Khách</th>
                        <th>Trạng thái</th>
                        <th>Thời gian</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['o_id'] ?></td>
                        <td><?= htmlspecialchars($order['res_title']) ?></td>
                        <td><?= htmlspecialchars($order['title']) ?></td>
                        <td><?= number_format($order['price'], 0, ',', '.') ?> VNĐ</td>
                        <td><?= $order['quantity'] ?></td>
                        <td><?= $order['u_id'] ?></td>
                        <td><span class="order-status <?= $order['status'] ?>"><?= htmlspecialchars($order['status']) ?></span></td>
                        <td><?= $order['order_time'] ?></td>
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewOrderModal<?= $order['o_id'] ?>"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editOrderModal<?= $order['o_id'] ?>"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Modal chi tiết và sửa trạng thái -->
            <?php foreach ($orders as $order): ?>
            <div class="modal fade" id="viewOrderModal<?= $order['o_id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header"><h5 class="modal-title">Chi tiết đơn hàng</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <div class="modal-body">
                            <p><strong>Mã đơn:</strong> <?= $order['o_id'] ?></p>
                            <p><strong>Cửa hàng:</strong> <?= htmlspecialchars($order['res_title']) ?></p>
                            <p><strong>Món ăn:</strong> <?= htmlspecialchars($order['title']) ?> (<?= number_format($order['price'], 0, ',', '.') ?> VNĐ)</p>
                            <p><strong>Số lượng:</strong> <?= $order['quantity'] ?></p>
                            <p><strong>Khách:</strong> <?= $order['u_id'] ?></p>
                            <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
                            <p><strong>Thời gian:</strong> <?= $order['order_time'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editOrderModal<?= $order['o_id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form class="modal-content" method="post">
                        <div class="modal-header"><h5 class="modal-title">Cập nhật trạng thái đơn hàng</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <div class="modal-body">
                            <input type="hidden" name="o_id" value="<?= $order['o_id'] ?>">
                            <div class="mb-2"><label>Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="pending" <?= $order['status']=='pending'?'selected':'' ?>>Chờ xử lý</option>
                                    <option value="in process" <?= $order['status']=='in process'?'selected':'' ?>>Đang xử lý</option>
                                    <option value="closed" <?= $order['status']=='closed'?'selected':'' ?>>Hoàn thành</option>
                                    <option value="rejected" <?= $order['status']=='rejected'?'selected':'' ?>>Từ chối</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer"><button class="btn btn-primary" name="update_order">Lưu</button></div>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
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
    <script>
        // Sidebar toggle logic
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const hamburger = document.getElementById('hamburger');
        hamburger.addEventListener('click', function() {
            sidebar.classList.toggle('hide');
            mainContent.classList.toggle('full');
        });
    </script>
</body>
</html>
