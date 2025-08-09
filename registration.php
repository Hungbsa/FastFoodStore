<!DOCTYPE html>
<html lang="en">
<?php
  session_start(); 
  error_reporting(0); 
  include("connection/connect.php"); 
  $message = $success = '';
  if(isset($_POST['submit'])) 
  {
      $username = trim($_POST['username']);
      $firstname = trim($_POST['firstname']);
      $lastname = trim($_POST['lastname']);
      $email = trim($_POST['email']);
      $phone = trim($_POST['phone']);
      $password = $_POST['password'];
      $cpassword = $_POST['cpassword'];
      $address = trim($_POST['address']);
      if(empty($username) || empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($password) || empty($cpassword)) {
          $message = "Tất cả thông tin cần phải được điền!";
      } elseif(strlen($password) < 6) {
          $message = "Mật khẩu phải nhiều hơn 6 ký tự hoặc số.";
      } elseif($password !== $cpassword) {
          $message = "Mật khẩu nhập lại không khớp.";
      } elseif(strlen($phone) < 7) {
          $message = "Số điện thoại phải có ít nhất 7 số!";
      } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $message = "Email không hợp lệ, hãy nhập lại!";
      } else {
          $check_username = mysqli_query($db, "SELECT username FROM users WHERE username = '".mysqli_real_escape_string($db, $username)."'");
          $check_email = mysqli_query($db, "SELECT email FROM users WHERE email = '".mysqli_real_escape_string($db, $email)."'");
          if(mysqli_num_rows($check_username) > 0) {
              $message = "Tên đăng nhập này đã có sẵn!";
          } elseif(mysqli_num_rows($check_email) > 0) {
              $message = "Email đã tồn tại!";
          } else {
              $mql = "INSERT INTO users(username,f_name,l_name,email,phone,password,address) VALUES('".mysqli_real_escape_string($db, $username)."','".mysqli_real_escape_string($db, $firstname)."','".mysqli_real_escape_string($db, $lastname)."','".mysqli_real_escape_string($db, $email)."','".mysqli_real_escape_string($db, $phone)."','".md5($password)."','".mysqli_real_escape_string($db, $address)."')";
              if(mysqli_query($db, $mql)) {
                  $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
                  echo "<script>setTimeout(function(){ window.location='login.php'; }, 1500); showToast('Đăng ký thành công!');</script>";
              } else {
                  $message = "Có lỗi xảy ra, vui lòng thử lại sau.";
              }
          }
      }
  }
?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/img/iconss.png">
    <title>Registration</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> </head>
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
         <div class="page-wrapper">
            
               <div class="container">
                  <ul>
                  </ul>
               </div>
            
