<?php
require_once '../../connection/connect.php';
// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where = '';
if ($search != '') {
    $search = mysqli_real_escape_string($db, $search);
    $where = "WHERE code LIKE '%$search%'";
}
// Lấy danh sách mã khuyến mãi
$sql = "SELECT * FROM coupons $where ORDER BY id DESC";
$result = mysqli_query($db, $sql);
// Xử lý thêm/sửa/xoá
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $code = $_POST['code'];
        $discount_type = $_POST['discount_type'];
        $discount_value = $_POST['discount_value'];
        $max_discount = $_POST['max_discount'] !== '' ? $_POST['max_discount'] : 'NULL';
        $expiry_date = $_POST['expiry_date'] !== '' ? "'" . $_POST['expiry_date'] . "'" : 'NULL';
        $usage_limit = $_POST['usage_limit'] !== '' ? $_POST['usage_limit'] : 'NULL';
        $active = isset($_POST['active']) ? (int)$_POST['active'] : 1;
        $sql_add = "INSERT INTO coupons (code, discount_type, discount_value, max_discount, expiry_date, usage_limit, active) VALUES ('$code', '$discount_type', '$discount_value', $max_discount, $expiry_date, $usage_limit, $active)";
        mysqli_query($db, $sql_add);
        header('Location: vourcher.php');
        exit();
    }
    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $code = $_POST['code'];
        $discount_type = $_POST['discount_type'];
        $discount_value = $_POST['discount_value'];
        $max_discount = $_POST['max_discount'] !== '' ? $_POST['max_discount'] : 'NULL';
        $expiry_date = $_POST['expiry_date'] !== '' ? "'" . $_POST['expiry_date'] . "'" : 'NULL';
        $usage_limit = $_POST['usage_limit'] !== '' ? $_POST['usage_limit'] : 'NULL';
        $active = isset($_POST['active']) ? (int)$_POST['active'] : 1;
        $sql_edit = "UPDATE coupons SET code='$code', discount_type='$discount_type', discount_value='$discount_value', max_discount=$max_discount, expiry_date=$expiry_date, usage_limit=$usage_limit, active=$active WHERE id='$id'";
        mysqli_query($db, $sql_edit);
        header('Location: vourcher.php');
        exit();
    }
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql_del = "DELETE FROM coupons WHERE id='$id'";
    mysqli_query($db, $sql_del);
    header('Location: vourcher.php');
    exit();
}
// Lấy thông tin mã để sửa
$edit_coupon = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql_get = "SELECT * FROM coupons WHERE id='$id'";
    $edit_coupon = mysqli_fetch_assoc(mysqli_query($db, $sql_get));
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý vourcher</title>
    <link rel="icon" href="../../images/img/shopp.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/index.css">
    <style>
        .container { max-width: 1200px; margin: 30px auto; }
        .table th, .table td { vertical-align: middle; }
        .form-inline input { margin-right: 10px; }
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
            <li class="active"><a href="../Listexp/vourcher.php"><i class="fa fa-percent me-2"></i> <span>Khuyến mãi</span></a></li>
            <li><a href="../Listexp/analyst.php"><i class="fa fa-chart-pie me-2"></i> <span>Phân tích & Báo cáo</span></a></li>
            <li><a href="../Listexp/settings.php"><i class="fa fa-cog me-2"></i> <span>Cài đặt</span></a></li>
            <li><a href="../logout.php" class="text-danger"><i class="fa fa-sign-out-alt me-2"></i> <span>Đăng xuất</span></a></li>
        </ul>
    </div>
