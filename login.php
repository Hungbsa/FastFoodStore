<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="images/img/iconss.png">
    <style type="text/css">
      #buttn{
        color:#fff;
        background-color: #5c4ac7;
      }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
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
                        $userq = mysqli_query($db, "SELECT username FROM users WHERE u_id='$uid' LIMIT 1");
                        $udata = mysqli_fetch_assoc($userq);
                        $username = $udata ? $udata['username'] : 'User';
                        $firstChar = strtoupper(substr($username,0,1));
                        echo '<li class="nav-item dropdown" style="margin-left: 12px;">';
                        echo '<a href="#" class="nav-link active dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center; gap: 8px; color: #222; background: #fff; border-radius: 22px; padding: 4px 16px 4px 6px; font-weight: 600; font-size: 1.08rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">';
                        echo '<span style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: #c2185b; color: #fff; border-radius: 50%; font-size: 1.3rem; font-weight: bold;">'.$firstChar.'</span>';
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


<div style=" background-image: url('images/img/banner.jpg');">


<?php
  include("connection/connect.php"); 
  error_reporting(0); 
  session_start(); 
  $message = $success = '';
  if(isset($_POST['submit']))  
  {
    $username = trim($_POST['username']);  
    $password = $_POST['password'];
    if(empty($username) || empty($password)) {
      $message = "Bạn cần điền thông tin tài khoản và mật khẩu!";
    } else if(!empty($_POST["submit"]))   
    {
      $userq = mysqli_query($db, "SELECT * FROM users WHERE username='".mysqli_real_escape_string($db, $username)."' OR email='".mysqli_real_escape_string($db, $username)."' LIMIT 1");
      $row = mysqli_fetch_array($userq);
      $isValid = false;
      if($row) {
        // Hỗ trợ password_hash, plain text, md5
        if (
          password_verify($password, $row['password']) ||
          $row['password'] === $password ||
          md5($password) === $row['password']
        ) {
          $isValid = true;
        }
      }
      if(!$row) {
        $message = "Tài khoản không tồn tại!";
      } else if (!$isValid) {
        $message = "Mật khẩu không đúng!";
      } else {
        $_SESSION["user_id"] = $row['u_id']; 
        $success = "Đăng nhập thành công!";
        echo "<script>setTimeout(function(){ window.location='index.php'; }, 1200); showToast('Đăng nhập thành công!');</script>";
      }
    }
  }
?>
  
<!-- login form -->
  <div class="pen-title">
    <
  </div>
  <div class="module form-module">
    <div class="toggle">
    </div>
    <div class="form">
      <h2>Đăng nhập Ngay</h2>
      <span style="color:red;"><?php echo $message; ?></span> 
    <span style="color:green;"><?php echo $success; ?></span>
      <form action="" method="post" autocomplete="off">
        <div style="text-align:center; color:#888; font-size:1.01rem; margin-bottom:8px;">Bạn có thể đăng nhập bằng <b>tên đăng nhập</b> hoặc <b>email</b></div>
        <div style="position:relative; margin-bottom:16px;">
          <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#888; font-size:1.15rem;"><i class="fa fa-user"></i></span>
          <input type="text" placeholder="Tên đăng nhập/Email" name="username" style="width:100%; padding:12px 12px 12px 40px; border-radius:6px; border:1px solid #bbb; font-size:1.08rem;" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" />
        </div>
        <div style="position:relative; margin-bottom:16px;">
          <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#888; font-size:1.15rem;"><i class="fa fa-lock"></i></span>
          <input type="password" placeholder="Mật khẩu" name="password" style="width:100%; padding:12px 12px 12px 40px; border-radius:6px; border:1px solid #bbb; font-size:1.08rem;" />
        </div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:18px;">
          <label style="display:flex; align-items:center; font-size:1.01rem; color:#444;">
            <input type="checkbox" name="remember" style="margin-left:-10px;" /> Lưu thông tin
          </label>
          <a href="#" onclick="showForgotPopup();return false;" style="color:#2196f3; font-size:1.01rem; text-decoration:none;">Quên mật khẩu?</a>
        </div>
        <input type="submit" id="buttn" name="submit" value="Đăng nhập" style="width:100%; background:#039be5; color:#fff; font-weight:600; font-size:1.13rem; border:none; border-radius:6px; padding:12px 0; margin-bottom:6px; letter-spacing:1px; transition:background 0.2s;" />
      </form>
    </div>

    <div class="cta">Chưa Đăng Ký?<a href="registration.php" style="color:#5c4ac7;"> Tạo tài khoản</a></div>
  </div>
