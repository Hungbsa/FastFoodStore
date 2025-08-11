<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php"); 
error_reporting(0);
session_start();

include_once 'product-action.php';
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="images\img\iconss.png">
    <title>Món ăn - FastFood</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .similar-restaurants {
            margin-top: 30px;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }
        .similar-restaurant-card {
            display: flex;
            flex-direction: column;
            width: 220px;
            min-width: 220px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            overflow: hidden;
            text-decoration: none;
            color: #222;
            transition: box-shadow 0.2s;
            border: 1px solid #f2f2f2;
        }
        .similar-restaurant-card:hover {
            box-shadow: 0 4px 18px rgba(0,0,0,0.13);
            border-color: #e0e0e0;
        }
        .similar-restaurant-img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            background: #f5f5f5;
        }
        .similar-restaurant-info {
            padding: 12px 14px 10px 14px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .similar-restaurant-title {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 4px;
            min-height: 38px;
        }
        .similar-restaurant-address {
            color: #666;
            font-size: 13px;
            margin-bottom: 6px;
            min-height: 32px;
        }
        .similar-restaurant-rating {
            color: #ff9800;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .similar-restaurant-reviews {
            color: #888;
            font-size: 12px;
            margin-left: 6px;
        }
        .search-container {
            position: relative;
            margin-bottom: 20px;
        }
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: white;
            z-index: 1000;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }
        .search-result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.2s;
        }
        .search-result-item:hover {
            background: #f8f9fa;
        }
        #similarRestaurants {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE 10+ */
        }
        #similarRestaurants::-webkit-scrollbar {
            display: none; /* Chrome/Safari/Webkit */
        }
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


            <!-- Page Wrapper -->
<div class="page-wrapper">
    <?php
    date_default_timezone_set('Asia/Ho_Chi_Minh'); 
    $ress = mysqli_query($db, "SELECT * FROM restaurant WHERE rs_id='$_GET[res_id]'");
    $rows = mysqli_fetch_array($ress);
    
    $rating_query = mysqli_query($db, "SELECT AVG(rating) as avg_rating, COUNT(*) as rating_count FROM rating WHERE rs_id='$_GET[res_id]'");
    $rating_data = mysqli_fetch_array($rating_query);
    $avg_rating = number_format($rating_data['avg_rating'], 1);
    $rating_count = $rating_data['rating_count'];
    
    function isOpenToday($o_days) {
        $current_day = strtolower(date('D'));
        $days_ranges = explode(',', strtolower($o_days));
        
        foreach ($days_ranges as $range) {
            $range = trim($range);
            if (strpos($range, '-') !== false) {
                list($start, $end) = explode('-', $range);
                $start = trim($start);
                $end = trim($end);
                $days = ['mon' => 1, 'tue' => 2, 'wed' => 3, 'thu' => 4, 'fri' => 5, 'sat' => 6, 'sun' => 7];
                $current_num = $days[$current_day] ?? 0;
                $start_num = $days[$start] ?? 0;
                $end_num = $days[$end] ?? 0;
                
                if ($current_num >= $start_num && $current_num <= $end_num) {
                    return true;
                }
            } elseif ($range == $current_day) {
                return true;
            }
        }
        return false;
    }
    function isRestaurantOpen($o_hr, $c_hr, $o_days) {
        if (!isOpenToday($o_days)) {
            return false;
        }
        $current_time = time();
        $open_time = strtotime($o_hr);
        $close_time = strtotime($c_hr);
        if ($close_time < $open_time) {
            $close_time += 86400;
        }
        
        return ($current_time >= $open_time && $current_time <= $close_time);
    }
    
    $is_open = isRestaurantOpen($rows['o_hr'], $rows['c_hr'], $rows['o_days']);
    
    // Debug thông tin
    echo "<!-- DEBUG INFO:";
    echo "Current: ".date('H:i:s')." | ";
    echo "Open: ".$rows['o_hr']." | ";
    echo "Close: ".$rows['c_hr']." | ";
    echo "Days: ".$rows['o_days']." | ";
    echo "IsOpen: ".($is_open ? 'YES' : 'NO');
    echo "-->";
    ?>
    
    <section class="inner-page-hero bg-image" data-image-src="images/img/restrrr.png" style="background: #fff; padding: 32px 0 18px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-5 col-lg-5 col-12 mb-3 mb-md-0">
                    <div class="image-wrap" style="background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.07);">
                        <figure style="margin:0;">
                            <?php echo '<img src="Mng_shop/admin/Res_img/'.$rows['image'].'" alt="Restaurant logo" style="width:100%;height:auto;object-fit:cover;">'; ?>
                        </figure>
                    </div>
                </div>
                <div class="col-md-7 col-lg-7 col-12">
                    <div class="shop-info-block" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 28px 32px 18px 32px;">
                        <div style="font-size: 1.02rem; color: #888; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">SHOP/CỬA HÀNG</div>
                        <h2 style="font-size: 2rem; font-weight: 800; color: #222; margin-bottom: 8px; line-height: 1.2;">
                            <?php echo $rows['title']; ?>
                        </h2>
                        <div style="font-size: 1.08rem; color: #555; margin-bottom: 10px;">
                            <?php echo $rows['address']; ?>
                        </div>
                        
                        <!-- Hiển thị rating -->
                        <div style="margin-bottom: 10px;">
                            <span style="color: #ffc107; font-size: 1.25rem;">
                                <?php 
                                $full_stars = floor($avg_rating);
                                $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
                                $empty_stars = 5 - $full_stars - $half_star;
                                
                                for ($i = 0; $i < $full_stars; $i++) {
                                    echo '<i class="fa fa-star"></i> ';
                                }
                                if ($half_star) {
                                    echo '<i class="fa fa-star-half-o"></i> ';
                                }
                                for ($i = 0; $i < $empty_stars; $i++) {
                                    echo '<i class="fa fa-star-o"></i> ';
                                }
                                ?>
                            </span>
                            <span style="background: #ffc107; color: #fff; font-weight: bold; border-radius: 6px; padding: 2px 10px; font-size: 1.1rem; margin-left: 6px;">
                                <?php echo $avg_rating; ?>
                            </span>
                            <span style="color: #555; font-size: 1.05rem; margin-left: 8px;">
                                <?php echo $rating_count; ?> đánh giá trên FastFood
                            </span>
                        </div>
                        
                        <div style="margin-bottom: 10px;">
                            <a href="shop_nonimation/shop_reviews.php?res_id=<?php echo $rows['rs_id']; ?>" style="color: #2196f3; font-size: 1.01rem; text-decoration: underline;">Xem tất cả đánh giá</a>
                        </div>
                        
                        <!-- Hiển thị trạng thái mở cửa -->
                        <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 18px;">
                            <span style="color: <?php echo $is_open ? '#19c37d' : '#e74c3c'; ?>; font-weight: 600; font-size: 1.08rem;">
                                <i class="fa fa-circle" style="font-size: 0.9rem;"></i> 
                                <?php 
                                if ($is_open) {
                                    echo 'Mở cửa';
                                } else {
                                    if (!isOpenToday($rows['o_days'])) {
                                        echo 'Đóng cửa (Hôm nay nghỉ)';
                                    } elseif (time() < strtotime($rows['o_hr'])) {
                                        echo 'Đóng cửa (Mở lúc '.date('H:i', strtotime($rows['o_hr'])).')';
                                    } else {
                                        echo 'Đóng cửa (Đã hết giờ)';
                                    }
                                }
                                ?>
                            </span>
                            <span style="color: #888; font-size: 1.05rem;">
                                <i class="fa fa-clock-o"></i> 
                                <?php echo date('H:i', strtotime($rows['o_hr'])); ?> - <?php echo date('H:i', strtotime($rows['c_hr'])); ?>
                            </span>
                        </div>
                        
                        <div style="margin-bottom: 10px; color: #888; font-size: 1.05rem;">
                            <i class="fa fa-phone"></i> <?php echo $rows['phone']; ?>
                        </div>
                        
                        <div class="row" style="margin-top: 18px;">
                            <div class="col-6">
                                <div style="font-size: 0.98rem; color: #888; font-weight: 600;">WEBSITE</div>
                                <div style="color: #2196f3; font-weight: 700; font-size: 1.1rem;">
                                    <a href="<?php echo $rows['url']; ?>" target="_blank" style="color: inherit;">Xem website</a>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="font-size: 0.98rem; color: #888; font-weight: 600;">NGÀY MỞ CỬA</div>
                                <div style="color: #555; font-weight: 700; font-size: 1.1rem;">
                                    <?php 
                                    $open_days = str_replace('mon', 'Thứ 2', strtolower($rows['o_days']));
                                    $open_days = str_replace('tue', 'Thứ 3', $open_days);
                                    $open_days = str_replace('wed', 'Thứ 4', $open_days);
                                    $open_days = str_replace('thu', 'Thứ 5', $open_days);
                                    $open_days = str_replace('fri', 'Thứ 6', $open_days);
                                    $open_days = str_replace('sat', 'Thứ 7', $open_days);
                                    $open_days = str_replace('sun', 'CN', $open_days);
                                    $open_days = str_replace('-', ' đến ', $open_days);
                                    echo ucwords($open_days);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            @media (max-width: 991px) {
                .shop-info-block {padding: 18px 10px 10px 10px !important;}
            }
            @media (max-width: 767px) {
                .shop-info-block {padding: 10px 2px 8px 2px !important;}
            }
        </style>
    </section>
