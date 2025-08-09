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
// Xử lý thêm, sửa, xoá món ăn
// Xử lý thêm category
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $fastfood_pdo) {
    // Xoá danh mục
    if (isset($_POST['delete_category'])) {
        $cat_id = intval($_POST['cat_id']);
        // Kiểm tra có món ăn thuộc danh mục không
        $count = $fastfood_pdo->query("SELECT COUNT(*) FROM dishes WHERE dish_category_id = $cat_id")->fetchColumn();
        if ($count > 0) {
            $msg = 'Không thể xoá danh mục khi còn món ăn!';
        } else {
            $stmt = $fastfood_pdo->prepare('DELETE FROM dish_categories WHERE cat_id = ?');
            $stmt->execute([$cat_id]);
            $msg = 'Xoá danh mục thành công!';
        }
    }
    // Thêm danh mục
    if (isset($_POST['add_category'])) {
        $cat_name = trim($_POST['cat_name']);
        if ($cat_name !== '') {
            $stmt = $fastfood_pdo->prepare('INSERT INTO dish_categories (cat_name) VALUES (?)');
            $stmt->execute([$cat_name]);
            $msg = 'Thêm danh mục thành công!';
        } else {
            $msg = 'Tên danh mục không được để trống!';
        }
    }
    // Thêm món
    if (isset($_POST['add_dish'])) {
        $title = $_POST['title'];
        $slogan = $_POST['slogan'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $calories = $_POST['calories'];
        $dish_category_id = $_POST['dish_category_id'];
        $rs_id = isset($_POST['rs_id']) ? intval($_POST['rs_id']) : 0;
        $img = '';
        if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
            $upload_dir1 = '../admin/Res_img/dishes/';
            $upload_dir2 = '../../admin/Res_img/dishes/';
            if (!is_dir($upload_dir1)) {
                mkdir($upload_dir1, 0777, true);
            }
            if (!is_dir($upload_dir2)) {
                mkdir($upload_dir2, 0777, true);
            }
            $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            $img_name = uniqid('dish_', true) . '.' . $ext;
            $target_file1 = $upload_dir1 . $img_name;
            $target_file2 = $upload_dir2 . $img_name;
            $success1 = move_uploaded_file($_FILES['img']['tmp_name'], $target_file1);
            $success2 = false;
            if ($success1) {
                // Copy sang thư mục ngoài
                $success2 = copy($target_file1, $target_file2);
                $img = $img_name;
            } else {
                $msg = 'Lỗi upload ảnh!';
            }
        }
        $stmt = $fastfood_pdo->prepare('INSERT INTO dishes (title, slogan, description, price, calories, img, dish_category_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt = $fastfood_pdo->prepare('INSERT INTO dishes (rs_id, title, slogan, description, price, calories, img, dish_category_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$rs_id, $title, $slogan, $description, $price, $calories, $img, $dish_category_id]);
        $msg = 'Thêm món ăn thành công!';
    }
    // Sửa món
    if (isset($_POST['edit_dish'])) {
        $d_id = $_POST['d_id'];
        $title = $_POST['title'];
        $slogan = $_POST['slogan'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $calories = $_POST['calories'];
        $dish_category_id = $_POST['dish_category_id'];
        $img = $_POST['old_img'];
        if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
            $upload_dir1 = '../admin/Res_img/dishes/';
            $upload_dir2 = '../../admin/Res_img/dishes/';
            if (!is_dir($upload_dir1)) {
                mkdir($upload_dir1, 0777, true);
            }
            if (!is_dir($upload_dir2)) {
                mkdir($upload_dir2, 0777, true);
            }
            $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            $img_name = uniqid('dish_', true) . '.' . $ext;
            $target_file1 = $upload_dir1 . $img_name;
            $target_file2 = $upload_dir2 . $img_name;
            $success1 = move_uploaded_file($_FILES['img']['tmp_name'], $target_file1);
            $success2 = false;
            if ($success1) {
                $success2 = copy($target_file1, $target_file2);
                $img = $img_name;
            }
        }
        $stmt = $fastfood_pdo->prepare('UPDATE dishes SET title=?, slogan=?, description=?, price=?, calories=?, img=?, dish_category_id=? WHERE d_id=?');
        $stmt->execute([$title, $slogan, $description, $price, $calories, $img, $dish_category_id, $d_id]);
        $msg = 'Cập nhật món ăn thành công!';
    }
    // Xoá món
    if (isset($_POST['delete_dish'])) {
        $d_id = $_POST['d_id'];
        $stmt = $fastfood_pdo->prepare('DELETE FROM dishes WHERE d_id=?');
        $stmt->execute([$d_id]);
        $msg = 'Xoá món ăn thành công!';
    }
}
// Lấy danh sách category từ bảng dish_categories
$categories = $fastfood_pdo ? $fastfood_pdo->query('SELECT * FROM dish_categories ORDER BY cat_id ASC')->fetchAll(PDO::FETCH_ASSOC) : [];
// Lấy danh sách món ăn, phân loại theo category_id
$dishes_by_cat = [];
if ($fastfood_pdo) {
    $dishes = $fastfood_pdo->query('SELECT * FROM dishes ORDER BY d_id DESC')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($dishes as $dish) {
        $cat_id = isset($dish['dish_category_id']) ? $dish['dish_category_id'] : 0;
        if (!isset($dishes_by_cat[$cat_id])) $dishes_by_cat[$cat_id] = [];
        $dishes_by_cat[$cat_id][] = $dish;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Thực đơn</title>
    <link rel="icon" href="../../images/img/shopp.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/index.css">
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
            <li class="active"><a href="../menuitem/Menu_manage.php"><i class="fa fa-utensils me-2"></i> <span>Quản lý Thực đơn</span></a></li>
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
            <span class="title">Quản lý Thực đơn</span>
            <span class="user ms-auto">Xin chào, <?php echo $_SESSION['username'] ?? 'Quản lý'; ?>!</span>
        </div>
        <div class="container py-4">
            <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
            <!-- Form thêm danh mục mới -->
            <div class="mb-4">
                <form class="row g-2" method="post" style="max-width:420px;">
                    <div class="col">
                        <input type="text" name="cat_name" class="form-control" placeholder="Tên danh mục mới" required>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-success" name="add_category"><i class="fa fa-plus"></i> Thêm danh mục</button>
                    </div>
                </form>
            </div>
            <div class="accordion" id="menuAccordion">
            <?php foreach ($categories as $cat): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header d-flex align-items-center justify-content-between" id="heading<?= $cat['cat_id'] ?>">
                        <div style="flex:1;">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $cat['cat_id'] ?>" aria-expanded="false" aria-controls="collapse<?= $cat['cat_id'] ?>">
                                <?= htmlspecialchars($cat['cat_name']) ?>
                            </button>
                        </div>
                        <form method="post" style="margin-left:8px;" onsubmit="return confirm('Xoá danh mục này? Danh mục phải rỗng món ăn!');">
                            <input type="hidden" name="cat_id" value="<?= $cat['cat_id'] ?>">
                            <button class="btn btn-danger btn-sm" name="delete_category" title="Xoá danh mục"><i class="fa fa-trash"></i></button>
                        </form>
                    </h2>
                    <div id="collapse<?= $cat['cat_id'] ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $cat['cat_id'] ?>" data-bs-parent="#menuAccordion">
                        <div class="accordion-body">
                            <!-- Form thêm món cho từng category -->
                            <form class="row g-3 mb-3" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="dish_category_id" value="<?= $cat['cat_id'] ?>">
                                <div class="col-md-2">
                                    <input type="number" name="rs_id" class="form-control" placeholder="ID cửa hàng" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="title" class="form-control" placeholder="Tên món ăn" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="slogan" class="form-control" placeholder="Slogan" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="description" class="form-control" placeholder="Mô tả" required>
                                </div>
                                <div class="col-md-1">
                                    <input type="number" name="price" class="form-control" placeholder="Giá" required min="0">
                                </div>
                                <div class="col-md-1">
                                    <input type="number" name="calories" class="form-control" placeholder="Calories" required min="0">
                                </div>
                                <div class="col-md-2">
                                    <input type="file" name="img" class="form-control" accept="image/*" required>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <button class="btn btn-primary" name="add_dish"><i class="fa fa-plus"></i> Thêm món</button>
                                </div>
                            </form>
                            <!-- Bảng món ăn của category này -->
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên món</th>
                                        <th>Giá</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($dishes_by_cat[$cat['cat_id']] ?? [] as $dish): ?>
                                    <tr>
                                        <td><?= $dish['d_id'] ?></td>
                                        <td><?= htmlspecialchars($dish['title']) ?></td>
                                        <td><?= number_format($dish['price'], 0, ',', '.') ?> VNĐ</td>
                                        <td>
                                            <!-- Sửa -->
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDishModal<?= $dish['d_id'] ?>"><i class="fa fa-edit"></i></button>
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="d_id" value="<?= $dish['d_id'] ?>">
                                                <button class="btn btn-danger btn-sm" name="delete_dish" onclick="return confirm('Xoá món này?');"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <!-- Đưa modal ra ngoài bảng -->
                        <?php foreach ($dishes_by_cat[$cat['cat_id']] ?? [] as $dish): ?>
                            <div class="modal fade" id="editDishModal<?= $dish['d_id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                                <form class="modal-content" method="post" enctype="multipart/form-data">
                                        <div class="modal-header"><h5 class="modal-title">Sửa món ăn</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">
                                            <input type="hidden" name="d_id" value="<?= $dish['d_id'] ?>">
                                            <input type="hidden" name="dish_category_id" value="<?= $cat['cat_id'] ?>">
                                            <div class="mb-2"><label>Tên món</label><input type="text" name="title" class="form-control" value="<?= htmlspecialchars($dish['title']) ?>" required></div>
                                            <div class="mb-2"><label>Slogan</label><input type="text" name="slogan" class="form-control" value="<?= htmlspecialchars($dish['slogan']) ?>" required></div>
                                            <div class="mb-2"><label>Mô tả</label><input type="text" name="description" class="form-control" value="<?= htmlspecialchars($dish['description']) ?>" required></div>
                                            <div class="mb-2"><label>Giá</label><input type="number" name="price" class="form-control" value="<?= $dish['price'] ?>" required min="0"></div>
                                            <div class="mb-2"><label>Calories</label><input type="number" name="calories" class="form-control" value="<?= $dish['calories'] ?>" required min="0"></div>
                                                    <div class="mb-2"><label>Ảnh</label><input type="file" name="img" class="form-control" accept="image/*">
                                                    <input type="hidden" name="old_img" value="<?= htmlspecialchars($dish['img']) ?>"></div>
                                        </div>
                                        <div class="modal-footer"><button class="btn btn-primary" name="edit_dish">Lưu</button></div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
