<?php
include("connection/connect.php");
session_start();
if(empty($_SESSION['user_id']))  {
    header('location:login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="icon" href="images/img/iconss.png">
    <title>Ví Voucher</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .voucher-wallet-container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 32px 24px; }
        .voucher-wallet-title { font-size: 1.5rem; font-weight: bold; color: #ff9800; margin-bottom: 18px; }
        .voucher-list { list-style: none; padding: 0; margin: 0; }
        .voucher-item { display: flex; align-items: center; gap: 18px; background: #f8f9fa; border-radius: 10px; margin-bottom: 16px; padding: 16px 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); }
        .voucher-icon { font-size: 2.2rem; color: #2196f3; }
        .voucher-info { flex: 1; }
        .voucher-title { font-size: 1.1rem; font-weight: 600; color: #222; }
        .voucher-desc { color: #666; font-size: 0.98rem; }
        .voucher-expiry { color: #d32f2f; font-size: 0.95rem; margin-top: 2px; }
        .voucher-empty { text-align: center; color: #aaa; font-size: 1.1rem; margin-top: 40px; }
    </style>
</head>
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
                        // Lấy tên user và ảnh đại diện từ database
                        $uid = $_SESSION["user_id"];
                        $userq = mysqli_query($db, "SELECT username, picture FROM users WHERE u_id='$uid' LIMIT 1");
                        $udata = mysqli_fetch_assoc($userq);
                        $username = $udata ? $udata['username'] : 'User';
                        $firstChar = strtoupper(substr($username,0,1));
                        $picture = ($udata && !empty($udata['picture'])) ? $udata['picture'] : '';
                        // Xử lý đường dẫn ảnh cho đúng thư mục
                        $avatarPath = $picture;
                        if ($avatarPath && strpos($avatarPath, 'images/') === 0) {
                            $avatarPath = $avatarPath;
                        }
                        echo '<li class="nav-item dropdown" style="margin-left: 12px;">';
                        echo '<a href="#" class="nav-link active dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center; gap: 8px; color: #222; background: #fff; border-radius: 22px; padding: 4px 16px 4px 6px; font-weight: 600; font-size: 1.08rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">';
                        if ($picture) {
                        echo '<span style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: #c2185b; color: #fff; border-radius: 50%; font-size: 1.3rem; font-weight: bold; overflow:hidden;"><img src="'.$avatarPath.'" alt="avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;"></span>';
                        }else {
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


<body style="background: #f5f5f5;">
    <div class="title text-xs-center m-b-30">
                    <h2></h2>
                    <p class="lead">.</p>
                </div>
    <div class="voucher-wallet-container" id="voucherWallet" style="margin-top: 90px;">
        <div class="voucher-wallet-title"><i class="fa fa-ticket"></i> Ví Voucher</div>
        <ul class="voucher-list" id="voucherList">
        <?php
        // Lấy coupon từ bảng coupons (chỉ lấy coupon còn hạn, active=1)
        $today = date('Y-m-d H:i:s');
        $q = mysqli_query($db, "SELECT * FROM coupons WHERE active=1 AND (expiry_date IS NULL OR expiry_date >= '$today') ORDER BY expiry_date ASC");
        if(mysqli_num_rows($q) > 0) {
            while($v = mysqli_fetch_assoc($q)) {
                $type = $v['discount_type'] == 'fixed' ? 'Giảm trực tiếp' : 'Giảm ';
                $value = $v['discount_type'] == 'fixed' ? (number_format($v['discount_value'],0,',','.') . ' VNĐ') : ($v['discount_value'] . '%');
                $max = $v['max_discount'] ? ('Tối đa ' . number_format($v['max_discount'],0,',','.') . ' VNĐ') : '';
                $expiry = $v['expiry_date'] ? date('d/m/Y', strtotime($v['expiry_date'])) : 'Không giới hạn';
                $desc = $type . ' ' . $value . ($max ? ' (' . $max . ')' : '');
                echo '<li class="voucher-item voucher-hover" tabindex="0" onclick="highlightVoucher(this)" onkeydown="if(event.key==\'Enter\'){highlightVoucher(this)}">';
                echo '<span class="voucher-icon"><i class="fa fa-ticket"></i></span>';
                echo '<div class="voucher-info">';
                echo '<div class="voucher-title">'.$v['code'].' - '.$desc.'</div>';
                echo '<div class="voucher-desc">Số lần dùng: '.($v['times_used'] ?? 0).' / '.($v['usage_limit'] ?? '∞').'</div>';
                echo '<div class="voucher-expiry">HSD: '.$expiry.'</div>';
                echo '</div>';
                echo '<button class="btn btn-sm btn-outline-primary voucher-copy-btn" style="margin-left:12px;" onclick="event.stopPropagation();copyVoucherCode(\''.$v['code'].'\', this)"><i class="fa fa-copy"></i> Sao chép</button>';
                echo '</li>';
            }
        } else {
            echo '<div class="voucher-empty">Bạn chưa có coupon nào còn hiệu lực.</div>';
        }
        ?>
        </ul>
        <a href="index.php" class="btn btn-secondary" style="margin-top: 18px;"><i class="fa fa-arrow-left"></i> Quay lại trang chủ</a>
    </div>
    <script>
    // Hiệu ứng hover và chọn voucher
    function highlightVoucher(el) {
        document.querySelectorAll('.voucher-item.selected').forEach(e => e.classList.remove('selected'));
        el.classList.add('selected');
    }
    // Sao chép mã voucher
    function copyVoucherCode(code, btn) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(function() {
                btn.innerHTML = '<i class="fa fa-check"></i> Đã sao chép';
                setTimeout(()=>{btn.innerHTML = '<i class="fa fa-copy"></i> Sao chép';}, 1200);
            });
        } else {
            // fallback
            var temp = document.createElement('input');
            temp.value = code;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            btn.innerHTML = '<i class="fa fa-check"></i> Đã sao chép';
            setTimeout(()=>{btn.innerHTML = '<i class="fa fa-copy"></i> Sao chép';}, 1200);
        }
    }
    </script>
    <style>
    .voucher-item.voucher-hover { cursor: pointer; transition: box-shadow 0.2s, border 0.2s; border: 2px solid transparent; }
    .voucher-item.voucher-hover:hover, .voucher-item.selected { border: 2px solid #2196f3; box-shadow: 0 4px 16px rgba(33,150,243,0.08); background: #e3f2fd; }
    .voucher-copy-btn { transition: background 0.2s, color 0.2s; }
    .voucher-copy-btn:active, .voucher-copy-btn:focus { background: #2196f3; color: #fff; border-color: #2196f3; }
    @media (max-width: 600px) {
        .voucher-wallet-container { padding: 18px 4px; }
        .voucher-title { font-size: 1rem; }
        .voucher-item { flex-direction: column; align-items: flex-start; gap: 8px; }
        .voucher-copy-btn { width: 100%; margin-left: 0; margin-top: 8px; }
    }
    </style>
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
