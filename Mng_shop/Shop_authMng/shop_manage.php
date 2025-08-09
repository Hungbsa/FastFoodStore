<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../connection/connect.php'; // Kết nối database
// Kết nối tới database fastfood
try {
    $fastfood_pdo = new PDO('mysql:host=localhost;dbname=fastfood;charset=utf8', 'root', '');
    $fastfood_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    $fastfood_pdo = null;
}
// Xử lý CRUD
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $fastfood_pdo) {
    // Thêm mới
    if (isset($_POST['add_shop'])) {
        $title = $_POST['name'];
        $address = $_POST['address'];
        $status = $_POST['status'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $url = $_POST['url'];
        $o_hr = $_POST['o_hr'];
        $c_hr = $_POST['c_hr'];
        $o_days = $_POST['o_days'];
        $c_id = $_POST['c_id'];
        $image = '';
        // Ưu tiên lấy URL ảnh nếu có
        if (!empty($_POST['image_url'])) {
            $image = trim($_POST['image_url']);
        } elseif (!empty($_FILES['logo']['name'])) {
            $dir = '../admin/Res_img/';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $dir . basename($_FILES['logo']['name']))) {
                $image = basename($_FILES['logo']['name']);
            }
        }
        $date = date('Y-m-d H:i:s');
        $stmt = $fastfood_pdo->prepare('INSERT INTO restaurant (c_id, title, email, phone, url, o_hr, c_hr, o_days, address, image, date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$c_id, $title, $email, $phone, $url, $o_hr, $c_hr, $o_days, $address, $image, $date, $status]);
        $msg = 'Thêm cửa hàng thành công!';
    }
    // Sửa
    if (isset($_POST['edit_shop'])) {
        $id = $_POST['id'];
        $title = $_POST['name'];
        $address = $_POST['address'];
        $status = $_POST['status'];
        $image = $_POST['old_logo'];
        if (!empty($_FILES['logo']['name'])) {
            $target = 'images/shops/' . basename($_FILES['logo']['name']);
            if (move_uploaded_file($_FILES['logo']['tmp_name'], '../Mng_shop/' . $target)) {
                $image = basename($_FILES['logo']['name']);
            }
        }
        $stmt = $fastfood_pdo->prepare('UPDATE restaurant SET title=?, address=?, status=?, image=? WHERE rs_id=?');
        $stmt->execute([$title, $address, $status, $image, $id]);
        $msg = 'Cập nhật cửa hàng thành công!';
    }
    // Xóa
    if (isset($_POST['delete_shop'])) {
        $id = $_POST['id'];
        $stmt = $fastfood_pdo->prepare('DELETE FROM restaurant WHERE rs_id=?');
        $stmt->execute([$id]);
        $msg = 'Xóa cửa hàng thành công!';
    }
}
// Tìm kiếm
$search = $_GET['search'] ?? '';
if ($search) {
    $shops = $fastfood_pdo->prepare('SELECT * FROM restaurant WHERE title LIKE ? OR address LIKE ? ORDER BY rs_id DESC');
    $shops->execute(['%' . $search . '%', '%' . $search . '%']);
} else {
    $shops = $fastfood_pdo->query('SELECT * FROM restaurant ORDER BY rs_id DESC');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Cửa hàng</title>
    <link rel="icon" href="../../images/img/shopp.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/index.css">
    <style>
        .shop-logo { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
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
            <li class="active"><a href="../Shop_authMng/shop_manage.php"><i class="fa fa-store me-2"></i> <span>Quản lý Cửa hàng</span></a></li>
            <li><a href="../Shop_authMng/order_manage.php"><i class="fa fa-money-check-alt me-2"></i> <span>Xử lý Đơn hàng</span></a></li>
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
            <span class="title">Quản lý Cửa hàng</span>
            <span class="user ms-auto">Xin chào, <?php echo $_SESSION['username'] ?? 'Quản lý'; ?>!</span>
        </div>
    <!-- sidebar end -->
        <div class="container py-4">
            <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
            <form class="row mb-3" method="get">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên hoặc địa chỉ..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                </div>
            </form>
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addShopModal"><i class="fa fa-plus"></i> Thêm cửa hàng</button>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Logo</th>
                        <th>Tên cửa hàng</th>
                        <th>Địa chỉ</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($shops as $shop): ?>
                    <tr>
                        <td>
                            <img src="<?= !empty($shop['image']) ? '../admin/Res_img/' . $shop['image'] : 'images/shops/default.png' ?>" class="shop-logo" alt="Logo">
                        </td>
                        <td><?= !empty($shop['title']) ? htmlspecialchars($shop['title']) : '<span class="text-muted">(Chưa có tên)</span>' ?></td>
                        <td><?= !empty($shop['address']) ? htmlspecialchars($shop['address']) : '<span class="text-muted">(Chưa có địa chỉ)</span>' ?></td>
                        <td>
                            <?php
                            if (isset($shop['status'])) {
                                if ($shop['status'] == 1) {
                                    echo '<span class="badge bg-success"><i class="fa fa-check-circle"></i> Hoạt động</span>';
                                } else {
                                    echo '<span class="badge bg-secondary"><i class="fa fa-times-circle"></i> Đóng</span>';
                                }
                            } else {
                                echo '<span class="badge bg-warning text-dark">Không xác định</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm" title="Xem chi tiết" data-bs-toggle="modal" data-bs-target="#viewShopModal<?= $shop['rs_id'] ?>"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-warning btn-sm" title="Sửa" data-bs-toggle="modal" data-bs-target="#editShopModal<?= $shop['rs_id'] ?>"><i class="fa fa-edit"></i></button>
                            <form method="post" style="display:inline" onsubmit="return confirm('Xác nhận xóa?');">
                                <input type="hidden" name="id" value="<?= $shop['rs_id'] ?>">
                                <button class="btn btn-danger btn-sm" title="Xóa" name="delete_shop"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Các modal chi tiết/sửa -->
            <?php
            // Lấy lại danh sách shop để render modal, tránh lỗi biến $shop bị ghi đè
            $shops_modal = $fastfood_pdo->query('SELECT * FROM restaurant ORDER BY rs_id DESC');
            foreach ($shops_modal as $shop): ?>
            <!-- Modal xem chi tiết -->
            <div class="modal fade" id="viewShopModal<?= $shop['rs_id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header"><h5 class="modal-title">Chi tiết cửa hàng</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <div class="modal-body">
                            <img src="<?= !empty($shop['image']) ? '../admin/Res_img/' . $shop['image'] : 'images/shops/default.png' ?>" class="shop-logo mb-3">
                            <p><strong>ID cửa hàng:</strong> <?= $shop['rs_id'] ?></p>
                            <p><strong>Danh mục cửa hàng:</strong> <?= $shop['c_id'] ?></p>
                            <p><strong>Tên:</strong> <?= htmlspecialchars($shop['title']) ?></p>
                            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($shop['address']) ?></p>
                            <p><strong>Trạng thái:</strong> <?= $shop['status'] ? 'Hoạt động' : 'Đóng' ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal sửa -->
            <div class="modal fade" id="editShopModal<?= $shop['rs_id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form class="modal-content" method="post" enctype="multipart/form-data">
                        <div class="modal-header"><h5 class="modal-title">Sửa cửa hàng</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $shop['rs_id'] ?>">
                            <input type="hidden" name="old_logo" value="<?= $shop['image'] ?>">
                            <div class="mb-2"><label>Tên cửa hàng</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($shop['title']) ?>" required></div>
                            <div class="mb-2"><label>Địa chỉ</label><input type="text" name="address" class="form-control" value="<?= htmlspecialchars($shop['address']) ?>" required></div>
                            <div class="mb-2"><label>Trạng thái</label><select name="status" class="form-select"><option value="1" <?= $shop['status'] ? 'selected' : '' ?>>Hoạt động</option><option value="0" <?= !$shop['status'] ? 'selected' : '' ?>>Đóng</option></select></div>
                            <div class="mb-2">
                                <label>Logo hiện tại</label><br>
                                <img src="<?= !empty($shop['image']) ? '../admin/Res_img/' . $shop['image'] : 'images/shops/default.png' ?>" class="shop-logo mb-2" alt="Logo">
                                <input type="file" name="logo" class="form-control mt-2">
                            </div>
                        </div>
                        <div class="modal-footer"><button class="btn btn-primary" name="edit_shop">Lưu</button></div>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="modal fade" id="addShopModal" tabindex="-1">
                <div class="modal-dialog">
                    <form class="modal-content" method="post" enctype="multipart/form-data">
                        <div class="modal-header"><h5 class="modal-title">Thêm cửa hàng</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <div class="modal-body">
                            <div class="mb-2"><label>Mã danh mục</label><input type="number" name="c_id" class="form-control" required></div>
                            <div class="mb-2"><label>Tên cửa hàng</label><input type="text" name="name" class="form-control" required></div>
                            <div class="mb-2"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                            <div class="mb-2"><label>Số điện thoại</label><input type="text" name="phone" class="form-control" required></div>
                            <div class="mb-2"><label>Website</label><input type="text" name="url" class="form-control"></div>
                            <div class="mb-2"><label>Giờ mở cửa </label><input type="time" name="o_hr" class="form-control" required></div>
                            <div class="mb-2"><label>Giờ đóng cửa</label><input type="time" name="c_hr" class="form-control" required></div>
                            <div class="mb-2"><label>Ngày mở cửa</label><input type="text" name="o_days" class="form-control" placeholder="mon-sat" required></div>
                            <div class="mb-2"><label>Địa chỉ</label><input type="text" name="address" class="form-control" required></div>
                            <div class="mb-2"><label>Trạng thái</label><select name="status" class="form-select"><option value="1">Hoạt động</option><option value="0">Đóng</option></select></div>
                            <div class="mb-2"><label>Logo (upload file)</label><input type="file" name="logo" class="form-control"></div>
                            <div class="mb-2"><label>Hoặc nhập URL ảnh</label><input type="text" name="image_url" class="form-control" placeholder="https://..."></div>
                        </div>
                        <div class="modal-footer"><button class="btn btn-success" name="add_shop">Thêm</button></div>
                    </form>
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
