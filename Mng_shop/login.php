<?php
include __DIR__.'/connection/connect.php';
session_start();
$register_success = false;
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reg_username'])) {
    $username = $_POST['reg_username'];
    $password = $_POST['reg_password'];
    $confirm = $_POST['reg_confirm'];
    $shop_name = $_POST['shop_name'];
    $owner_name = $_POST['owner_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $shop_type = $_POST['shop_type'];
    $open_time = $_POST['open_time'];
    $close_time = $_POST['close_time'];
    if ($password !== $confirm) {
        $register_error = 'Mật khẩu xác nhận không khớp!';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users_shop WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $register_error = 'Tên đăng nhập đã tồn tại!';
        } else {
            $stmt = $pdo->prepare('INSERT INTO shops (shop_name, address, phone, email, open_time, close_time, owner_name, shop_type) VALUES (?,?,?,?,?,?,?,?)');
            $stmt->execute([$shop_name, $address, $phone, $email, $open_time, $close_time, $owner_name, $shop_type]);
            $shop_id = $pdo->lastInsertId();
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users_shop (username, password, role, shop_id) VALUES (?,?,?,?)');
            $stmt->execute([$username, $hash, 'owner', $shop_id]);
            $register_success = true;
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare('SELECT * FROM users_shop WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo '<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><meta http-equiv="refresh" content="1.5;url=index.php"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Đăng nhập thành công</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head><body style="background:#f8f9fa;">';
        echo '<div style="position:fixed;top:20px;left:20px;z-index:9999;min-width:260px;max-width:90vw;" class="alert alert-success shadow fade show"><i class="fa fa-check-circle me-2"></i> Đăng nhập thành công!</div>';
        echo '<div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10000;text-align:center;">';
        echo '<div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status"></div>';
        echo '<div class="mt-3 text-primary">Đang chuyển hướng...</div>';
        echo '</div>';
        echo '<script>setTimeout(function(){window.location.href="index.php";},1500);</script>';
        echo '</body></html>';
        exit;
    } else {
        $login_error = 'Tên đăng nhập hoặc mật khẩu không đúng!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Quản lý cửa hàng</title>
    <link rel="icon" href="../images/img/shopp.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../Mng_shop/css/login.css">
    <link rel="stylesheet" href="../Mng_shop/css/register.css">
</head>
<body>
  <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success']): ?>
    <div id="loginSuccessAlert" style="position:fixed;top:20px;left:20px;z-index:9999;min-width:260px;max-width:90vw;" class="alert alert-success shadow fade show">
      <i class="fa fa-check-circle me-2"></i> Đăng nhập thành công!
    </div>
    <script>
      setTimeout(function(){
        var alertBox = document.getElementById('loginSuccessAlert');
        if(alertBox) alertBox.style.display = 'none';
      }, 2500);
    </script>
    <?php unset($_SESSION['login_success']); ?>
  <?php endif; ?>
  <div class="login-bg-anim">
    <div class="circle circle1"></div>
    <div class="circle circle2"></div>
    <div class="circle circle3"></div>
  </div>
  <div class="center-container">
    <div class="login-box" id="loginBox">
    <div class="login-logo">
      <i class="fa-solid fa-store"></i>
      <span>Shop Manager</span>
    </div>
    <h4 class="mb-4 text-center text-primary">Đăng nhập hệ thống quản lý cửa hàng</h4>
    <?php if (!empty($login_error)): ?>
      <div class="alert alert-danger text-center"> <?php echo $login_error; ?> </div>
    <?php endif; ?>
    <form method="post" action="">
      <div class="mb-3">
        <label for="username" class="form-label">Tên đăng nhập</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên đăng nhập" required autofocus>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <input type="checkbox" id="remember" name="remember">
          <label for="remember" class="form-check-label">Ghi nhớ đăng nhập</label>
        </div>
        <a href="#" class="text-decoration-none text-primary">Quên mật khẩu?</a>
      </div>
      <button type="submit" class="btn btn-login w-100 py-2">Đăng nhập <i class="fa fa-arrow-right ms-2"></i></button>
    </form>
    <div class="mt-3 text-center">
      <span>Bạn chưa có tài khoản? <a href="#" class="text-primary" onclick="showRegister()">Đăng ký ngay</a></span>
    </div>
  </div>

    <div class="login-box" id="registerBox" style="display:none; position:absolute; top:0; left:0; right:0; margin:auto;">
    <div class="login-logo">
      <i class="fa-solid fa-store"></i>
      <span>Shop Manager</span>
    </div>
    <h4 class="mb-4 text-center text-success">Đăng ký quản lý cửa hàng mới</h4>
    <?php if (!empty($register_error)): ?>
      <div class="alert alert-danger text-center"> <?php echo $register_error; ?> </div>
    <?php endif; ?>
    <?php if ($register_success): ?>
      <script>
        setTimeout(function(){
          alert('Đăng ký thành công!');
          showLogin();
        }, 300);
      </script>
    <?php endif; ?>
    <form method="post" action="">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="reg_username" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="reg_username" name="reg_username" placeholder="Nhập tên đăng nhập" required>
          </div>
          <div class="mb-3">
            <label for="reg_password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="reg_password" name="reg_password" placeholder="Nhập mật khẩu" required>
          </div>
          <div class="mb-3">
            <label for="reg_confirm" class="form-label">Xác nhận mật khẩu</label>
            <input type="password" class="form-control" id="reg_confirm" name="reg_confirm" placeholder="Nhập lại mật khẩu" required>
          </div>
          <div class="mb-3">
            <label for="shop_name" class="form-label">Tên cửa hàng</label>
            <input type="text" class="form-control" id="shop_name" name="shop_name" placeholder="Nhập tên cửa hàng" required>
          </div>
          <div class="mb-3">
            <label for="owner_name" class="form-label">Tên chủ cửa hàng</label>
            <input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="Nhập tên chủ cửa hàng" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email" required>
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ cửa hàng</label>
            <div class="input-group">
              <input type="text" class="form-control" id="address" name="address" placeholder="Nhập địa chỉ cửa hàng" required>
              <button type="button" class="btn btn-outline-secondary" onclick="openMapPopup()">Lấy từ Google Maps</button>
            </div>
          </div>
          <div class="mb-3">
            <label for="shop_type" class="form-label">Loại hình kinh doanh</label>
            <select class="form-control" id="shop_type" name="shop_type" required>
              <option value="">-- Chọn loại hình --</option>
              <option value="Nhà hàng">Nhà hàng</option>
              <option value="Quán ăn">Quán ăn</option>
              <option value="Café">Café</option>
              <option value="Trà sữa">Trà sữa</option>
              <option value="Khác">Khác</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="open_time" class="form-label">Giờ mở cửa</label>
            <input type="time" class="form-control" id="open_time" name="open_time" required>
          </div>
          <div class="mb-3">
            <label for="close_time" class="form-label">Giờ đóng cửa</label>
            <input type="time" class="form-control" id="close_time" name="close_time" required>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-success w-100 py-2">Đăng ký <i class="fa fa-user-plus ms-2"></i></button>
    </form>
    <div class="mt-3 text-center">
      <span>Đã có tài khoản? <a href="#" class="text-primary" onclick="showLogin()">Đăng nhập</a></span>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function openMapPopup() {
      window.open('https://www.google.com/maps', 'mapPopup', 'width=800,height=600');
      setTimeout(function(){
        alert('Hãy copy địa chỉ từ Google Maps và dán vào ô địa chỉ!');
      }, 500);
    }
    function showRegister() {
      document.getElementById('loginBox').style.display = 'none';
      document.getElementById('registerBox').style.display = 'block';
      document.getElementById('registerBox').style.position = 'relative';
      document.getElementById('registerBox').style.margin = '0 auto';
      document.getElementById('registerBox').animate([
        { transform: 'translateX(100px)', opacity: 0 },
        { transform: 'translateX(0)', opacity: 1 }
      ], { duration: 400, fill: 'forwards' });
    }
    function showLogin() {
      document.getElementById('registerBox').style.display = 'none';
      document.getElementById('loginBox').style.display = 'block';
      document.getElementById('loginBox').animate([
        { transform: 'translateX(-100px)', opacity: 0 },
        { transform: 'translateX(0)', opacity: 1 }
      ], { duration: 400, fill: 'forwards' });
    }
  </script>
  </div>
</body>
</html>