<section style="background: linear-gradient(120deg, #f8f8f8 60%, #e3f2fd 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
  <div style="position:relative; width:100%; max-width: 1100px; min-height: 600px; display: flex; align-items: center; justify-content: center;">
    <!-- Background image with overlay -->
    <div style="position:absolute; left:0; top:0; width:100%; height:100%; z-index:1; border-radius: 24px; overflow: hidden;">
      <img src='images/img/icons-new2.jpg' alt='' style="width:100%; height:100%; object-fit:cover; filter: blur(2px) brightness(0.93); opacity:0.18;" />
      <div style="position:absolute; left:0; top:0; width:100%; height:100%; background:linear-gradient(120deg,rgba(255,255,255,0.7) 60%,rgba(33,150,243,0.08) 100%);"></div>
    </div>
    <!-- Registration form -->
    <div style="position:relative; z-index:2; width:100%; max-width: 440px; margin: 0 auto; background: #fff; border-radius: 18px; box-shadow: 0 4px 32px rgba(33,150,243,0.10), 0 1.5px 8px rgba(0,0,0,0.07); padding: 38px 32px 28px 32px; display: flex; flex-direction: column; align-items: center;">
      <div style="display:flex; align-items:center; justify-content:center; margin-bottom: 10px;">
        <img src="images/img/newimg.jpg" alt="FastFood" style="width: 54px; height: 54px; border-radius: 12px; box-shadow: 0 2px 8px rgba(33,150,243,0.08); background: #fff; margin-right: 10px;">
        <span style="font-weight: bold; font-size: 1.45rem; color: #039be5; letter-spacing: 1px;">FastFood</span>
      </div>
      <h2 style="text-align:center; font-weight:800; color:#222; margin-bottom: 10px; font-size:1.45rem; letter-spacing:0.5px;">Đăng ký tài khoản</h2>
      <div style="text-align:center; color:#666; font-size:1.07rem; margin-bottom:16px;">Nhanh chóng, an toàn và tiện lợi cho mọi đơn hàng!</div>
      <?php if(!empty($message)): ?>
        <div style="background:#f8d7da; color:#a94442; border-radius:6px; padding:10px; margin-bottom:18px; text-align:center; font-size:1.07rem; border:1px solid #f5c6cb;">
          <?php echo $message; ?>
        </div>
      <?php endif; ?>
      <?php if(!empty($success)): ?>
        <div style="background:#d4edda; color:#155724; border-radius:6px; padding:10px; margin-bottom:18px; text-align:center; font-size:1.07rem; border:1px solid #c3e6cb;">
          <?php echo $success; ?>
        </div>
      <?php endif; ?>
      <form action="" method="post" autocomplete="off" style="width:100%;" onsubmit="return validatePolicy();">
        <div style="position:relative; margin-bottom: 15px;">
          <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#90caf9; font-size:1.13rem;"><i class="fa fa-user"></i></span>
          <input type="text" name="username" placeholder="Tên đăng nhập" style="width:100%; padding:12px 15px 12px 42px; border:1px solid #e3eaf1; border-radius:7px; font-size:1.07rem; background:#fafdff;" required>
        </div>
        <div style="display: flex; gap: 12px; margin-bottom: 15px;">
          <div style="position:relative; flex:1;">
            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#90caf9; font-size:1.13rem;"><i class="fa fa-user"></i></span>
            <input type="text" name="lastname" placeholder="Họ" style="width:100%; padding:12px 15px 12px 42px; border:1px solid #e3eaf1; border-radius:7px; font-size:1.07rem; background:#fafdff;" required>
          </div>
          <div style="position:relative; flex:1;">
            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#90caf9; font-size:1.13rem;"><i class="fa fa-user"></i></span>
            <input type="text" name="firstname" placeholder="Tên" style="width:100%; padding:12px 15px 12px 42px; border:1px solid #e3eaf1; border-radius:7px; font-size:1.07rem; background:#fafdff;" required>
          </div>
        </div>
        <div style="position:relative; margin-bottom: 15px;">
          <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#90caf9; font-size:1.13rem;"><i class="fa fa-envelope"></i></span>
          <input type="email" name="email" placeholder="Email" style="width:100%; padding:12px 15px 12px 42px; border:1px solid #e3eaf1; border-radius:7px; font-size:1.07rem; background:#fafdff;" required>
        </div>
        <div style="position:relative; margin-bottom: 15px;">
          <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#90caf9; font-size:1.13rem;"><i class="fa fa-phone"></i></span>
          <input type="tel" name="phone" placeholder="Số điện thoại" style="width:100%; padding:12px 15px 12px 42px; border:1px solid #e3eaf1; border-radius:7px; font-size:1.07rem; background:#fafdff;" required>
        </div>
        <div style="display: flex; gap: 12px; margin-bottom: 15px;">
          <div style="position:relative; flex:1;">
            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#90caf9; font-size:1.13rem;"><i class="fa fa-lock"></i></span>
            <input type="password" name="password" placeholder="Mật khẩu" style="width:100%; padding:12px 15px 12px 42px; border:1px solid #e3eaf1; border-radius:7px; font-size:1.07rem; background:#fafdff;" required>
          </div>
          <div style="position:relative; flex:1;">
            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#90caf9; font-size:1.13rem;"><i class="fa fa-lock"></i></span>
            <input type="password" name="cpassword" placeholder="Nhập lại mật khẩu" style="width:100%; padding:12px 15px 12px 42px; border:1px solid #e3eaf1; border-radius:7px; font-size:1.07rem; background:#fafdff;" required>
          </div>
        </div>
        <div style="position:relative; margin-bottom: 18px;">
          <span style="position:absolute; left:14px; top:18px; color:#90caf9; font-size:1.13rem;"><i class="fa fa-map-marker"></i></span>
          <textarea id="address-input" name="address" rows="2" placeholder="Địa chỉ giao hàng" style="width:100%; padding:12px 15px 12px 42px; border:1px solid #e3eaf1; border-radius:7px; font-size:1.07rem; background:#fafdff;"></textarea>
          <button type="button" onclick="getLocationReg()" style="position:absolute; right:14px; top:14px; background:#fff; border:none; color:#039be5; font-size:1.25rem; cursor:pointer; padding:4px 8px; border-radius:6px; box-shadow:0 1px 4px rgba(33,150,243,0.08);" title="Lấy vị trí hiện tại">
            <i class="fa fa-location-arrow"></i>
          </button>
        </div>
        <div style="margin-bottom: 18px; display:flex; align-items:center;">
          <input type="checkbox" id="policyCheck" name="policy" style="margin-right:8px; width:18px; height:18px;" onclick="toggleRegisterBtn()">
          <label for="policyCheck" style="margin:0; color:#444; font-size:1.01rem;">
            Tôi đã đọc, đồng ý với <a href="#" style="color:#039be5; text-decoration:underline;" target="_blank">Chính sách bảo vệ dữ liệu cá nhân</a> & <a href="#" style="color:#039be5; text-decoration:underline;" target="_blank">Quy định sử dụng</a> của FastFood
          </label>
        </div>
        <button type="submit" id="registerBtn" name="submit" style="width:100%; background:linear-gradient(90deg,#039be5 60%,#00c6fb 100%); color:#fff; border:none; padding:14px 0; border-radius:7px; font-weight:700; font-size:1.13rem; letter-spacing:0.5px; box-shadow:0 2px 8px rgba(33,150,243,0.08); transition:background 0.2s; cursor:pointer; opacity:0.5; pointer-events:none;">Đăng ký</button>
      </form>
      <div style="text-align:center; margin-top:18px; color:#666; font-size:1.05rem;">Đã có tài khoản? <a href="login.php" style="color:#039be5; text-decoration:underline; font-weight:600;">Đăng nhập</a></div>
    </div>
  </div>