</div>
            <!-- Page Wrapper -->



            <div class="breadcrumb">
                <div class="container">
                   
                </div>
            </div>
            <div class="container m-t-30">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">


                     <!-- cart widget -->
                        <div class="widget widget-cart" style="border-radius: 10px; overflow: hidden; box-shadow: 0 3px 10px rgba(0,0,0,0.08);">
                            <div class="widget-heading" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); padding: 15px 20px;">
                                <h3 class="widget-title text-white" style="margin: 0; font-weight: 600;">Giỏ hàng</h3>
                                <div class="clearfix"></div>
                            </div>
                            
                            <div class="order-row bg-white" style="padding: 20px;">
                                <div class="widget-body">
                                    <!-- Discount Code Section -->
                                    <div class="discount-section" style="margin-bottom: 20px;">
                                        <h4 style="color: #555; font-size: 15px; margin-bottom: 10px; font-weight: 500;">
                                            Discount
                                        </h4>
                                        <div style="display: flex; gap: 10px;">
                                            <input type="text" id="couponCode" placeholder="Enter Here" 
                                                style="flex: 1; padding: 1px 1px; border: 1px solid #ddd; border-radius: 4px; outline: none;">
                                            <button onclick="applyCoupon()" style="background: #6a11cb; color: white; border: none; padding: 0 15px; border-radius: 4px; cursor: pointer; font-size: 1.2em;">✓</button>
                                        </div>
                                        <div id="couponMessage" style="margin-top: 8px; font-size: 13px;"></div>
                                        <script>
                                            var totalAmount = <?php echo json_encode($item_total); ?>;
                                        </script>
                                        <style>
                                            button[onclick="applyCoupon()"]:hover {
                                                background: #4d0ca8 !important;
                                                transform: scale(1.05);
                                            }
                                            #couponCode:focus {
                                                border-color: #6a11cb;
                                                box-shadow: 0 0 0 2px rgba(106, 17, 203, 0.2);
                                            }
                                        </style>
                                        <script>
                                            function applyCoupon() {
                                                const couponCode = document.getElementById('couponCode').value;
                                                if (!couponCode.trim()) {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Vui lòng nhập mã',
                                                        text: 'Bạn chưa nhập mã giảm giá',
                                                        confirmButtonColor: '#6a11cb'
                                                    });
                                                    return;
                                                }
                                                Swal.fire({
                                                    title: 'Đang kiểm tra...',
                                                    allowOutsideClick: false,
                                                    didOpen: () => Swal.showLoading()
                                                });
                                                fetch('apply_coupon.php', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/x-www-form-urlencoded',
                                                    },
                                                    body: `coupon_code=${encodeURIComponent(couponCode)}&total_amount=${encodeURIComponent(totalAmount)}`
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    Swal.close();
                                                    if (data.success) {
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Thành công',
                                                            html: `<div style="text-align:left;">
                                                                <p>Mã: <strong>${couponCode}</strong></p>
                                                                <p>Giảm: <strong style=\"color:#4CAF50\">${data.discount_amount}đ</strong></p>
                                                                ${data.remaining_uses ? `<p>Còn lại: <strong>${data.remaining_uses}</strong> lượt</p>` : ''}
                                                            </div>`,
                                                            confirmButtonColor: '#6a11cb'
                                                        });
                                                    } else {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Không thể áp dụng',
                                                            text: data.message,
                                                            confirmButtonColor: '#6a11cb'
                                                        });
                                                    }
                                                })
                                                .catch(error => {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Lỗi kết nối',
                                                        text: 'Không thể kiểm tra mã giảm giá',
                                                        confirmButtonColor: '#6a11cb'
                                                    });
                                                });
                                            }
                                        </script>
                                    </div>
                                    
                                    <!-- Cart Items -->
                                    <?php
                                    $item_total = 0;
                                    $cart_items = $_SESSION["cart_item"] ?? [];
                                    
                                    if (!empty($cart_items)) {
                                        foreach ($cart_items as $item) {
                                            $item_price = $item["price"] * $item["quantity"];
                                            $item_total += $item_price;
                                    ?>
                                    <div class="cart-item" style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                            <h5 style="margin: 0; color: #333; font-weight: 500;"><?php echo htmlspecialchars($item["title"]); ?></h5>
                                            <a href="dishes.php?res_id=<?php echo $_GET['res_id']; ?>&action=remove&id=<?php echo $item["d_id"]; ?>" 
                                            style="color: #ff4757; font-size: 16px;">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                        
                                        <div style="display: flex; justify-content: space-between;">
                                            <div>
                                                <span style="color: #666;"><?php echo number_format($item["price"], 0); ?> VNĐ</span>
                                            </div>
                                            <div>
                                                <span style="color: #666;">SL: <?php echo $item["quantity"]; ?></span>
                                            </div>
                                            <div>
                                                <span style="color: #2ecc71; font-weight: 500;"><?php echo number_format($item_price, 0); ?> VNĐ</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        }
                                    } else {
                                        echo '<div style="text-align: center; padding: 30px 0; color: #888;">
                                                <i class="fa fa-shopping-cart" style="font-size: 40px; opacity: 0.3; margin-bottom: 10px;"></i>
                                                <p>Giỏ hàng của bạn trống</p>
                                            </div>';
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <!-- Cart Total -->
                            <div class="widget-body" style="padding: 20px; background: #f9f9f9; border-top: 1px solid #eee;">
                                <div class="price-wrap">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                        <span style="color: #555;">Tạm tính</span>
                                        <span style="font-weight: 500;"><?php echo number_format($item_total, 0); ?> VNĐ</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 18px;">
                                        <span style="font-weight: 600;">Tổng cộng</span>
                                        <span style="color: #6a11cb; font-weight: 700;"><?php echo number_format($item_total, 0); ?> VNĐ</span>
                                    </div>
                                    <p style="text-align: center; color: #2ecc71; margin-bottom: 15px;">Giao hàng Nhanh chóng!</p>
                                    
                                    <div style="text-align: center;">
                                        <a href="checkout.php?res_id=<?php echo $_GET['res_id'];?>&action=check" 
                                        class="btn btn-lg <?php echo ($item_total == 0) ? 'btn-secondary disabled' : 'btn-primary'; ?>" 
                                        style="background: <?php echo ($item_total == 0) ? '#95a5a6' : 'linear-gradient(135deg, #6a11cb 0%, #2575fc 100%)'; ?>; 
                                                border: none; 
                                                padding: 10px 20px;
                                                border-radius: 5px;
                                                font-weight: 600;
                                                width: 100%;">
                                            CHECKOUT
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- cart widget -->
                    </div>

                    <div class="col-md-8">
                        <div class="search-container">
                            <div class="input-group">
                                <input type="text" id="dishSearch" class="form-control" placeholder="Tìm kiếm món ăn..." 
                                    style="border-radius: 20px; padding: 12px 20px; border: 1px solid #ddd;">
                                <div class="input-group-append"></div>
                            </div>
                            <div id="searchResults" class="search-results"></div>
                        </div>
                        <?php if (!empty($_SESSION['group'])): ?>
    <a href="group_order/my_group.php" class="btn btn-outline-primary" style="border-radius: 20px; font-weight:600; margin-bottom: 18px; display:inline-flex;align-items:center;gap:8px;">
        <i class="fa fa-users"></i> Nhóm: <?php echo htmlspecialchars($_SESSION['group']['name']); ?> (của <?php echo htmlspecialchars($_SESSION['group']['owner']); ?>)
        <img src="https://cdn-icons-png.flaticon.com/128/3135/3135715.png" style="width:22px;height:22px;margin-left:4px;">
    </a>
<?php else: ?>
    <button class="group-order-btn" data-toggle="modal" data-target="#groupOrderModal" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: white; border: none; padding: 8px 16px; border-radius: 20px; font-weight: 600; margin-bottom: 18px; display: inline-flex; align-items: center;">
        <i class="fa fa-users" style="margin-right: 8px;"></i> Đặt theo nhóm
    </button>
<?php endif; ?>

    <!-- menu widget -->
<div class="menu-widget" id="menu-widget-2" style="font-family: 'Segoe UI', sans-serif;">
    <div class="widget-heading" style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
        <h3 class="widget-title text-dark" style="font-weight: 600; display: flex; justify-content: space-between; align-items: center;">
            MENU
            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#popular2" aria-expanded="true" style="padding: 0; color: #333;">
                <i class="fa fa-angle-down"></i>
            </button>
        </h3>
    </div>

    <div class="collapse in" id="popular2">
        <?php  
        $res_id = filter_input(INPUT_GET, 'res_id', FILTER_VALIDATE_INT);
        
        if ($res_id) {
            $stmt = $db->prepare("SELECT * FROM dishes WHERE rs_id = ?");
            $stmt->bind_param("i", $res_id);
            $stmt->execute();
            $products = $stmt->get_result();
            if ($products->num_rows > 0) {
                while ($product = $products->fetch_assoc()) {
                    $dish_id = htmlspecialchars($product['d_id']);
                    $dish_title = htmlspecialchars($product['title']);
                    $dish_slogan = htmlspecialchars($product['slogan']);
                    $dish_des = htmlspecialchars($product['description']);
                    $dish_price = number_format($product['price'], 2);
                    $dish_img = htmlspecialchars($product['img']);
                    $sold_quantity = isset($product['sold']) ? $product['sold'] : rand(5, 50);
        ?>
        <div class="food-item" id="dish-<?= $dish_id ?>" style="padding: 15px 0; border-bottom: 1px solid #f5f5f5;">
            <form method="post" action="dishes.php?res_id=<?= $res_id ?>&action=add&id=<?= $dish_id ?>">
                <form class="add-to-cart-form" method="post" action="dishes.php?res_id=<?= $res_id ?>&action=add&id=<?= $dish_id ?>">
                <div style="display: flex; gap: 15px;">
                    <div style="width: 80px; height: 80px; flex-shrink: 0;">
                        <?php
                        $img_path = "admin/Res_img/dishes/" . $dish_img;
                        if (file_exists($img_path)) {
                        ?>
                        <img src="<?= $img_path ?>" alt="<?= $dish_title ?>" 
                             style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                        <?php
                        } else {
                            echo "<span style='color:red;font-size:12px;'>Ảnh không tồn tại: $img_path</span>";
                        }
                        ?>
                    </div>
                    
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <div style="margin-bottom: 5px;">
                            <h5 style="margin: 0; font-weight: 600; color: #333;"><?= $dish_title ?></h5>
                            <?php if(!empty($product['is_signature'])): ?>
                                <span style="font-size: 12px; color: #ff6b6b; font-weight: 500;">Signature</span>
                            <?php endif; ?>
                        </div>
                        <p style="margin: 0; color: #666; font-size: 13px; flex: 1;"><?= $dish_des ?></p>
                        <p style="margin: 5px 0 0; color: #888; font-size: 12px;">
                            <?= $sold_quantity ?>+ sold
                        </p>
                    </div>
                    
                    <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; justify-content: space-between;">
                        <span style="font-weight: 600; color: #333;"><?= number_format($product['price'], 0) ?> VNĐ</span>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="number" name="quantity" min="1" value="1" 
                                   style="width: 50px; padding: 5px; border: 1px solid #ddd; border-radius: 4px; text-align: center;">
                            <button type="submit" style="background: #ff6b6b; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;" type="button" class="add-to-cart-btn">
                                +
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
                }
            } else {
                echo '<div class="alert alert-info" style="margin: 15px 0; padding: 10px; 
                     background: #f8f9fa; border: 1px solid #eee; border-radius: 4px;">
                     Không có món ăn nào! Vui lòng quay lại sau</div>';
            }
            $stmt->close();
        } else {
            echo '<div class="alert alert-danger" style="margin: 15px 0; padding: 10px; 
                 background: #fff3f3; border: 1px solid #ffdddd; border-radius: 4px;">
                 Cửa hàng này không tồn tại.</div>';
        }
        ?>
    </div>
</div>
    <!-- menu widget -->
            
                <!-- Similar Restaurants -->
                <div class="similar-restaurants" style="margin-bottom: 32px;">
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 18px;">
                        <button id="tabSimilar" class="btn btn-outline-primary active" style="border-radius: 20px 20px 20px 20px; font-weight: 600;">
                            <span>Cửa hàng tương tự</span> <i class="fa fa-angle-down" id="iconSimilar"></i>
                        </button>
                        <label for="similarLimit" style="margin: 0 0 0 18px; font-weight: 500; color: #444;">Hiển thị:</label>
                        <select id="similarLimit" style="width: 70px; border-radius: 4px; border: 1px solid #ddd; padding: 2px 8px;">
                            <option value="3">3</option>
                            <option value="4" selected>4</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div id="similarBox" style="position: relative;">
                        <button id="prevSimilar" class="btn btn-light" style="position: absolute; left: -30px; top: 40%; z-index: 2; border-radius: 50%; width: 36px; height: 36px; box-shadow: 0 2px 8px #eee;">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <div id="similarRestaurants" style="display: flex; gap: 18px; overflow-x: hidden; scroll-behavior: smooth; padding: 8px 0 8px 0;">
                        </div>
                        <button id="nextSimilar" class="btn btn-light" style="position: absolute; right: -30px; top: 40%; z-index: 2; border-radius: 50%; width: 36px; height: 36px; box-shadow: 0 2px 8px #eee;">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <!-- Similar Restaurants End -->
                       
                    </div>
                    
                </div>
     
            </div>
        

        <!-- Modal đặt theo nhóm Star -->
    <div class="modal fade" id="groupOrderModal" tabindex="-1" role="dialog" aria-labelledby="groupOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupOrderModalLabel">Đặt món theo nhóm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="groupName">Tên nhóm</label>
                        <input type="text" class="form-control" id="groupName" placeholder="Ví dụ: Nhóm bạn thân">
                    </div>
                    <div class="form-group">
                        <label for="groupCode">Mã nhóm (để chia sẻ)</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="groupCode" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="copyGroupCode()">
                                    <i class="fa fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Thành viên</label>
                        <div id="groupMembers">
                            <div class="member-item" style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="flex: 1;">Bạn (chủ nhóm)</span>
                                <span class="badge badge-primary">Chủ nhóm</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="createGroupOrder()">Tạo nhóm</button>
                </div>
            </div>
        </div>
    </div>
        <!-- Modal đặt theo nhóm End -->

        <!-- Topcontrol Navigation Start -->
<div class="topcontrol-nav" id="topcontrolNav">
  <div class="topcontrol-group" tabindex="0">
    <button class="topcontrol-main-btn"><i class="fa fa-bars"></i></button>
    <div class="topcontrol-popup">
      <button class="topcontrol-btn" title="Lên đầu trang" onclick="window.scrollTo({top:0,behavior:'smooth'})"><i class="fa fa-arrow-up"></i></button>
      <button class="topcontrol-btn" title="Đăng ký tài xế" onclick="window.location.href='driver_register.php'"><i class="fa fa-motorcycle"></i></button>
      <button class="topcontrol-btn" title="Startup cửa hàng" onclick="window.location.href='startup.php'"><i class="fa fa-rocket"></i></button>
      <button class="topcontrol-btn" title="Trang admin" onclick="window.location.href='admin/index.php'"><i class="fa fa-user-secret"></i></button>
      <button class="topcontrol-btn" title="Cửa hàng yêu thích" onclick="handleWorthyNav()"><i class="fa fa-heart"></i></button>
    </div>
  </div>
  <style>
    .topcontrol-nav {
      position: fixed;
      top: 38%;
      left: 18px;
      z-index: 9999;
      display: flex;
      align-items: center;
      background: transparent;
      box-shadow: none;
      padding: 0;
    }
    .topcontrol-group {
      position: relative;
      outline: none;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .topcontrol-main-btn {
      background: #fff;
      border: none;
      outline: none;
      border-radius: 50%;
      width: 54px;
      height: 54px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: #ff9800;
      box-shadow: 0 2px 16px rgba(0,0,0,0.13);
      cursor: pointer;
      transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.18s;
    }
    .topcontrol-main-btn:hover {
      background: #ffe0b2;
      color: #00b14f;
      box-shadow: 0 4px 18px rgba(0,177,79,0.13);
      transform: scale(1.08) translateY(-2px);
    }
    .topcontrol-popup {
      position: absolute;
      left: 70px;
      top: 50%;
      transform: translateY(-50%) scale(0.95);
      background: rgba(255,255,255,0.98);
      border-radius: 18px;
      box-shadow: 0 4px 32px rgba(0,0,0,0.13);
      padding: 18px 16px;
      display: flex;
      flex-direction: column;
      gap: 16px;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.18s, transform 0.18s;
      min-width: 60px;
    }
    .topcontrol-group:hover .topcontrol-popup,
    .topcontrol-group:focus-within .topcontrol-popup {
      opacity: 1;
      pointer-events: auto;
      transform: translateY(-50%) scale(1);
    }
    .topcontrol-btn {
      background: #fff;
      border: none;
      outline: none;
      border-radius: 50%;
      width: 44px;
      height: 44px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.45rem;
      color: #ff9800;
      box-shadow: 0 1px 6px rgba(0,0,0,0.07);
      margin: 0;
      cursor: pointer;
      transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.18s;
    }
    .topcontrol-btn:hover {
      background: #ffe0b2;
      color: #00b14f;
      box-shadow: 0 4px 18px rgba(0,177,79,0.13);
      transform: scale(1.12) translateY(-2px);
    }
    .topcontrol-btn:active {
      transform: scale(0.97);
    }
    .topcontrol-btn[title]:hover:after {
      content: attr(title);
      position: absolute;
      left: 120%;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(34,34,34,0.97);
      color: #fff;
      padding: 7px 16px;
      border-radius: 8px;
      font-size: 1.02rem;
      font-weight: 500;
      white-space: nowrap;
      box-shadow: 0 2px 12px rgba(0,0,0,0.13);
      opacity: 1;
      pointer-events: none;
      transition: opacity 0.18s, left 0.18s;
      z-index: 10000;
    }
    .topcontrol-btn[title]:hover:before {
      content: '';
      position: absolute;
      left: 114%;
      top: 50%;
      transform: translateY(-50%);
      border-width: 7px;
      border-style: solid;
      border-color: transparent rgba(34,34,34,0.97) transparent transparent;
      z-index: 10001;
    }
    @media (max-width: 900px) {
      .topcontrol-nav {left: 4px;}
      .topcontrol-main-btn {width: 44px;height: 44px;font-size:1.3rem;}
      .topcontrol-btn {width: 36px;height: 36px;font-size:1.08rem;}
      .topcontrol-popup {left: 54px; padding: 10px 8px;}
      .topcontrol-btn[title]:hover:after {font-size:0.95rem;}
    }
    @media (max-width: 600px) {
      .topcontrol-nav {top: unset; bottom: 18px; left: 8px;}
      .topcontrol-group {flex-direction: row;}
      .topcontrol-main-btn {width: 38px;height: 38px;font-size:1rem;}
      .topcontrol-popup {left: 0; top: unset; bottom: 54px; transform: translateY(0) scale(0.95); flex-direction: row; gap: 10px;}
      .topcontrol-group:hover .topcontrol-popup,
      .topcontrol-group:focus-within .topcontrol-popup {transform: translateY(0) scale(1);}
      .topcontrol-btn {width: 32px;height: 32px;font-size:1rem;}
      .topcontrol-btn[title]:hover:after {left: 50%; top: -38px; transform: translateX(-50%);}
      .topcontrol-btn[title]:hover:before {left: 50%; top: -10px; transform: translateX(-50%) rotate(90deg); border-width: 7px; border-color: transparent transparent rgba(34,34,34,0.97) transparent;}
    }
  </style>
  <script>
    function handleWorthyNav() {
      <?php if(empty($_SESSION['user_id'])): ?>
        window.location.href = 'login.php';
      <?php else: ?>
        window.location.href = 'worthy.php';
      <?php endif; ?>
    }
  </script>
</div>

<!-- Topcontrol Navigation End -->




        <footer class="footer">
<!-- Chatbot Widget Start -->
  <div id="chatbot-widget">
    <div id="chatbot-header">
      <span style="display:flex;align-items:center;gap:8px;"><img src="images/img/iconss.png" alt="Bot" style="width:32px;height:32px;border-radius:50%;margin-right:6px;box-shadow:0 1px 4px rgba(0,0,0,0.10);border:2px solid #fff;background:#fff;"> Chat hỗ trợ</span>
      <span style="display:flex;align-items:center;gap:0;">
        <span id="chatbot-minimize" title="Thu nhỏ" style="cursor:pointer;font-size:1.3rem;padding:0 8px;">&#8211;</span>
        <span id="chatbot-close" title="Đóng" style="cursor:pointer;font-size:1.3rem;padding:0 8px;">&times;</span>
      </span>
    </div>
    <div id="chatbot-messages"></div>
    <div id="chatbot-input-area">
      <input type="text" id="chatbot-input" placeholder="Nhập câu hỏi..." autocomplete="off"/>
      <button id="chatbot-send"><i class="fa fa-paper-plane"></i></button>
    </div>
  </div>
  <button id="chatbot-toggle"><i class="fa fa-comments"></i></button>
  <style>
    #chatbot-widget {
      position: fixed; bottom: 36px; right: 36px; width: 400px; background: #fff; border-radius: 20px;
      box-shadow: 0 6px 40px rgba(0,0,0,0.16); display: none; flex-direction: column; z-index: 99999;
      min-height: 480px; max-height: 80vh; overflow: hidden;
    }
    .chatbot-suggestions-inline {
      display: inline-flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-left: 12px;
      vertical-align: middle;
    }
    .chatbot-suggestion {
      background: #fff3e0;
      color: #ff9800;
      padding: 8px 14px;
      border-radius: 16px;
      font-size: 0.98rem;
      cursor: pointer;
      font-weight: 500;
      border: 1.5px solid #ff9800;
      transition: background 0.2s, color 0.2s;
      display: inline-block;
      margin-bottom: 0;
    }
    .chatbot-suggestion:hover {
      background: #ff9800;
      color: #fff;
    }
    #chatbot-header { background: #ff9800; color: #fff; padding: 16px 20px; border-radius: 20px 20px 0 0; font-weight: bold; display: flex; justify-content: space-between; align-items: center; font-size: 1.18rem;}
    #chatbot-messages { padding: 18px; height: 340px; overflow-y: auto; background: #f9f9f9; display: flex; flex-direction: column; gap: 10px; }
    #chatbot-input-area { display: flex; border-top: 1px solid #eee; background: #fff; }
    #chatbot-input { flex: 1; border: none; padding: 14px; border-radius: 0 0 0 20px; outline: none; font-size: 1.08rem; }
    #chatbot-send { background: #ff9800; color: #fff; border: none; padding: 0 24px; border-radius: 0 0 20px 0; cursor: pointer; font-size: 1.25rem; }
    #chatbot-toggle { position: fixed; bottom: 36px; right: 36px; background: #ff9800; color: #fff; border: none; border-radius: 50%; width: 64px; height: 64px; font-size: 2.2rem; box-shadow: 0 2px 16px rgba(0,0,0,0.13); cursor: pointer; z-index: 99999; }
    .chatbot-msg-row { display: flex; align-items: flex-end; gap: 10px; }
    .chatbot-msg-row.user { justify-content: flex-end; }
    .chatbot-avatar { width: 38px; height: 38px; border-radius: 50%; background: #ff9800; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.3rem; font-weight: bold; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
    .chatbot-avatar.bot {
      background: #fff;
      color: #ff9800;
      border: 2.5px solid #ff9800;
      background-image: url('images/img/iconss.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      box-shadow: 0 2px 8px rgba(255,152,0,0.10);
    }
    .chatbot-bubble { max-width: 75%; padding: 12px 18px; border-radius: 16px; font-size: 1.08rem; line-height: 1.5; box-shadow: 0 1px 6px rgba(0,0,0,0.04); word-break: break-word; }
    .chatbot-msg-row.user .chatbot-bubble { background: #ff9800; color: #fff; border-bottom-right-radius: 6px; }
    .chatbot-msg-row.bot .chatbot-bubble { background: #eee; color: #222; border-bottom-left-radius: 6px; }
    .chatbot-loading { display: inline-block; width: 32px; height: 18px; }
    .chatbot-loading span { display: inline-block; width: 8px; height: 8px; margin: 0 2px; background: #ff9800; border-radius: 50%; animation: chatbot-bounce 1.1s infinite alternate; }
    .chatbot-loading span:nth-child(2) { animation-delay: 0.2s; }
    .chatbot-loading span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes chatbot-bounce { 0% { transform: translateY(0); } 100% { transform: translateY(-8px); } }
    @media (max-width: 600px) {
      #chatbot-widget { right: 2vw; left: 2vw; width: 96vw; min-width: unset; min-height: 320px; }
      #chatbot-toggle { right: 2vw; bottom: 2vw; width: 54px; height: 54px; font-size: 1.5rem; }
      #chatbot-header { font-size: 1rem; padding: 12px 10px; }
      #chatbot-messages { padding: 10px; height: 180px; }
      #chatbot-input { padding: 10px; font-size: 1rem; }
      #chatbot-send { padding: 0 12px; font-size: 1rem; }
    }
  </style>
  <script>
    const chatbotWidget = document.getElementById('chatbot-widget');
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotMinimize = document.getElementById('chatbot-minimize');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotMessages = document.getElementById('chatbot-messages');

    chatbotToggle.onclick = function() {
      chatbotWidget.style.display = 'flex';
      chatbotToggle.style.display = 'none';
      setTimeout(() => chatbotInput.focus(), 200);
    };
    chatbotClose.onclick = function() {
      chatbotWidget.style.display = 'none';
      chatbotToggle.style.display = 'block';
    };
    chatbotMinimize.onclick = function() {
      chatbotWidget.style.display = 'none';
      chatbotToggle.style.display = 'block';
    };
    chatbotSend.onclick = sendMessage;
    chatbotInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') sendMessage();
    });

    // Đề xuất câu hỏi gợi ý
    const suggestedQuestions = [
      "Làm thế nào để đăng ký tài khoản?",
      "Giờ làm việc của bạn là khi nào?",
      "Tôi cần hỗ trợ thanh toán",
      "Cách liên hệ với bộ phận CSKH?"
    ];

    function sendMessage() {
      var msg = chatbotInput.value.trim();
      if (!msg) return;
      appendMessage('user', msg);
      chatbotInput.value = '';
      chatbotInput.focus();
      removeSuggestions();
      appendLoading();
      fetch('support/chatbot.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'message=' + encodeURIComponent(msg)
      })
      .then(res => res.text())
      .then(reply => {
        removeLoading();
        appendMessage('bot', reply);
      });
    }

    // Hiển thị gợi ý khi mở chat lần đầu
    chatbotToggle.onclick = function() {
      chatbotWidget.style.display = 'flex';
      chatbotToggle.style.display = 'none';
      setTimeout(() => chatbotInput.focus(), 200);
      if(chatbotMessages.children.length === 0) {
        setTimeout(() => {
          appendMessage('bot', 'Xin chào! Bạn cần hỗ trợ gì ạ?');
          showSuggestions();
        }, 400);
      }
    };

    function showSuggestions() {
      removeSuggestions();
      // Tìm bubble của tin nhắn bot đầu tiên
      const lastBotMsg = Array.from(chatbotMessages.children).find(row => row.classList.contains('bot'));
      if (lastBotMsg) {
        const bubble = lastBotMsg.querySelector('.chatbot-bubble');
        if (bubble) {
          const suggestions = document.createElement('span');
          suggestions.className = 'chatbot-suggestions-inline';
          suggestedQuestions.forEach(q => {
            const btn = document.createElement('span');
            btn.className = 'chatbot-suggestion';
            btn.textContent = q;
            btn.onclick = function() {
              chatbotInput.value = q;
              chatbotInput.focus();
              sendMessage();
            };
            suggestions.appendChild(btn);
          });
          bubble.appendChild(suggestions);
        }
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
      }
    }
    function removeSuggestions() {
      const exist = chatbotMessages.querySelectorAll('.chatbot-suggestions');
      exist.forEach(e => e.remove());
    }
    function appendMessage(sender, text) {
      var row = document.createElement('div');
      row.className = 'chatbot-msg-row ' + (sender === 'user' ? 'user' : 'bot');
      var avatar = document.createElement('div');
      avatar.className = 'chatbot-avatar ' + (sender === 'user' ? '' : 'bot');
      avatar.innerHTML = sender === 'user' ? '<i class="fa fa-user"></i>' : '<i class="fa fa-robot"></i>';
      var bubble = document.createElement('div');
      bubble.className = 'chatbot-bubble';
      if(sender === 'bot') {
        bubble.innerHTML = text;
      } else {
        bubble.textContent = text;
      }
      if(sender === 'user') {
        row.appendChild(bubble);
        row.appendChild(avatar);
      } else {
        row.appendChild(avatar);
        row.appendChild(bubble);
      }
      chatbotMessages.appendChild(row);
      chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function appendLoading() {
      removeLoading();
      var row = document.createElement('div');
      row.className = 'chatbot-msg-row bot chatbot-loading-row';
      var avatar = document.createElement('div');
      avatar.className = 'chatbot-avatar bot';
      avatar.innerHTML = '<i class="fa fa-robot"></i>';
      var loading = document.createElement('div');
      loading.className = 'chatbot-bubble chatbot-loading';
      loading.innerHTML = '<span></span><span></span><span></span>';
      row.appendChild(avatar);
      row.appendChild(loading);
      chatbotMessages.appendChild(row);
      chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }
    function removeLoading() {
      var loading = chatbotMessages.querySelector('.chatbot-loading-row');
      if(loading) chatbotMessages.removeChild(loading);
    }
  </script>
<!-- Chatbot Widget End -->
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
      
        </div>
  
    </div>


   
 
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Load cửa hàng tương tự
        loadSimilarRestaurants();
        
        // Tìm kiếm món ăn
        $('#dishSearch').on('input', function() {
            const searchTerm = $(this).val().trim();
            if (searchTerm.length > 0) {
                $.ajax({
                    url: 'support/search_dishes.php',
                    type: 'GET',
                    data: {
                        term: searchTerm,
                        res_id: <?php echo $_GET['res_id']; ?>
                    },
                    success: function(response) {
                        let results = response;
                        if (typeof results === 'string') results = JSON.parse(results);
                        const $resultsContainer = $('#searchResults');
                        $resultsContainer.empty();
                        if (results.length > 0) {
                            results.forEach(dish => {
                                $resultsContainer.append(`
                                    <div class="search-result-item" onclick="selectDish('${dish.id}')">
                                        <div style="font-weight: 600;">${dish.title}</div>
                                        <div style="font-size: 12px; color: #666;">${dish.slogan || ''}</div>
                                        <div style="color: #6a11cb; font-weight: 600;">${dish.price}</div>
                                    </div>
                                `);
                            });
                            $resultsContainer.show();
                        } else {
                            $resultsContainer.append('<div class="search-result-item">Không tìm thấy món phù hợp</div>');
                            $resultsContainer.show();
                        }
                    }
                });
            } else {
                $('#searchResults').hide();
            }
        });
        
        // Ẩn kết quả tìm kiếm khi click ra ngoài
        $(document).click(function(e) {
            if (!$(e.target).closest('.search-container').length) {
                $('#searchResults').hide();
            }
        });
    });
    
    let similarData = [];
    let similarIndex = 0;
    let VISIBLE_CARDS = 4; // Số lượng thẻ hiển thị cùng lúc

    function renderSimilarRestaurants() {
        const $container = $('#similarRestaurants');
        $container.empty();
        if (similarData.length === 0) {
            $container.append('<p style="padding: 20px;">Không có cửa hàng tương tự</p>');
            return;
        }
        for (let i = similarIndex; i < Math.min(similarIndex + VISIBLE_CARDS, similarData.length); i++) {
            const r = similarData[i];
            $container.append(`
                <a href="dishes.php?res_id=${r.rs_id}" class="similar-restaurant-card">
                    <img src="Mng_shop/admin/Res_img/${r.image}" alt="${r.title}" class="similar-restaurant-img">
                    <div class="similar-restaurant-info">
                        <div>
                            <div class="similar-restaurant-title">${r.title}</div>
                            <div class="similar-restaurant-address">${r.address || ''}</div>
                        </div>
                        <div>
                            <span class="similar-restaurant-rating">
                                <i class="fa fa-star"></i> ${r.rating ? Number(r.rating).toFixed(1) : '0.0'}
                            </span>
                            <span class="similar-restaurant-reviews">(${r.reviews || 0} đánh giá)</span>
                        </div>
                    </div>
                </a>
            `);
        }
        $('#prevSimilar').prop('disabled', similarIndex === 0);
        $('#nextSimilar').prop('disabled', similarIndex + VISIBLE_CARDS >= similarData.length);
    }

    function loadSimilarRestaurants() {
        $.ajax({
            url: 'support/get_similar_restaurants.php',
            type: 'GET',
            data: {
                current_res_id: <?php echo $_GET['res_id']; ?>,
                limit: 20
            },
            success: function(response) {
                similarData = typeof response === "string" ? JSON.parse(response) : response;
                similarIndex = 0;
                renderSimilarRestaurants();
            }
        });
    }

    $(document).ready(function() {
        loadSimilarRestaurants();
        $('#prevSimilar').on('click', function() {
            if (similarIndex > 0) {
                similarIndex--;
                renderSimilarRestaurants();
            }
        });
        $('#nextSimilar').on('click', function() {
            if (similarIndex + VISIBLE_CARDS < similarData.length) {
                similarIndex++;
                renderSimilarRestaurants();
            }
        });
        $('#similarLimit').on('change', function() {
            VISIBLE_CARDS = parseInt($(this).val());
            similarIndex = 0;
            renderSimilarRestaurants();
        });
        // Ẩn/hiện box cửa hàng tương tự
        $('#tabSimilar').on('click', function() {
            $('#similarBox').slideToggle(220, function() {
                // Đổi icon
                const icon = $('#iconSimilar');
                if ($('#similarBox').is(':visible')) {
                    icon.removeClass('fa-angle-up').addClass('fa-angle-down');
                } else {
                    icon.removeClass('fa-angle-down').addClass('fa-angle-up');
                }
            });
        });
    });
    
    
    

    function selectDish(dishId) {
        $('html, body').animate({
            scrollTop: $(`#dish-${dishId}`).offset().top - 100
        }, 500);
        $(`#dish-${dishId}`).css('background', '#fff8e1').animate({
            backgroundColor: '#ffffff'
        }, 1500);
        
        $('#searchResults').hide();
    }
    
    // Tạo đơn hàng nhóm
    function createGroupOrder() {
        const groupName = $('#groupName').val().trim();
        const resId = <?php echo isset($_GET['res_id']) ? intval($_GET['res_id']) : 0; ?>;
        if (!groupName) {
            Swal.fire('Lỗi', 'Vui lòng nhập tên nhóm', 'error');
            return;
        }
        if (!resId) {
            Swal.fire('Lỗi', 'Không xác định được nhà hàng!', 'error');
            return;
        }
        // Gọi AJAX tạo nhóm và lưu session
        $.ajax({
            url: 'group_order/create_group.php',
            type: 'POST',
            data: { group_name: groupName, res_id: resId },
            success: function(res) {
                window.location.href = 'group_order/my_group.php';
            }
        });
    }

    function copyGroupCode() {
        const groupCode = $('#groupCode').val();
        if (groupCode) {
            navigator.clipboard.writeText(groupCode).then(() => {
                Swal.fire('Đã copy!', 'Mã nhóm đã được sao chép', 'success');
            });
        }
    }
</script>

<script>    
    $(document).ready(function() {
        $(document).on('click', '.add-to-cart-btn', function(e) {
            e.preventDefault();
            var $form = $(this).closest('form');
            var formData = $form.serialize();
            var actionUrl = $form.attr('action');
            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData + '&ajax=1',
                success: function(response) {
                    $(".widget-cart").load(window.location.href + " .widget-cart > *", function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã thêm vào giỏ!',
                            showConfirmButton: false,
                            timer: 900
                        });
                    });
                },
                error: function() {
                    Swal.fire('Lỗi', 'Không thể thêm vào giỏ hàng', 'error');
                }
            });
        });
    });
</script>
<?php if (!empty($_SESSION['group'])): ?>
    <a href="group_order/my_group.php" class="btn btn-outline-primary" style="border-radius: 20px; font-weight:600; margin-bottom: 18px; display:inline-flex;align-items:center;gap:8px;">
        <i class="fa fa-users"></i> Nhóm: <?php echo htmlspecialchars($_SESSION['group']['name']); ?> (của <?php echo htmlspecialchars($_SESSION['group']['owner']); ?>)
        <img src="https://cdn-icons-png.flaticon.com/128/3135/3135715.png" style="width:22px;height:22px;margin-left:4px;">
    </a>
<?php endif; ?>
</body>
</html>
