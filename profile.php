<?php
include("connection/connect.php");
session_start(); 
if(empty($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

// Lấy thông tin user
$uid = $_SESSION["user_id"];
$userq = mysqli_query($db, "SELECT * FROM users WHERE u_id='$uid' LIMIT 1");
$udata = mysqli_fetch_assoc($userq);
$username = $udata ? $udata['username'] : 'User';
$email = $udata ? $udata['email'] : '';
$address = $udata ? $udata['address'] : '';
$picture = $udata && isset($udata['picture']) ? $udata['picture'] : '';
$firstChar = strtoupper(substr($username,0,1));

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_account'])) {
        // Xóa tài khoản
        mysqli_query($db, "DELETE FROM users WHERE u_id='$uid'");
        session_destroy();
        header("Location: registration.php");
        exit();
    } else {
        $new_username = mysqli_real_escape_string($db, $_POST['username']);
        $new_email = mysqli_real_escape_string($db, $_POST['email']);
        $new_address = mysqli_real_escape_string($db, $_POST['address']);
        $picture_path = $picture;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $target = 'images/avatars/user_' . $uid . '_' . time() . '.' . $ext;
            if (!is_dir('images/avatars')) mkdir('images/avatars', 0777, true);
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
                $picture_path = $target;
            }
        }
        $sql = "UPDATE users SET username='$new_username', email='$new_email', address='$new_address', picture=" . ($picture_path ? "'$picture_path'" : "NULL") . " WHERE u_id='$uid'";
        mysqli_query($db, $sql);
        // Reload lại dữ liệu mới
        header("Location: profile.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/img/iconss.png">
    <title>Thông tin cá nhân - FastFood</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> 
    <style>
        body { background: #f4f4f4; }
        .profile-container { max-width: 1200px; margin: 40px auto; display: flex; gap: 32px; }
        .profile-sidebar { background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); width: 320px; padding: 32px 24px; display: flex; flex-direction: column; align-items: center; }
        .profile-avatar { width: 90px; height: 90px; border-radius: 50%; background: #c2185b; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 3.2rem; font-weight: bold; margin-bottom: 16px; }
        .profile-username { font-size: 1.25rem; font-weight: 600; color: #444; margin-bottom: 24px; word-break: break-all; text-align: center; }
        .profile-menu { width: 100%; }
        .profile-menu li { list-style: none; margin-bottom: 10px; }
        .profile-menu .menu-has-children > .menu-parent { font-weight: 600; color: #c2185b; background: none; border: none; outline: none; cursor: pointer; display: flex; align-items: center; width: 100%; padding: 10px 14px; border-radius: 6px; }
        .profile-menu .menu-has-children > .menu-parent .fa { color: #c2185b; }
        .profile-menu .menu-has-children > ul { display: none; }
        .profile-menu .menu-has-children.open > ul { display: block; }
        .profile-menu .menu-has-children.open > .menu-parent, .profile-menu .menu-has-children > .menu-parent:focus { background: #ffe0ec; color: #c2185b; }
        .profile-menu a { display: flex; align-items: center; gap: 10px; color: #222; font-size: 1.08rem; padding: 10px 14px; border-radius: 6px; text-decoration: none; transition: background 0.15s, color 0.15s; }
        .profile-menu a.active, .profile-menu a:hover { background: #ffe0ec; color: #c2185b; font-weight: 600; }
        .profile-menu .fa { font-size: 1.2rem; }
        .profile-menu .menu-red { color: #c2185b; }
        .profile-menu .menu-gray { color: #444; }
        .profile-menu .menu-arrow { margin-left: auto; color: #c2185b; }
        @media (max-width: 900px) { .profile-container { flex-direction: column; gap: 18px; } .profile-sidebar { width: 100%; flex-direction: row; justify-content: flex-start; padding: 18px 10px; } }
        .profile-main { flex: 1 1 0; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 32px 36px; min-width: 0; }
        .profile-main h2 { font-size: 1.4rem; font-weight: 700; margin-bottom: 24px; }
        .profile-avatar-lg { width: 120px; height: 120px; border-radius: 50%; background: #c2185b; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 4rem; font-weight: bold; margin-bottom: 18px; }
        .profile-upload-btn { margin-left: 10px; }
        .profile-form label { font-weight: 600; color: #2d2d2d; }
        .profile-form input, .profile-form select { margin-bottom: 16px; }
        .profile-form .form-group { margin-bottom: 18px; }
        .profile-form .btn { min-width: 120px; }
        .profile-form .change-pass-link { margin-left: 12px; font-size: 1rem; color: #2366b5; text-decoration: underline; cursor: pointer; }
        .profile-delete-btn { float: right; margin-top: 8px; }
        @media (max-width: 600px) { .profile-main { padding: 16px 6px; } }
    </style>
</head>
<body>

<header id="header" class="header-scroll top-header headrom" style="background: linear-gradient(90deg, #ffb347 0%, #ff9800 100%); box-shadow: 0 2px 12px rgba(0,0,0,0.07);">
    <nav class="navbar navbar-dark" style="padding: 0.7rem 0;">
        <div class="container" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center;">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse" style="margin-right: 18px; background: #fff3e0; border: none; color: #ff9800; font-size: 1.5rem; padding: 6px 12px; border-radius: 6px;">&#9776;</button>
                <a class="navbar-brand" href="index.php" style="display: flex; align-items: center;">
                    <img class="img-rounded" src="images/img/newimg.jpg" style="width: 62px; height: 62px; margin-right: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); background: #fff; border-radius: 12px;">
                    <span style="font-weight: bold; font-size: 1.5rem; color: #fff; letter-spacing: 1px;">FastFood</span>
                </a>
            </div>
            <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
                <ul class="nav navbar-nav" style="gap: 8px; align-items: center;">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php" style="color: #fff; font-weight: 600; font-size: 1.1rem; padding: 8px 18px; border-radius: 20px; transition: background 0.2s; background: rgba(255,255,255,0.08);">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="Foods.php" style="color: #fff; font-weight: 600; font-size: 1.1rem; padding: 8px 18px; border-radius: 20px; transition: background 0.2s; background: rgba(255,255,255,0.08);">Foods</a>
                    </li>
                    <?php
                    if(empty($_SESSION["user_id"]))
                    {
                        echo '<li class="nav-item"><a href="login.php" class="nav-link active" style="color: #fff; font-weight: 600; font-size: 1.1rem; padding: 8px 18px; border-radius: 20px; background: #ff9800; margin-left: 6px;">Login</a></li>';
                        echo '<li class="nav-item"><a href="registration.php" class="nav-link active" style="color: #fff; font-weight: 600; font-size: 1.1rem; padding: 8px 18px; border-radius: 20px; background: #ff9800; margin-left: 6px;">Register</a></li>';
                    }
                    else
                    {
                        // Lấy tên user từ database
                        $uid = $_SESSION["user_id"];
                        $userq = mysqli_query($db, "SELECT username, picture FROM users WHERE u_id='$uid' LIMIT 1");
                        $udata = mysqli_fetch_assoc($userq);
                        $username = $udata ? $udata['username'] : 'User';
                        $firstChar = strtoupper(substr($username,0,1));
                        $picture = ($udata && !empty($udata['picture'])) ? $udata['picture'] : '';
                        echo '<li class="nav-item dropdown" style="margin-left: 12px;">';
                        echo '<a href="#" class="nav-link active dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center; gap: 8px; color: #222; background: #fff; border-radius: 22px; padding: 4px 16px 4px 6px; font-weight: 600; font-size: 1.08rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">';
                        if ($picture) {
                            echo '<span style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: #c2185b; color: #fff; border-radius: 50%; font-size: 1.3rem; font-weight: bold; overflow:hidden;"><img src="'.$picture.'" alt="avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;"></span>';
                        } else {
                            echo '<span style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: #c2185b; color: #fff; border-radius: 50%; font-size: 1.3rem; font-weight: bold;">'.$firstChar.'</span>';
                        }
                        echo '<span style="max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'.$username.'</span>';
                        echo '<span style="font-size: 1.1rem; color: #888; margin-left: 4px;"></span>';
                        echo '</a>';
                        echo '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" style="min-width: 220px; padding: 0;">';
                        echo '<a class="dropdown-item" href="your_orders.php" style="display:flex;align-items:center;gap:10px;padding:12px 18px;font-size:1.08rem;"><span style="color:#4caf50;font-size:1.5rem;"><i class="fa fa-calendar"></i></span> Lịch sử đơn hàng</a>';
                        echo '<a class="dropdown-item" href="voucher_wallet.php" style="display:flex;align-items:center;gap:10px;padding:12px 18px;font-size:1.08rem;"><span style="color:#2196f3;font-size:1.5rem;"><i class="fa fa-ticket"></i></span> Ví Voucher</a>';
                        echo '<a class="dropdown-item" href="profile.php" style="display:flex;align-items:center;gap:10px;padding:12px 18px;font-size:1.08rem;"><span style="color:#ff9800;font-size:1.5rem;"><i class="fa fa-user"></i></span> Cập nhật tài khoản</a>';
                        echo '<div class="dropdown-divider" style="margin:0;"></div>';
                        echo '<a class="dropdown-item" href="logout.php" style="display:flex;align-items:center;gap:10px;padding:12px 18px;font-size:1.08rem;"><span style="color:#555;font-size:1.5rem;"><i class="fa fa-power-off"></i></span> Đăng xuất</a>';
                        echo '</div>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>


<div class="title text-xs-center m-b-30">
                    <p class="lead">.</p>
                </div>



<div class="profile-container">
    <div class="profile-sidebar">
        <div class="profile-avatar">
            <?php if ($picture): ?>
                <img src="<?=$picture?>" alt="avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
            <?php else: ?>
                <?=$firstChar?>
            <?php endif; ?>
        </div>
        <div class="profile-username"><?=$username?></div>
        <ul class="profile-menu">
            <li><a href="#" class="active"><i class="fa fa-user menu-red"></i> Cập nhật tài khoản <span class="menu-arrow">&gt;</span></a></li>
            <li><a href="account/address.php"><i class="fa fa-map-marker menu-red"></i> Cập nhật địa chỉ <span class="menu-arrow">&gt;</span></a></li>
            <li><a href="account/vat_invoice.php"><i class="fa fa-file-text-o menu-red"></i> Cập nhật hóa đơn VAT <span class="menu-arrow">&gt;</span></a></li>
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var parents = document.querySelectorAll('.profile-menu .menu-has-children > .menu-parent');
        parents.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var li = btn.parentElement;
                li.classList.toggle('open');
            });
        });
    });
</script>
        </ul>
    </div>
    <div class="profile-main">
        <form class="profile-form" method="post" enctype="multipart/form-data">
            <div style="display: flex; align-items: center; gap: 32px; margin-bottom: 18px;">
                <div class="profile-avatar-lg">
                    <?php if ($picture): ?>
                        <img src="<?=$picture?>" alt="avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    <?php else: ?>
                        <?=$firstChar?>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="avatar">Tải lên từ</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" style="display:inline-block;">
                    <span style="font-size: 0.95rem; color: #888; margin-left: 8px;">Chấp nhận GIF, JPEG, PNG, BMP với kích thước tối đa 5.0 MB</span><br>
                    <button type="button" class="btn btn-primary profile-upload-btn">Cập nhật</button>
                </div>
            </div>
            <hr>
            <h2>Thay đổi thông tin</h2>
            <div class="form-group">
                <label for="username">Tên</label>
                <input type="text" class="form-control" id="username" name="username" value="<?=$username?>" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address" value="<?=$address?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?=$email?>" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" value="********" disabled>
                <a href="account/change_password.php" class="change-pass-link">Đổi mật khẩu</a>
            </div>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            <button type="submit" name="delete_account" class="btn btn-outline-danger profile-delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản? Hành động này không thể hoàn tác!');">Xóa tài khoản</button>
        </form>
    </div>
</div>


        <footer class="footer">
<!-- Real-time Chat with Mng_shop (WebSocket) -->
  <!-- Bong bóng chat với Mng_shop -->
  <div id="ws-chat-bubble" style="position:fixed;bottom:120px;right:36px;z-index:99998;">
    <button id="ws-chat-toggle" style="width:64px;height:64px;border-radius:50%;background:#ff9800;color:#fff;border:none;box-shadow:0 2px 16px rgba(0,0,0,0.13);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.18s;padding:0;">
      <img src="images/img/master.png" alt="Chat" style="width:48px;height:48px;border-radius:50%;box-shadow:0 1px 6px rgba(0,0,0,0.10);background:#fff;">
    </button>
    <div id="ws-chat-box" style="display:none;position:absolute;bottom:80px;right:0;width:340px;background:#fff;border-radius:18px;box-shadow:0 2px 16px rgba(0,0,0,0.13);padding:16px;">
      <div style="font-weight:600;font-size:1.08rem;margin-bottom:8px;color:#ff9800;display:flex;justify-content:space-between;align-items:center;">
        Bộ phận CSKH
        <button id="ws-chat-close" style="background:none;border:none;font-size:1.3rem;color:#888;cursor:pointer;">&times;</button>
      </div>
      <div id="chat-messages" style="height:180px;overflow-y:auto;margin-bottom:12px;background:#f9f9f9;border-radius:8px;padding:8px;"></div>
      <div style="display:flex;gap:8px;">
        <input type="text" id="chat-input" placeholder="Nhập tin nhắn..." style="flex:1;padding:8px;border-radius:6px;border:1px solid #eee;">
        <button id="ws-chat-send" style="padding:8px 18px;background:#ff9800;color:#fff;border:none;border-radius:6px;font-weight:600;"><i class="fa fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
  <style>
    #ws-chat-bubble {z-index:99998;}
    #ws-chat-toggle {transition:background 0.18s;}
    #ws-chat-toggle:hover {background:#ffe0b2;color:#ff9800;}
    #ws-chat-box {animation: wsChatFadeIn 0.22s;}
    @keyframes wsChatFadeIn {from{opacity:0;transform:scale(0.95);}to{opacity:1;transform:scale(1);}}
    @media (max-width:600px){#ws-chat-box{width:96vw;right:-16vw;}}
  </style>
  <script>
    // --- Bong bóng chat real-time: bỏ tên, thêm icon gửi, gửi bằng Enter ---
    var wsChatToggle = document.getElementById('ws-chat-toggle');
    var wsChatBox = document.getElementById('ws-chat-box');
    var wsChatClose = null;
    var chatMessagesDiv = document.getElementById('chat-messages');
    var chatInput = document.getElementById('chat-input');
    var wsChatSend = document.getElementById('ws-chat-send');
    var chatHistory = [];
    function renderChatMessages() {
      chatMessagesDiv.innerHTML = '';
      chatHistory.forEach(function(msg) {
        var html = '<div style="margin-bottom:6px;display:flex;align-items:center;gap:8px;">';
        if(msg.sender === 'user') {
          html += '<span style="background:#ff9800;color:#fff;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;"><i class="fa fa-user"></i></span>';
        } else {
          html += '<span style="background:#fff;border:2px solid #ff9800;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;overflow:hidden;">';
          html += '<img src="images/img/master.png" alt="Mng_shop" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">';
          html += '</span>';
        }
        html += '<span style="background:#f9f9f9;padding:10px 16px;border-radius:14px;font-size:1.05rem;">'+msg.content+'</span>';
        html += '</div>';
        chatMessagesDiv.innerHTML += html;
      });
      chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
    }
    wsChatToggle.onclick = function() {
      wsChatBox.style.display = 'block';
      wsChatToggle.style.display = 'none';
      wsChatClose = document.getElementById('ws-chat-close');
      if(wsChatClose) wsChatClose.onclick = function(){
        wsChatBox.style.display = 'none';
        wsChatToggle.style.display = 'block';
      };
      setTimeout(function(){chatInput.focus();},200);
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
      var msg = chatInput.value.trim();
      if (!msg) return;
      var data = {sender: 'user', receiver: 'mng_shop', content: msg};
      ws.send(JSON.stringify(data));
      chatHistory.push(data);
      renderChatMessages();
      chatInput.value = '';
    }
    wsChatSend.onclick = sendMessage;
    chatInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') sendMessage();
    });
  </script>
<!-- End Real-time Chat -->
            <div class="container">
                <div class="bottom-footer">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 payment-options color-gray">
                            <h5>Thanh Toán Đa Dịch Vụ</h5>
                            <ul>
                                <li>
                                    <a href="#"> <img src="images/img/momo.png" style="width: 32px; height: 24px;" alt="momo"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="images/img/msc.png" style="width: 32px; height: 24px;" alt="Mastercard"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="images/img/visa.png" style="width: 32px; height: 24px;" alt="visa"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="images/img/vnpay.png" style="width: 32px; height: 24px;" alt="vnpay"> </a>
                                </li>
                                
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-4 address color-gray">
                                    <h5>Address</h5>
                                    <p>Công Ty Cổ Phần Foody</p>
                                    <p>Lầu G, Tòa nhà Jabes 1,số 244 đường Cống Quỳnh, phường Phạm Ngũ Lão, Quận 1, TPHCM</p>
                                    <p>Điện thoại: 1900 2042</p>
                                    <p>Email: <a href="mailto: cskh@support.fastfood.vn">cskh@support.fastfood.vn</a></p>
                                </div>
                                <div class="col-xs-12 col-sm-5 additional-info color-gray">
                                    <h5>Thông tin về shop</h5>
                                   <p>người dùng ShopeeFood còn có thể thanh toán qua ví điện tử với nhiều ưu đãi hấp dẫn, thẻ tín dụng (Visa/Mastercard), thẻ ATM hoặc tài khoản ngân hàng online (iBanking)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </footer>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>