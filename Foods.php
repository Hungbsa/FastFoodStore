<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images\img\iconss.png">
    <title>Cửa hàng - FastFood</title>
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


        <div class="page-wrapper">
            
            
            <div class="result-show">
                <div class="container">
                    <div class="row">     
                    </div>
                </div>
            </div>
            <section class="restaurants-page" style="background: #f4f4f4; min-height: 100vh;">
    <div class="container">
        <h2 style="font-weight: 700; font-size: 2.1rem; margin: 32px 0 18px 0; text-align: center; color: #222;">Danh sách cửa hàng</h2>
        <div class="row" style="margin-bottom: 18px; align-items: center;">
          <div class="col-md-3 col-6 mb-2">
            <label style="font-weight: 600; color: #888;">KHU VỰC</label>
            <form method="get" id="filterForm">
              <select class="form-control" name="c_id" style="margin-top: 4px;" onchange="document.getElementById('filterForm').submit();">
                <option value="">Tất cả</option>
                <?php 
                $catList = mysqli_query($db, "SELECT c_id, c_name FROM res_category");
                while($cat = mysqli_fetch_assoc($catList)) {
                  $selected = (isset($_GET['c_id']) && $_GET['c_id'] == $cat['c_id']) ? 'selected' : '';
                  echo '<option value="'.$cat['c_id'].'" '.$selected.'>'.$cat['c_name'].'</option>';
                }
                ?>
              </select>
            </form>
          </div>
          <div class="col-md-5 col-12 mb-2">
            <div style="position:relative;">
              <input type="text" id="shopSearchInput" class="form-control" placeholder="Tìm cửa hàng..." style="padding-right:38px;" oninput="filterShops()" onfocus="showShopSuggestions()" autocomplete="off">
              <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#ff9800;font-size:1.3rem;cursor:pointer;" onclick="filterShops()"><i class="fa fa-search"></i></span>
              <div id="shopSuggestions" style="position:absolute;top:110%;left:0;width:100%;background:#fff;border-radius:8px;box-shadow:0 2px 12px #0002;z-index:99;display:none;"></div>
            </div>
          </div>
        </div>
    <div class="row" style="gap: 0 18px;" id="shopsList">
      <?php 
      $query = "SELECT r.*, c.c_name FROM restaurant r LEFT JOIN res_category c ON r.c_id = c.c_id";
      if(isset($_GET['c_id']) && $_GET['c_id'] != "") {
        $c_id = intval($_GET['c_id']);
        $query .= " WHERE r.c_id = $c_id";
      }
      // Lấy danh sách id nhà hàng đã yêu thích của user
      $user_fav = array();
      if (!empty($_SESSION['user_id'])) {
        $uid = intval($_SESSION['user_id']);
        $favq = mysqli_query($db, "SELECT res_id FROM worthy WHERE user_id = $uid");
        while($fav = mysqli_fetch_assoc($favq)) {
          $user_fav[] = $fav['res_id'];
        }
      }
      $shops_data = [];
      $ress= mysqli_query($db, $query);
      while($rows=mysqli_fetch_array($ress))
      {
        $is_fav = (!empty($_SESSION['user_id']) && in_array($rows['rs_id'], $user_fav));
        $shops_data[] = [
          'rs_id' => $rows['rs_id'],
          'title' => $rows['title'],
          'address' => $rows['address'],
          'image' => $rows['image'],
          'is_fav' => $is_fav
        ];
        echo '<div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4 shop-item" data-title="'.strtolower($rows['title']).'" data-address="'.strtolower($rows['address']).'">
          <div class="promo-card">
            <div class="promo-img-wrap">
              <img src="Mng_shop/admin/Res_img/'.$rows['image'].'" alt="'.$rows['title'].'" class="promo-img">
              <span class="promo-status-dot"></span>';
        // Icon trái tim thêm vào danh sách yêu thích
        if ($is_fav) {
          echo '<a href="worthy.php?res_id='.$rows['rs_id'].'" class="promo-fav-btn" title="Đã yêu thích" style="position:absolute;top:10px;right:10px;background:rgba(255,255,255,0.95);border:none;border-radius:50%;width:38px;height:38px;display:flex;align-items:center;justify-content:center;box-shadow:0 1px 6px rgba(0,0,0,0.07);font-size:1.35rem;color:#e74c3c;cursor:pointer;transition:color 0.18s;">'
          .'<i class="fa fa-heart"></i>'
          .'</a>';
        } else {
          echo '<a href="worthy.php?res_id='.$rows['rs_id'].'" class="promo-fav-btn" title="Thêm vào yêu thích" style="position:absolute;top:10px;right:10px;background:rgba(255,255,255,0.95);border:none;border-radius:50%;width:38px;height:38px;display:flex;align-items:center;justify-content:center;box-shadow:0 1px 6px rgba(0,0,0,0.07);font-size:1.35rem;color:#bbb;cursor:pointer;transition:color 0.18s;">'
          .'<i class="fa fa-heart-o"></i>'
          .'</a>';
        }
        echo '</div>
            <div class="promo-card-body">
              <div class="promo-title-row">
                <span class="promo-shield"><i class="fa fa-shield" style="color:#ffc107;"></i></span>
                <a href="dishes.php?res_id='.$rows['rs_id'].'" class="promo-title-link"><span class="promo-title">'.$rows['title'].'</span></a>
              </div>
              <div class="promo-address">'.$rows['address'].'</div>
              <div class="promo-tags">
                <span class="promo-discount"><i class="fa fa-tag"></i> Giảm món</span>
              </div>
            </div>
          </div>
        </div>';
      }
      ?>
    </div>
    <script>
    // Dữ liệu cửa hàng cho JS
    var shopsData = <?php echo json_encode($shops_data); ?>;
    function filterShops() {
      var input = document.getElementById('shopSearchInput').value.toLowerCase();
      var items = document.querySelectorAll('.shop-item');
      var found = 0;
      items.forEach(function(item) {
        var title = item.getAttribute('data-title');
        var address = item.getAttribute('data-address');
        if (!input || title.includes(input) || address.includes(input)) {
          item.style.display = '';
          found++;
        } else {
          item.style.display = 'none';
        }
      });
      // Ẩn gợi ý khi đã nhập
      document.getElementById('shopSuggestions').style.display = 'none';
    }
    function showShopSuggestions() {
      var input = document.getElementById('shopSearchInput').value.toLowerCase();
      var suggestions = shopsData.filter(function(shop) {
        return !input || shop.title.toLowerCase().includes(input);
      }).slice(0,3);
      var html = '';
      suggestions.forEach(function(shop) {
        html += `<div style='padding:8px 14px;cursor:pointer;display:flex;align-items:center;gap:10px;' onclick='selectShopSuggestion("${shop.title}")'>
          <img src='Mng_shop/admin/Res_img/${shop.image}' style='width:32px;height:32px;border-radius:8px;object-fit:cover;'>
          <span style='font-weight:600;color:#222;'>${shop.title}</span>
          <span style='color:#888;font-size:0.98rem;'>${shop.address}</span>
        </div>`;
      });
      var box = document.getElementById('shopSuggestions');
      box.innerHTML = html;
      box.style.display = suggestions.length ? 'block' : 'none';
    }
    function selectShopSuggestion(title) {
      document.getElementById('shopSearchInput').value = title;
      filterShops();
      document.getElementById('shopSuggestions').style.display = 'none';
    }
    // Ẩn gợi ý khi blur
    document.getElementById('shopSearchInput').addEventListener('blur', function(){
      setTimeout(function(){document.getElementById('shopSuggestions').style.display = 'none';}, 180);
    });
    </script>
    </div>