</section>
            
      
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
         
         </div>
       
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>

<!-- Toast thông báo -->
<div id="toast-success" style="display:none; position:fixed; top:32px; right:32px; z-index:99999; background:#43a047; color:#fff; padding:16px 28px; border-radius:8px; font-size:1.08rem; box-shadow:0 2px 12px rgba(0,0,0,0.13); font-weight:500;">
  <i class="fa fa-check-circle" style="margin-right:8px;"></i> <span id="toast-msg">Đăng ký thành công!</span>
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
<!-- thông báo đăng ký -->
<script>
    function toggleRegisterBtn() {
      var cb = document.getElementById('policyCheck');
      var btn = document.getElementById('registerBtn');
      if(cb.checked) {
        btn.style.opacity = '1';
        btn.style.pointerEvents = 'auto';
      } else {
        btn.style.opacity = '0.5';
        btn.style.pointerEvents = 'none';
      }
    }
    function validatePolicy() {
      var cb = document.getElementById('policyCheck');
      if(!cb.checked) {
        alert('Bạn cần đồng ý với chính sách và quy định để đăng ký!');
        return false;
      }
      return true;
  }
</script>
<script>
  function getLocationReg() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function(position) {
          var lat = position.coords.latitude;
          var lng = position.coords.longitude;
          // Gọi Nominatim API để lấy địa chỉ thực tế
          fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
              var address = data.display_name || '';
              document.getElementById('address-input').value = address;
              alert('Đã lấy vị trí: ' + address);
            })
            .catch(() => {
              alert('Không thể lấy địa chỉ từ vị trí!');
            });
        },
        function(error) {
          alert('Không thể lấy vị trí: ' + error.message);
        }
      );
    } else {
      alert("Trình duyệt không hỗ trợ Geolocation");
    }
  }
</script>
</body>

</html>