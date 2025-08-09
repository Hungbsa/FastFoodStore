<?php
require_once '../../connection/connect.php';
// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where = '';
if ($search != '') {
    $search = mysqli_real_escape_string($db, $search);
    $where = "WHERE staff_id LIKE '%$search%' OR full_name LIKE '%$search%'";
}

$sql = "SELECT * FROM staff $where ORDER BY staff_id DESC";
$result = mysqli_query($db, $sql);
// Xử lý thêm/sửa/xoá
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        // Thêm nhân sự
        $user_id = $_POST['user_id'];
        $full_name = $_POST['full_name'];
        $position = $_POST['position'];
        $hire_date = $_POST['hire_date'];
        $salary = $_POST['salary'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $date_of_birth = $_POST['date_of_birth'];
        $id_card_number = $_POST['id_card_number'];
        $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
        $sql_add = "INSERT INTO staff (user_id, full_name, position, hire_date, salary, phone, email, address, date_of_birth, id_card_number, status) VALUES ('$user_id', '$full_name', '$position', '$hire_date', '$salary', '$phone', '$email', '$address', '$date_of_birth', '$id_card_number', '$status')";
        mysqli_query($db, $sql_add);
        header('Location: staff_manage.php');
        exit();
    }
    if (isset($_POST['edit'])) {
        // Sửa nhân sự
        $staff_id = $_POST['staff_id'];
        $user_id = $_POST['user_id'];
        $full_name = $_POST['full_name'];
        $position = $_POST['position'];
        $hire_date = $_POST['hire_date'];
        $salary = $_POST['salary'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $date_of_birth = $_POST['date_of_birth'];
        $id_card_number = $_POST['id_card_number'];
        $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
        $sql_edit = "UPDATE staff SET user_id='$user_id', full_name='$full_name', position='$position', hire_date='$hire_date', salary='$salary', phone='$phone', email='$email', address='$address', date_of_birth='$date_of_birth', id_card_number='$id_card_number', status='$status' WHERE staff_id='$staff_id'";
        mysqli_query($db, $sql_edit);
        header('Location: staff_manage.php');
        exit();
    }
}
if (isset($_GET['delete'])) {
    $staff_id = $_GET['delete'];
    $sql_del = "DELETE FROM staff WHERE staff_id='$staff_id'";
    mysqli_query($db, $sql_del);
    header('Location: staff_manage.php');
    exit();
}
// Lấy thông tin nhân sự để sửa
$edit_staff = null;
if (isset($_GET['edit'])) {
    $staff_id = $_GET['edit'];
    $sql_get = "SELECT * FROM staff WHERE staff_id='$staff_id'";
    $edit_staff = mysqli_fetch_assoc(mysqli_query($db, $sql_get));
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhân sự</title>
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
            <li class="active"><a href="../Shop_authMng/staff_manage.php"><i class="fa fa-user-friends me-2"></i> <span>Quản lý Nhân sự</span></a></li>
            <li><a href="../Listexp/vourcher.php"><i class="fa fa-percent me-2"></i> <span>Khuyến mãi</span></a></li>
            <li><a href="../Listexp/analyst.php"><i class="fa fa-chart-pie me-2"></i> <span>Phân tích & Báo cáo</span></a></li>
            <li><a href="../Listexp/settings.php"><i class="fa fa-cog me-2"></i> <span>Cài đặt</span></a></li>
            <li><a href="../logout.php" class="text-danger"><i class="fa fa-sign-out-alt me-2"></i> <span>Đăng xuất</span></a></li>
        </ul>
    </div>
<div class="main-content" id="mainContent">
        <div class="topbar">
            <span class="hamburger" id="hamburger"><i class="fa fa-bars"></i></span>
            <span class="title">Quản lý Nhân sự</span>
            <span class="user ms-auto">Xin chào, <?php echo $_SESSION['username'] ?? 'Quản lý'; ?>!</span>
        </div>
    <div class="container">
        <!-- Form tìm kiếm -->
        <div class="form-inline mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên hoặc ID" value="<?= htmlspecialchars($search) ?>">
            <a href="staff_manage.php" class="btn btn-secondary ms-2">Reset</a>
        </div>
        <!-- Form thêm/sửa nhân sự -->
        <form method="post" class="mb-4">
            <?php if ($edit_staff): ?>
                <input type="hidden" name="staff_id" value="<?= $edit_staff['staff_id'] ?>">
            <?php endif; ?>
            <?php $default_status = $edit_staff ? $edit_staff['status'] : 1; ?>
            <div class="row">
                <div class="col-md-3"><input type="text" name="user_id" class="form-control" placeholder="User ID" value="<?= $edit_staff ? $edit_staff['user_id'] : '' ?>" required></div>
                <div class="col-md-3"><input type="text" name="full_name" class="form-control" placeholder="Họ tên" value="<?= $edit_staff ? $edit_staff['full_name'] : '' ?>" required></div>
                <div class="col-md-3"><input type="text" name="position" class="form-control" placeholder="Chức vụ" value="<?= $edit_staff ? $edit_staff['position'] : '' ?>" required></div>
                <div class="col-md-3"><input type="date" name="hire_date" class="form-control" placeholder="Ngày vào làm" value="<?= $edit_staff ? $edit_staff['hire_date'] : '' ?>" required></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-2"><input type="number" name="salary" class="form-control" placeholder="Lương / 1h" value="<?= $edit_staff ? $edit_staff['salary'] : '' ?>" required></div>
                <div class="col-md-2"><input type="text" name="phone" class="form-control" placeholder="SĐT" value="<?= $edit_staff ? $edit_staff['phone'] : '' ?>" required></div>
                <div class="col-md-2"><input type="email" name="email" class="form-control" placeholder="Email" value="<?= $edit_staff ? $edit_staff['email'] : '' ?>"></div>
                <div class="col-md-2"><input type="text" name="address" class="form-control" placeholder="Địa chỉ" value="<?= $edit_staff ? $edit_staff['address'] : '' ?>"></div>
                <div class="col-md-2"><input type="date" name="date_of_birth" class="form-control" placeholder="Ngày sinh" value="<?= $edit_staff ? $edit_staff['date_of_birth'] : '' ?>"></div>
                <div class="col-md-2"><input type="text" name="id_card_number" class="form-control" placeholder="CMND/CCCD" value="<?= $edit_staff ? $edit_staff['id_card_number'] : '' ?>"></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="1" <?= $default_status == 1 ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="0" <?= $default_status == 0 ? 'selected' : '' ?>>Ngừng hoạt động</option>
                    </select>
                </div>
                <div class="col-md-10">
                    <?php if ($edit_staff): ?>
                        <button type="submit" name="edit" class="btn btn-warning">Cập nhật</button>
                        <a href="staff_manage.php" class="btn btn-secondary">Huỷ</a>
                    <?php else: ?>
                        <button type="submit" name="add" class="btn btn-success">Thêm mới</button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        <!-- Bảng danh sách nhân sự -->
        <table class="table table-bordered table-hover" id="staffTable">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Họ tên</th>
                    <th>Chức vụ</th>
                    <th>Ngày vào làm</th>
                    <th>Lương</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Ngày sinh</th>
                    <th>CMND/CCCD</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['staff_id'] ?></td>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= $row['full_name'] ?></td>
                    <td><?= $row['position'] ?></td>
                    <td><?= $row['hire_date'] ?></td>
                    <td><?= $row['salary'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['address'] ?></td>
                    <td><?= $row['date_of_birth'] ?></td>
                    <td><?= $row['id_card_number'] ?></td>
                    <td><?= $row['status'] == 1 ? 'Hoạt động' : 'Ngừng hoạt động' ?></td>
                    <td>
                        <a href="staff_manage.php?edit=<?= $row['staff_id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="staff_manage.php?delete=<?= $row['staff_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xoá?')">Xoá</a>
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

        // Tìm kiếm tự động
        document.getElementById('searchInput').addEventListener('input', function() {
            const value = this.value.toLowerCase();
            const rows = document.querySelectorAll('#staffTable tbody tr');
            rows.forEach(row => {
                const userId = row.children[1].textContent.toLowerCase();
                const name = row.children[2].textContent.toLowerCase();
                if (userId.includes(value) || name.includes(value)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