<!-- login form -->

<!-- Popup Quên mật khẩu -->
<div id="forgotPopup" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.25);">
  <div style="background:#fff; max-width:350px; margin:120px auto 0 auto; border-radius:10px; box-shadow:0 2px 16px rgba(0,0,0,0.13); padding:28px 22px 18px 22px; position:relative;">
    <span onclick="closeForgotPopup()" style="position:absolute; right:16px; top:10px; color:#888; font-size:1.3rem; cursor:pointer;"><i class="fa fa-times"></i></span>
    <h4 style="margin-bottom:16px; color:#222;">Quên mật khẩu?</h4>
    <form onsubmit="return sendForgotEmail();">
      <input type="email" id="forgotEmail" placeholder="Nhập email của bạn" required style="width:100%; padding:10px 12px; border-radius:6px; border:1px solid #bbb; font-size:1.05rem; margin-bottom:14px;" />
      <button type="submit" style="width:100%; background:#039be5; color:#fff; font-weight:600; font-size:1.08rem; border:none; border-radius:6px; padding:10px 0;">Gửi yêu cầu</button>
    </form>
    <div id="forgotMsg" style="margin-top:10px; color:#388e3c; font-size:1.01rem; display:none;"></div>
  </div>
</div>
  <script>
    function showForgotPopup() {
      document.getElementById('forgotPopup').style.display = 'block';
      document.getElementById('forgotMsg').style.display = 'none';
      document.getElementById('forgotEmail').value = '';
    }
    function closeForgotPopup() {
      document.getElementById('forgotPopup').style.display = 'none';
    }
    function sendForgotEmail() {
    var email = document.getElementById('forgotEmail').value;
    var msg = document.getElementById('forgotMsg');
    msg.style.display = 'block';
    msg.style.color = '#888';
    msg.innerHTML = 'Đang gửi...';
    fetch('phpmailer/forgot_password_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'email=' + encodeURIComponent(email)
    })
    .then(response => response.json())
    .then(data => {
      msg.style.display = 'block';
      msg.style.color = data.success ? '#388e3c' : '#d32f2f';
      msg.innerHTML = data.msg;
      msg.innerHTML = data.msg;
    })
    .catch(() => {
      msg.style.display = 'block';
      msg.style.color = '#d32f2f';
      msg.innerHTML = 'Có lỗi xảy ra, vui lòng thử lại!';
    });
    return false;
   }
</script>

<div id="toast-success" style="display:none; position:fixed; top:32px; right:32px; z-index:99999; background:#43a047; color:#fff; padding:16px 28px; border-radius:8px; font-size:1.08rem; box-shadow:0 2px 12px rgba(0,0,0,0.13); font-weight:500;">
  <i class="fa fa-check-circle" style="margin-right:8px;"></i> <span id="toast-msg">Đăng nhập thành công!</span>
</div>
<script>
  function showToast(msg) {
    var toast = document.getElementById('toast-success');
    document.getElementById('toast-msg').innerText = msg;
    toast.style.display = 'block';
    setTimeout(function(){ toast.style.display = 'none'; }, 2000);
    }

  <?php if (!empty($success)) { echo "showToast('".addslashes($success)."');"; } ?>
</script>

  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script> 
  <div class="container-fluid pt-3">
  <p></p>
  </div>
        <footer class="footer">
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
       


</body>

</html>