<div class="main-content" id="mainContent">
        <div class="topbar">
            <span class="hamburger" id="hamburger"><i class="fa fa-bars"></i></span>
            <span class="title">Quản lý Vourcher</span>
            <span class="user ms-auto">Xin chào, <?php echo $_SESSION['username'] ?? 'Quản lý'; ?>!</span>
        </div>
    <div class="container">
        <!-- Form tìm kiếm -->
        <div class="form-inline mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên mã" value="<?= htmlspecialchars($search) ?>">
            <a href="vourcher.php" class="btn btn-secondary ms-2">Reset</a>
        </div>
        <!-- Form thêm/sửa mã -->
        <form method="post" class="mb-4">
            <?php if ($edit_coupon): ?>
                <input type="hidden" name="id" value="<?= $edit_coupon['id'] ?>">
            <?php endif; ?>
            <div class="row">
                <div class="col-md-2"><input type="text" name="code" class="form-control" placeholder="Tên mã" value="<?= $edit_coupon ? $edit_coupon['code'] : '' ?>" required></div>
                <div class="col-md-2">
                    <select name="discount_type" class="form-control">
                        <option value="percentage" <?= $edit_coupon && $edit_coupon['discount_type'] == 'percentage' ? 'selected' : '' ?>>Phần trăm</option>
                        <option value="fixed" <?= $edit_coupon && $edit_coupon['discount_type'] == 'fixed' ? 'selected' : '' ?>>Giá trị cố định</option>
                    </select>
                </div>
                <div class="col-md-2"><input type="number" step="0.01" name="discount_value" class="form-control" placeholder="Giá trị giảm" value="<?= $edit_coupon ? $edit_coupon['discount_value'] : '' ?>" required></div>
                <div class="col-md-2"><input type="number" step="0.01" name="max_discount" class="form-control" placeholder="Giảm tối đa" value="<?= $edit_coupon ? $edit_coupon['max_discount'] : '' ?>"></div>
                <div class="col-md-2"><input type="date" name="expiry_date" class="form-control" placeholder="Ngày hết hạn" value="<?= $edit_coupon ? ($edit_coupon['expiry_date'] ? date('Y-m-d', strtotime($edit_coupon['expiry_date'])) : '') : '' ?>"></div>
                <div class="col-md-2"><input type="number" name="usage_limit" class="form-control" placeholder="Giới hạn sử dụng" value="<?= $edit_coupon ? $edit_coupon['usage_limit'] : '' ?>"></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-2">
                    <select name="active" class="form-control">
                        <option value="1" <?= !$edit_coupon || $edit_coupon['active'] == 1 ? 'selected' : '' ?>>Kích hoạt</option>
                        <option value="0" <?= $edit_coupon && $edit_coupon['active'] == 0 ? 'selected' : '' ?>>Ngừng</option>
                    </select>
                </div>
                <div class="col-md-10">
                    <?php if ($edit_coupon): ?>
                        <button type="submit" name="edit" class="btn btn-warning">Cập nhật</button>
                        <a href="vourcher.php" class="btn btn-secondary">Huỷ</a>
                    <?php else: ?>
                        <button type="submit" name="add" class="btn btn-success">Thêm mới</button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        <!-- Bảng danh sách mã khuyến mãi -->
        <table class="table table-bordered table-hover" id="couponTable">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên mã</th>
                    <th>Loại giảm</th>
                    <th>Giá trị giảm</th>
                    <th>Giảm tối đa</th>
                    <th>Ngày hết hạn</th>
                    <th>Giới hạn sử dụng</th>
                    <th>Đã dùng</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['code'] ?></td>
                    <td><?= $row['discount_type'] == 'percentage' ? 'Phần trăm' : 'Giá trị cố định' ?></td>
                    <td><?= $row['discount_type'] == 'fixed' ? (number_format($row['discount_value'],0,',','.') . ' VNĐ') : ($row['discount_value'] . '%') ?></td>
                    <td><?= $row['max_discount'] !== null ? (number_format($row['max_discount'],0,',','.') . ' VNĐ') : '-' ?></td>
                    <td><?= $row['expiry_date'] ? date('d/m/Y', strtotime($row['expiry_date'])) : '-' ?></td>
                    <td><?= $row['usage_limit'] !== null ? $row['usage_limit'] : '-' ?></td>
                    <td><?= $row['times_used'] ?></td>
                    <td><?= $row['active'] == 1 ? 'Kích hoạt' : 'Ngừng' ?></td>
                    <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                    <td>
                        <a href="vourcher.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="vourcher.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xoá?')">Xoá</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
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
    // Tìm kiếm tự động theo tên mã
    document.getElementById('searchInput').addEventListener('input', function() {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('#couponTable tbody tr');
        rows.forEach(row => {
            const code = row.children[1].textContent.toLowerCase();
            if (code.includes(value)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>
