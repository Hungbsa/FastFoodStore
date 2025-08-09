<?php
include("../connection/connect.php");
session_start();
if(empty($_SESSION["user_id"])){
    header("Location: ../login.php");
    exit();
}
$uid = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../images/img/iconss.png">
    <title>Cập nhật địa Chỉ - FastFood</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/animsition.min.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet"> 
    <style>
        body { background: #f4f4f4; }
        .vat-container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 32px 36px; }
        .vat-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 24px; }
        .vat-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .vat-table th, .vat-table td { padding: 12px 10px; border-bottom: 1px solid #eee; text-align: left; }
        .vat-table th { color: #222; font-weight: 700; background: #fafafa; }
        .vat-table td { color: #333; }
        .vat-form-title { font-size: 1.08rem; font-weight: 600; margin-bottom: 10px; }
        .vat-form input { margin-bottom: 12px; }
        .vat-save-btn { float: right; background: #f4511e; color: #fff; border: none; border-radius: 5px; padding: 8px 32px; font-size: 1.08rem; font-weight: 600; transition: background 0.2s; }
        .vat-save-btn:hover { background: #ff7043; }
    </style>
</head>
</html>

<?php

$userq = mysqli_query($db, "SELECT username FROM users WHERE u_id='$uid' LIMIT 1");
$udata = mysqli_fetch_assoc($userq);
$username = $udata ? $udata['username'] : 'User';
$firstChar = strtoupper(substr($username,0,1));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật hóa đơn VAT</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <style>
        body { background: #f7f7f7; }
        .profile-container { display: flex; gap: 24px; margin: 32px 0; justify-content: center; }
        .profile-sidebar { width: 270px; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 28px 0 18px 0; display: flex; flex-direction: column; align-items: center; }
        .profile-avatar { width: 60px; height: 60px; border-radius: 50%; background: #c2185b; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; font-weight: bold; margin-bottom: 10px; }
        .profile-username { font-size: 1.1rem; font-weight: 600; color: #444; margin-bottom: 24px; word-break: break-all; text-align: center; }
        .profile-menu { width: 100%; padding-left: 0; }
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
        .vat-table { width: 100%; background: #fff; border-radius: 8px; border-collapse: separate; border-spacing: 0; margin-bottom: 18px; }
        .vat-table th, .vat-table td { padding: 14px 16px; text-align: left; }
        .vat-table th { background: #f5f5f5; font-weight: 700; color: #222; border-top: 1px solid #eee; }
        .vat-table tr { border-bottom: 1px solid #eee; }
        .vat-table td { color: #333; font-size: 1.05rem; }
        .vat-table a { color: #1976d2; text-decoration: none; margin-right: 10px; }
        .vat-table a:hover { text-decoration: underline; }
        .vat-form { margin-top: 18px; }
        .vat-form input { margin-bottom: 12px; border-radius: 6px; border: 1px solid #ddd; padding: 10px 14px; font-size: 1.08rem; width: 100%; }
        .vat-form .vat-row { display: flex; gap: 16px; }
        .vat-form .vat-row > * { flex: 1; }
        .btn-save { background: #f44336; color: #fff; border: none; border-radius: 6px; padding: 10px 32px; font-weight: 600; font-size: 1.08rem; float: right; margin-top: 8px; transition: background 0.15s; }
        .btn-save:hover { background: #c2185b; }
        @media (max-width: 600px) { .profile-main { padding: 16px 6px; } .vat-form .vat-row { flex-direction: column; gap: 0; } }
    </style>
</head>
<body>
<header id="header" class="header-scroll top-header headrom" style="background: linear-gradient(90deg, #ffb347 0%, #ff9800 100%); box-shadow: 0 2px 12px rgba(0,0,0,0.07);">
    <nav class="navbar navbar-dark" style="padding: 0.7rem 10;">
        <div class="container" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center;">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse" style="margin-right: 18px; background: #fff3e0; border: none; color: #ff9800; font-size: 1.5rem; padding: 6px 12px; border-radius: 6px;">&#9776;</button>
                <a class="navbar-brand" href="index.php" style="display: flex; align-items: center;">
                    <img class="img-rounded" src="../images/img/newimg.jpg" style="width: 62px; height: 62px; margin-right: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); background: #fff; border-radius: 12px;">
                    <span style="font-weight: bold; font-size: 1.5rem; color: #fff; letter-spacing: 1px;">FastFood</span>
                </a>
            </div>
            <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
                <ul class="nav navbar-nav" style="gap: 8px; align-items: center;">
                    <li class="nav-item">
                        <a class="nav-link active" href="../index.php" style="color: #fff; font-weight: 600; font-size: 1.1rem; padding: 8px 18px; border-radius: 20px; transition: background 0.2s; background: rgba(255,255,255,0.08);">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../Foods.php" style="color: #fff; font-weight: 600; font-size: 1.1rem; padding: 8px 18px; border-radius: 20px; transition: background 0.2s; background: rgba(255,255,255,0.08);">Foods</a>
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
                    // Xử lý đường dẫn ảnh cho đúng thư mục
                    $avatarPath = $picture;
                    if ($avatarPath && strpos($avatarPath, 'images/') === 0) {
                        $avatarPath = '../' . $avatarPath;
                    }
                    echo '<li class="nav-item dropdown" style="margin-left: 12px;">';
                    echo '<a href="#" class="nav-link active dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center; gap: 8px; color: #222; background: #fff; border-radius: 22px; padding: 4px 16px 4px 6px; font-weight: 600; font-size: 1.08rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">';
                    if ($picture) {
                        echo '<span style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: #c2185b; color: #fff; border-radius: 50%; font-size: 1.3rem; font-weight: bold; overflow:hidden;"><img src="'.$avatarPath.'" alt="avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;"></span>';
                    } else {
                        echo '<span style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: #c2185b; color: #fff; border-radius: 50%; font-size: 1.3rem; font-weight: bold;">'.$firstChar.'</span>';
                    }
                    echo '<span style="max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'.$username.'</span>';
                    echo '<span style="font-size: 1.1rem; color: #888; margin-left: 4px;"></span>';
                    echo '</a>';
                        echo '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" style="min-width: 220px; padding: 0;">';
                        echo '<a class="dropdown-item" href="../your_orders.php" style="display:flex;align-items:center;gap:10px;padding:12px 18px;font-size:1.08rem;"><span style="color:#4caf50;font-size:1.5rem;"><i class="fa fa-calendar"></i></span> Lịch sử đơn hàng</a>';
                        echo '<a class="dropdown-item" href="../voucher_wallet.php" style="display:flex;align-items:center;gap:10px;padding:12px 18px;font-size:1.08rem;"><span style="color:#2196f3;font-size:1.5rem;"><i class="fa fa-ticket"></i></span> Ví Voucher</a>';
                        echo '<a class="dropdown-item" href="../profile.php" style="display:flex;align-items:center;gap:10px;padding:12px 18px;font-size:1.08rem;"><span style="color:#ff9800;font-size:1.5rem;"><i class="fa fa-user"></i></span> Cập nhật tài khoản</a>';
                        echo '<div class="dropdown-divider" style="margin:0;"></div>';
                        echo '<a class="dropdown-item" href="../logout.php" style="display:flex;align-items:center;gap:10px;padding:12px 18px;font-size:1.08rem;"><span style="color:#555;font-size:1.5rem;"><i class="fa fa-power-off"></i></span> Đăng xuất</a>';
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
        <div class="profile-avatar" style="width:60px;height:60px;border-radius:50%;background:#c2185b;color:#fff;display:flex;align-items:center;justify-content:center;font-size:2.2rem;font-weight:bold;margin-bottom:10px;overflow:hidden;">
            <?php
            $picture = '';
            $userq = mysqli_query($db, "SELECT picture FROM users WHERE u_id='$uid' LIMIT 1");
            $udata = mysqli_fetch_assoc($userq);
            if ($udata && !empty($udata['picture'])) {
                $picture = $udata['picture'];
            }
            // Sửa đường dẫn nếu là ảnh upload
            $avatarPath = $picture;
            if ($avatarPath && strpos($avatarPath, 'images/') === 0) {
                $avatarPath = '../' . $avatarPath;
            }
            if ($picture): ?>
                <img src="<?=$avatarPath?>" alt="avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">
            <?php else: ?>
                <?=$firstChar?>
            <?php endif; ?>
        </div>
        <div class="profile-username"><?=$username?></div>
        <ul class="profile-menu">
            <li><a href="../profile.php"><i class="fa fa-user menu-red"></i> Cập nhật tài khoản <span class="menu-arrow">&gt;</span></a></li>
            <li><a href="account/address.php" class="active"><i class="fa fa-map-marker menu-red"></i> Cập nhật địa chỉ <span class="menu-arrow">&gt;</span></a></li>
            <li><a href="../account/vat_invoice.php"><i class="fa fa-file-text-o menu-red"></i> Cập nhật hóa đơn VAT <span class="menu-arrow">&gt;</span></a></li>
        </ul>
    </div>
    <div class="profile-main">
        <h2>Cập nhật địa chỉ</h2>
        <table class="address-table" style="width:100%;background:#fff;border-radius:8px;border-collapse:separate;border-spacing:0;margin-bottom:18px;">
    <thead>
        <tr>
            <th style="padding:14px 16px;background:#f5f5f5;font-weight:700;color:#222;border-top:1px solid #eee;">Tên gọi nhớ</th>
            <th style="padding:14px 16px;background:#f5f5f5;font-weight:700;color:#222;border-top:1px solid #eee;">Địa chỉ</th>
            <th style="padding:14px 16px;background:#f5f5f5;font-weight:700;color:#222;border-top:1px solid #eee;">Số điện thoại</th>
            <th style="padding:14px 16px;background:#f5f5f5;font-weight:700;color:#222;border-top:1px solid #eee;"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Hiển thị địa chỉ đúng logic bảng addresses
        $address_query = mysqli_query($db, "SELECT * FROM addresses WHERE user_id='$uid'");
        if(mysqli_num_rows($address_query) > 0) {
            while($row = mysqli_fetch_assoc($address_query)) {
                echo '<tr style="border-bottom:1px solid #eee;">';
                echo '<td style="padding:14px 16px;color:#333;font-size:1.05rem;">'.htmlspecialchars($row['label']).'</td>';
                echo '<td style="padding:14px 16px;color:#333;font-size:1.05rem;">'.htmlspecialchars($row['address']).'</td>';
                echo '<td style="padding:14px 16px;color:#333;font-size:1.05rem;">'.htmlspecialchars($row['phone']).'</td>';
                echo '<td style="padding:14px 16px;white-space:nowrap;">';
                echo '<button type="button" class="btn btn-link btn-edit-address" data-id="'.htmlspecialchars($row['id']).'" style="color:#1976d2;text-decoration:none;margin-right:8px;padding:0 8px;"><i class="fa fa-pencil"></i> Sửa</button>';
                echo '<button type="button" class="btn btn-link btn-delete-address" data-id="'.htmlspecialchars($row['id']).'" style="color:#d32f2f;text-decoration:none;margin-right:8px;padding:0 8px;"><i class="fa fa-trash"></i> Xoá</button>';
                echo '<button type="button" class="btn btn-link btn-default-address" data-id="'.htmlspecialchars($row['id']).'" style="color:#fbc02d;text-decoration:none;padding:0 8px;" title="Chọn làm mặc định">';
                if($row['is_default']) {
                    echo '<i class="fa fa-star" style="color:#fbc02d;"></i>';
                } else {
                    echo '<i class="fa fa-star-o" style="color:#bdb76b;"></i>';
                }
                echo '</button>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            // Nếu không có địa chỉ nào trong addresses thì hiển thị thông tin từ users (không sửa/xoá được)
            $user_query = mysqli_query($db, "SELECT * FROM users WHERE u_id='$uid'");
            $user = mysqli_fetch_assoc($user_query);
            echo '<tr style="border-bottom:1px solid #eee;">';
            echo '<td style="padding:14px 16px;color:#333;font-size:1.05rem;">'.htmlspecialchars($user['l_name']).'</td>';
            echo '<td style="padding:14px 16px;color:#333;font-size:1.05rem;">'.htmlspecialchars($user['address']).'</td>';
            echo '<td style="padding:14px 16px;color:#333;font-size:1.05rem;">'.htmlspecialchars($user['phone']).'</td>';
            echo '<td style="padding:14px 16px;white-space:nowrap;">';
            echo '<button type="button" class="btn btn-link btn-edit-address" style="color:#1976d2;text-decoration:none;margin-right:8px;padding:0 8px;" disabled><i class="fa fa-pencil"></i> Sửa</button>';
            echo '<button type="button" class="btn btn-link btn-delete-address" style="color:#d32f2f;text-decoration:none;margin-right:8px;padding:0 8px;" disabled><i class="fa fa-trash"></i> Xoá</button>';
            echo '<button type="button" class="btn btn-link btn-default-address" style="color:#fbc02d;text-decoration:none;padding:0 8px;" title="Chọn làm mặc định" disabled><i class="fa fa-star-o"></i></button>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
        <!-- Modal sửa địa chỉ -->
<div id="editAddressModal" class="modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;overflow:auto;background:rgba(0,0,0,0.5);">
  <div class="modal-content" style="background:#fff;margin:40px auto;padding:32px 28px 18px 28px;border-radius:10px;max-width:900px;position:relative;box-shadow:0 2px 12px rgba(0,0,0,0.18);">
    <span class="close-modal" id="closeEditModal" style="position:absolute;top:12px;right:18px;font-size:2rem;font-weight:bold;color:#888;cursor:pointer;">&times;</span>
    <form id="editAddressForm" autocomplete="off">
      <input type="hidden" name="id" id="edit_id">
      <div style="display:flex;gap:24px;flex-wrap:wrap;">
        <div style="flex:1;min-width:260px;">
          <label>Tên gọi nhớ<span style="color:red">*</span></label>
          <input type="text" name="label" id="edit_label" class="form-control" required>
        </div>
        <div style="flex:1;min-width:260px;">
          <label>Họ tên<span style="color:red">*</span></label>
          <input type="text" name="fullname" id="edit_fullname" class="form-control" required>
        </div>
      </div>
      <div style="display:flex;gap:24px;flex-wrap:wrap;">
        <div style="flex:1;min-width:260px;">
          <label>Email<span style="color:red">*</span></label>
          <input type="email" name="email" id="edit_email" class="form-control" required>
        </div>
        <div style="flex:1;min-width:260px;">
          <label>Số điện thoại<span style="color:red">*</span></label>
          <input type="text" name="phone" id="edit_phone" class="form-control" required>
        </div>
      </div>
      <div style="margin-top:10px;">
        <label>Địa chỉ<span style="color:red">*</span></label>
        <input type="text" name="address" id="edit_address" class="form-control" required>
      </div>
      <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;">
        <button type="button" id="closeEditModalBtn" class="btn btn-secondary" style="min-width:90px;">ĐÓNG</button>
        <button type="submit" class="btn btn-danger" style="min-width:90px;">LƯU</button>
      </div>
    </form>
  </div>
</div>
    </tbody>
 </table>
        <script>
            document.getElementById('addAddressForm').addEventListener('submit', function(e) {
                e.preventDefault();
                // Gửi dữ liệu form bằng AJAX
                fetch('add_address.php', {
                    method: 'POST',
                    body: new FormData(this)
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Đóng modal và làm mới trang
                        document.getElementById('addAddressModal').style.display = 'none';
                        location.reload(); // Làm mới trang để hiển thị dữ liệu mới
                    } else {
                        alert('Có lỗi xảy ra: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        </script>
        <button class="btn-add" id="showAddModal" style="background:#f44336;color:#fff;border:none;border-radius:6px;padding:10px 32px;font-weight:600;font-size:1.08rem;float:right;margin-top:18px;transition:background 0.15s;">THÊM</button>

        <!-- Modal Thêm địa chỉ -->
        <div id="addAddressModal" class="modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;overflow:auto;background:rgba(0,0,0,0.5);">
          <div class="modal-content" style="background:#fff;margin:40px auto;padding:32px 28px 18px 28px;border-radius:10px;max-width:900px;position:relative;box-shadow:0 2px 12px rgba(0,0,0,0.18);">
            <span class="close-modal" id="closeAddModal" style="position:absolute;top:12px;right:18px;font-size:2rem;font-weight:bold;color:#888;cursor:pointer;">&times;</span>
            <form id="addAddressForm" autocomplete="off">
              <div style="display:flex;gap:24px;flex-wrap:wrap;">
                <div style="flex:1;min-width:260px;">
                  <label>Tên gọi nhớ<span style="color:red">*</span></label>
                  <input type="text" name="label" class="form-control" placeholder="" required>
                </div>
                <div style="flex:1;min-width:260px;">
                  <label>Họ tên<span style="color:red">*</span></label>
                  <input type="text" name="fullname" class="form-control" placeholder="" required>
                </div>
              </div>
              <div style="display:flex;gap:24px;flex-wrap:wrap;">
                <div style="flex:1;min-width:260px;">
                  <label>Email<span style="color:red">*</span></label>
                  <input type="email" name="email" class="form-control" placeholder="" required>
                </div>
                <div style="flex:1;min-width:260px;">
                  <label>Số điện thoại<span style="color:red">*</span></label>
                  <input type="text" name="phone" class="form-control" placeholder="" required>
                </div>
              </div>
              <div style="margin-top:10px;">
                <label>Địa chỉ<span style="color:red">*</span></label>
                <div style="display:flex;gap:8px;align-items:center;">
                  <input type="text" name="address" id="addressInput" class="form-control" placeholder="Chọn vị trí trên bản đồ hoặc nhập địa chỉ" required readonly style="flex:1;">
                  <button type="button" class="btn btn-info" id="openMapBtn" style="white-space:nowrap;"><i class="fa fa-map-marker"></i> Chọn trên bản đồ</button>
                </div>
                <!-- Modal Leaflet Map -->
                <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
                <div id="mapModal" style="display:none;position:fixed;z-index:99999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.35);">
                  <div style="background:#fff;max-width:600px;margin:60px auto;padding:18px 18px 12px 18px;border-radius:10px;position:relative;box-shadow:0 2px 16px rgba(0,0,0,0.18);">
                    <span id="closeMapModal" style="position:absolute;top:10px;right:18px;font-size:2rem;font-weight:bold;color:#888;cursor:pointer;">&times;</span>
                    <h5 style="margin-bottom:10px;color:#ff9800;font-weight:600;"><i class="fa fa-map-marker"></i> Chọn vị trí trên bản đồ</h5>
                    <div id="leafletMap" style="width:100%;height:350px;border-radius:8px;"></div>
                    <div style="margin-top:10px;text-align:right;">
                      <button type="button" class="btn btn-danger" id="selectLocationBtn">Chọn vị trí này</button>
                    </div>
                  </div>
                </div>
              </div>
              
              <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;">
                <button type="button" id="closeAddModalBtn" class="btn btn-secondary" style="min-width:90px;">ĐÓNG</button>
                <button type="submit" class="btn btn-danger" style="min-width:90px;">OK</button>
              </div>
            </form>
          </div>
        </div>
        <!-- Modal Thêm địa chỉ -->

    </div>
</div>

    <footer class="footer">
<!-- Real-time Chat with Mng_shop (WebSocket) -->
  <!-- Bong bóng chat với Mng_shop -->
  <div id="ws-chat-bubble" style="position:fixed;bottom:120px;right:36px;z-index:99998;">
    <button id="ws-chat-toggle" style="width:64px;height:64px;border-radius:50%;background:#ff9800;color:#fff;border:none;box-shadow:0 2px 16px rgba(0,0,0,0.13);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.18s;padding:0;">
      <img src="../images/img/master.png" alt="Chat" style="width:48px;height:48px;border-radius:50%;box-shadow:0 1px 6px rgba(0,0,0,0.10);background:#fff;">
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
                                    <a href="#"> <img src="../images/img/momo.png" style="width: 32px; height: 24px;" alt="momo"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="../images/img/msc.png" style="width: 32px; height: 24px;" alt="Mastercard"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="../images/img/visa.png" style="width: 32px; height: 24px;" alt="visa"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="../images/img/vnpay.png" style="width: 32px; height: 24px;" alt="vnpay"> </a>
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



<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    let leafletMap, leafletMarker, selectedLatLng = null;
    document.addEventListener('DOMContentLoaded', function() {
    const openMapBtn = document.getElementById('openMapBtn');
    const mapModal = document.getElementById('mapModal');
    const closeMapModal = document.getElementById('closeMapModal');
    const selectLocationBtn = document.getElementById('selectLocationBtn');
    const addressInput = document.getElementById('addressInput');
    if(openMapBtn && mapModal && closeMapModal && selectLocationBtn && addressInput) {
        openMapBtn.onclick = function() {
        mapModal.style.display = 'block';
        setTimeout(initLeafletMap, 100);
        };
        closeMapModal.onclick = function() { mapModal.style.display = 'none'; };
        window.onclick = function(e) { if(e.target == mapModal) mapModal.style.display = 'none'; };
        selectLocationBtn.onclick = function() {
        if(selectedLatLng) {
            // Reverse geocode bằng Nominatim
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${selectedLatLng.lat}&lon=${selectedLatLng.lng}`)
            .then(res => res.json())
            .then(data => {
                if(data && data.display_name) {
                addressInput.value = data.display_name;
                mapModal.style.display = 'none';
                } else {
                alert('Không tìm được địa chỉ!');
                }
            })
            .catch(() => alert('Không tìm được địa chỉ!'));
        }
        };
    }
    });
    function initLeafletMap() {
    if(leafletMap) return;
    leafletMap = L.map('leafletMap').setView([10.762622, 106.660172], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(leafletMap);
    leafletMarker = L.marker([10.762622, 106.660172], {draggable:true}).addTo(leafletMap);
    selectedLatLng = {lat: 10.762622, lng: 106.660172};
    leafletMap.on('click', function(e) {
        leafletMarker.setLatLng(e.latlng);
        selectedLatLng = e.latlng;
    });
    leafletMarker.on('dragend', function(e) {
        selectedLatLng = e.target.getLatLng();
    });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var showBtn = document.getElementById('showAddModal');
    var modal = document.getElementById('addAddressModal');
    var closeBtn = document.getElementById('closeAddModal');
    var closeBtn2 = document.getElementById('closeAddModalBtn');
    var form = document.getElementById('addAddressForm');
    if(showBtn && modal) {
        showBtn.onclick = function() { modal.style.display = 'block'; };
        closeBtn.onclick = function() { modal.style.display = 'none'; };
        if(closeBtn2) closeBtn2.onclick = function() { modal.style.display = 'none'; };
        window.onclick = function(event) { if(event.target == modal) modal.style.display = 'none'; };
    }
    if(form) {
        form.onsubmit = function(e) {
        e.preventDefault();
        var formData = new FormData(form);
        fetch('add_address.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Thêm địa chỉ thành công!',
                showConfirmButton: false,
                timer: 1200
            });
            modal.style.display = 'none';
            form.reset();
            setTimeout(function(){ location.reload(); }, 1200);
            } else {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: data.message });
            }
        })
        .catch(error => {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể gửi dữ liệu!' });
            console.error('Error:', error);
        });
        };
    }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Sửa
    document.querySelectorAll('.btn-edit-address').forEach(function(btn) {
        btn.onclick = function() {
        var id = this.getAttribute('data-id');
        fetch('get_address.php?id='+id)
            .then(res => res.json())
            .then(data => {
            if(data.success) {
                document.getElementById('edit_id').value = data.address.id;
                document.getElementById('edit_label').value = data.address.label;
                document.getElementById('edit_fullname').value = data.address.fullname;
                document.getElementById('edit_email').value = data.address.email;
                document.getElementById('edit_phone').value = data.address.phone;
                document.getElementById('edit_address').value = data.address.address;
                document.getElementById('editAddressModal').style.display = 'block';
            } else {
                alert('Không tìm thấy địa chỉ!');
            }
            });
        };
    });
    // Đóng modal sửa
    document.getElementById('closeEditModal').onclick = function() {
        document.getElementById('editAddressModal').style.display = 'none';
    };
    document.getElementById('closeEditModalBtn').onclick = function() {
        document.getElementById('editAddressModal').style.display = 'none';
    };
    // Submit sửa
    document.getElementById('editAddressForm').onsubmit = function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        fetch('edit_address.php', {
        method: 'POST',
        body: formData
        })
        .then(res => res.json())
        .then(data => {
        if(data.success) {
            Swal.fire({icon:'success',title:'Cập nhật thành công!',showConfirmButton:false,timer:1200});
            document.getElementById('editAddressModal').style.display = 'none';
            setTimeout(function(){ location.reload(); }, 1200);
        } else {
            Swal.fire({icon:'error',title:'Lỗi',text:data.message});
        }
        });
    };
    // Xoá địa chỉ
    document.querySelectorAll('.btn-delete-address').forEach(function(btn) {
        btn.onclick = function() {
        var id = this.getAttribute('data-id');
        Swal.fire({
            title: 'Bạn chắc chắn muốn xoá?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xoá',
            cancelButtonText: 'Huỷ'
        }).then((result) => {
            if(result.isConfirmed) {
            fetch('delete_address.php', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'id='+encodeURIComponent(id)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                Swal.fire({icon:'success',title:'Đã xoá!',showConfirmButton:false,timer:1000});
                setTimeout(function(){ location.reload(); }, 1000);
                } else {
                Swal.fire({icon:'error',title:'Lỗi',text:data.message});
                }
            });
            }
        });
        };
    });
    // Chọn vị trí mặc định
    document.querySelectorAll('.btn-default-address').forEach(function(btn) {
        btn.onclick = function() {
        var id = this.getAttribute('data-id');
        fetch('set_default_address.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'id='+encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
            Swal.fire({icon:'success',title:'Đã chọn làm mặc định!',showConfirmButton:false,timer:1000});
            setTimeout(function(){ location.reload(); }, 1000);
            } else {
            Swal.fire({icon:'error',title:'Lỗi',text:data.message});
            }
        });
        };
    });
    });
</script>
</body>
</html>