<style>
        .promo-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: box-shadow 0.18s;
            min-height: 340px;
        }
        .promo-card:hover { box-shadow: 0 6px 24px rgba(255,152,0,0.13); }
        .promo-img-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 4/2.1;
            background: #f7f7f7;
            overflow: hidden;
        }
        .promo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .promo-status-dot {
            position: absolute;
            top: 10px; left: 10px;
            width: 16px; height: 16px;
            background: #19c37d;
            border: 2.5px solid #fff;
            border-radius: 50%;
            z-index: 2;
        }
        .promo-fav-tag {
            position: absolute;
            bottom: 10px; left: 10px;
            background: #e74c3c;
            color: #fff;
            font-size: 0.98rem;
            font-weight: 500;
            border-radius: 6px;
            padding: 3px 12px 3px 8px;
            display: flex;
            align-items: center;
            gap: 5px;
            z-index: 2;
        }
        .promo-card-body {
            padding: 14px 14px 10px 14px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .promo-title-row {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 2px;
        }
        .promo-shield {
            font-size: 1.1rem;
        }
        .promo-title {
            font-size: 1.13rem;
            font-weight: 700;
            color: #222;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 170px;
        }
        .promo-address {
            font-size: 0.98rem;
            color: #666;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .promo-tags {
            margin-top: 4px;
        }
        .promo-discount {
            background: #f4f7ff;
            color: #007bff;
            font-size: 0.98rem;
            border-radius: 5px;
            padding: 2px 10px 2px 7px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        @media (max-width: 991px) {
            .col-lg-3 {width: 50%; flex: 0 0 50%; max-width: 50%;}
        }
        @media (max-width: 767px) {
            .col-md-4, .col-lg-3 {width: 100%; flex: 0 0 100%; max-width: 100%;}
            .promo-title {max-width: 120px;}
        }
</style>
</section>
    
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
        
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
</body>

</html>