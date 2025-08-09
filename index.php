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
    <title>Trang chủ - FastFood</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> 

</head>

<body class="home">


<!-- Popup quảng cáo -->
<div id="adModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.35);z-index:999999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:24px;box-shadow:0 4px 48px #0005;max-width:700px;width:98vw;padding:3.2rem 2.2rem;position:relative;text-align:center;overflow:hidden;display:flex;flex-direction:column;align-items:center;">
    <button id="closeAdModal" style="position:absolute;top:12px;right:16px;background:none;border:none;font-size:1.7rem;color:#888;cursor:pointer;">&times;</button>
    <div id="adSlider" style="position:relative;">
      <div class="ad-slide" style="display:block; border: 5px solid #ff9800; border-radius: 22px; background: linear-gradient(90deg,#fff7e6 0%,#fff 100%); box-shadow:0 4px 24px #ff980033; padding: 10px 0;">
        <img src="images/img/sukem.webp" alt="Banner 1" style="width:100%;max-width:520px;height:260px;object-fit:cover;border-radius:18px;box-shadow:0 4px 24px #0002;margin:0 auto;">
        <h2 style="color:#ff9800;margin:28px 0 12px 0;font-size:2.2rem;font-weight:800;">Khuyến mãi đặc biệt!</h2>
        <p style="font-size:1.25rem;color:#222;margin-bottom:1.8rem;line-height:1.6;">Đặt món ngay hôm nay để nhận ưu đãi lên tới <b>50%</b> cho đơn hàng đầu tiên!</p>
        <a href="voucher_wallet.php" style="display:inline-block;padding:16px 38px;background:#ff9800;color:#fff;border-radius:12px;font-weight:700;text-decoration:none;font-size:1.18rem;box-shadow:0 2px 12px #ff980033;">Nhận mã giảm giá</a>
      </div>
      <div class="ad-slide" style="display:none; border: 5px solid #00b14f; border-radius: 22px; background: linear-gradient(90deg,#e6fff2 0%,#fff 100%); box-shadow:0 4px 24px #00b14f33; padding: 10px 0;">
        <img src="images/img/mix.webp" alt="Banner 2" style="width:100%;max-width:520px;height:260px;object-fit:cover;border-radius:18px;box-shadow:0 4px 24px #0002;margin:0 auto;">
        <h2 style="color:#00b14f;margin:28px 0 12px 0;font-size:2.2rem;font-weight:800;">Miễn phí giao hàng!</h2>
        <p style="font-size:1.25rem;color:#222;margin-bottom:1.8rem;line-height:1.6;">Nhập mã <b>FREESHIP</b> khi thanh toán để được miễn phí vận chuyển cho đơn từ 100.000đ.</p>
        <a href="checkout.php" style="display:inline-block;padding:16px 38px;background:#00b14f;color:#fff;border-radius:12px;font-weight:700;text-decoration:none;font-size:1.18rem;box-shadow:0 2px 12px #00b14f33;">Thanh toán ngay</a>
      </div>
      <div class="ad-slide" style="display:none; border: 5px solid #e74c3c; border-radius: 22px; background: linear-gradient(90deg,#ffe6e6 0%,#fff 100%); box-shadow:0 4px 24px #e74c3c33; padding: 10px 0;">
        <img src="images/img/bokebe.jpg" alt="Banner 3" style="width:100%;max-width:520px;height:260px;object-fit:cover;border-radius:18px;box-shadow:0 4px 24px #0002;margin:0 auto;">
        <h2 style="color:#e74c3c;margin:28px 0 12px 0;font-size:2.2rem;font-weight:800;">Tích điểm nhận quà!</h2>
        <p style="font-size:1.25rem;color:#222;margin-bottom:1.8rem;line-height:1.6;">Mỗi đơn hàng bạn sẽ nhận được điểm thưởng, đổi quà hấp dẫn tại <b>Ví Voucher</b>.</p>
        <a href="voucher_wallet.php" style="display:inline-block;padding:16px 38px;background:#e74c3c;color:#fff;border-radius:12px;font-weight:700;text-decoration:none;font-size:1.18rem;box-shadow:0 2px 12px #e74c3c33;">Xem quà tặng</a>
      </div>
      <div style="display:flex;justify-content:center;align-items:center;gap:10px;margin-top:18px;">
        <button id="adPrev" style="background:#eee;border:none;border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#ff9800;box-shadow:0 1px 6px #0001;cursor:pointer;"><i class="fa fa-chevron-left"></i></button>
        <span id="adDots"></span>
        <button id="adNext" style="background:#eee;border:none;border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#ff9800;box-shadow:0 1px 6px #0001;cursor:pointer;"><i class="fa fa-chevron-right"></i></button>
      </div>
    </div>
  </div>
</div>
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




<div class="title text-xs-center m-b-30">
                    <p class="lead">.</p>
                </div>



<!-- Image Carousel Section Start -->
<section class="image-carousel-section" style="padding: 0; margin: 0; margin-top: 110px; background: #fff;">
  <div class="carousel-container" style="position:relative; max-width: 900px;height:30  0px; margin: 0 auto; border-radius: 22px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); overflow: hidden;">
    <div class="carousel-slide fade">
      <img src="images/img/sale1.jpg" alt="Ảnh 1" style="width:900px;height:800px ; border-radius: 0; ">
    </div>
    <div class="carousel-slide fade">
      <img src="images/img/sale2.jpg" alt="Ảnh 2" style="width:900px;height:800px ; border-radius: 0;">
    </div>
    <div class="carousel-slide fade">
      <img src="images/img/sale3.jpg" alt="Ảnh 3" style="width:900px;height:800px ; border-radius: 0; ">
    </div>
    <div class="carousel-slide fade">
      <img src="images/img/sale4.jpg" alt="Ảnh 4" style="width:900px;height:800px ; border-radius: 0;">
    </div>
    
  </div>
  <div style="text-align:center; margin-top: 8px;">
    <span class="dot" onclick="currentSlide(1)" style="height:12px;width:12px;margin:0 4px;background:#ff9800;border-radius:50%;display:inline-block;cursor:pointer;"></span>
    <span class="dot" onclick="currentSlide(2)" style="height:12px;width:12px;margin:0 4px;background:#ff9800;border-radius:50%;display:inline-block;cursor:pointer;"></span>
    <span class="dot" onclick="currentSlide(3)" style="height:12px;width:12px;margin:0 4px;background:#ff9800;border-radius:50%;display:inline-block;cursor:pointer;"></span>
    <span class="dot" onclick="currentSlide(4)" style="height:12px;width:12px;margin:0 4px;background:#ff9800;border-radius:50%;display:inline-block;cursor:pointer;"></span>
  </div>
  <style>
    .carousel-slide {display: none; animation: fadeIn 5s;}
    .carousel-slide img {width: 100%; border-radius: 0; max-height: 300px; object-fit: cover;}
    .carousel-container .prev, .carousel-container .next {transition: background 10s;}
    .carousel-container .prev:hover, .carousel-container .next:hover {background: rgba(0,0,0,0.18); border-radius: 50%;}
    @keyframes fadeIn {from {opacity: 0;} to {opacity: 1;}
    @media (max-width: 600px) {
      .carousel-container {max-width: 100vw;}
      .carousel-slide img {max-height: 120px;}
    }
  </style>
  <script>
    var slideIndex = 1;
    showSlides(slideIndex);
    var autoSlide = setInterval(function(){plusSlides(1)}, 3500);
    function plusSlides(n) {
      clearInterval(autoSlide); autoSlide = setInterval(function(){plusSlides(1)}, 3500);
      showSlides(slideIndex += n);
    }
    function currentSlide(n) {
      clearInterval(autoSlide); autoSlide = setInterval(function(){plusSlides(1)}, 3500);
      showSlides(slideIndex = n);
    }
    function showSlides(n) {
      var i;
      var slides = document.getElementsByClassName("carousel-slide");
      var dots = document.getElementsByClassName("dot");
      if (n > slides.length) {slideIndex = 1}
      if (n < 1) {slideIndex = slides.length}
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
      }
      for (i = 0; i < dots.length; i++) {
        dots[i].style.background = "#ff9800";
        dots[i].style.opacity = 0.5;
      }
      slides[slideIndex-1].style.display = "block";
      dots[slideIndex-1].style.background = "#ff9800";
      dots[slideIndex-1].style.opacity = 1;
    }
  </script>
</section>
<!-- Image Carousel Section End -->





      
    
  
    <!-- Popular Foods Section Start --> 
<section class="popular-promos-section" style="background:#fff; padding: 36px 0 24px 0;">
  <div class="container">
    <div class="text-center mb-4">
      <h2 style="font-weight:700;font-size:2.2rem;">Ưu đãi <span style="color:#00b14f;">Món ăn</span> tại <span style="color:#00b14f;">FastFood</span></h2>
    </div>
    <div class="promo-carousel-wrapper position-relative">
      <button class="promo-arrow promo-arrow-left" onclick="promoPrev()"><i class="fa fa-chevron-left"></i></button>
      <div class="promo-carousel no-scrollbar" id="promoCarousel">
        <?php 
        $query_res= mysqli_query($db,"select * from dishes LIMIT 10"); 
        while($r=mysqli_fetch_array($query_res))
        {
          echo '<a href="dishes.php?res_id='.$r['rs_id'].'" class="promo-card-link" style="text-decoration:none;color:inherit;">';
          echo '<div class="promo-card">
            <div class="promo-img-wrap">
              <img src="admin/Res_img/dishes/'.$r['img'].'" alt="'.$r['title'].'">
              <div class="promo-badge">Promo</div>
            </div>
            <div class="promo-card-body">
              <h5 class="promo-title">'.$r['title'].'</h5>
              <div class="promo-desc">'.$r['slogan'].'</div>
              <div class="promo-meta">
                <span class="promo-rating"><i class="fa fa-star" style="color:#ffc107;"></i> 4.4</span>
                <span class="promo-time"><i class="fa fa-clock-o"></i> 25 phút</span>
                <span class="promo-distance"><i class="fa fa-map-marker"></i> 1.5 km</span>
              </div>
              <div class="promo-deal"><i class="fa fa-tag" style="color:#00b14f;"></i> DEAL ĐÃ Giảm '.rand(10000,60000).'đ</div>
            </div>
          </div>';
          echo '</a>';
        }
        ?>
      </div>
      <button class="promo-arrow promo-arrow-right" onclick="promoNext()"><i class="fa fa-chevron-right"></i></button>
    </div>
    <div class="text-center mt-3">
      <a href="Foods.php" class="btn btn-link fw-bold" style="color:#00b14f;font-size:1.1rem;">See all promotions</a>
    </div>
  </div>
  <style>
    .popular-promos-section {padding-bottom: 0;}
    .promo-carousel-wrapper {position:relative;}
    .promo-carousel {display:flex;gap:22px;overflow-x:hidden;scroll-behavior:smooth;padding:8px 0 8px 0;transition: none;}
    .promo-card-link {display:block;}
    .promo-card {background:#fff;border-radius:14px;box-shadow:0 2px 16px rgba(0,0,0,0.07);min-width:270px;max-width:270px;flex:0 0 270px;display:flex;flex-direction:column;transition:box-shadow 0.2s;}
    .promo-card:hover {box-shadow:0 6px 24px rgba(0,177,79,0.13);}
    .promo-img-wrap {position:relative;height:140px;overflow:hidden;border-top-left-radius:14px;border-top-right-radius:14px;}
    .promo-img-wrap img {width:100%;height:100%;object-fit:cover;display:block;}
    .promo-badge {position:absolute;top:10px;left:10px;background:#00b14f;color:#fff;font-size:0.95rem;font-weight:600;padding:2px 12px;border-radius:8px;}
    .promo-card-body {padding:14px 14px 10px 14px;display:flex;flex-direction:column;gap:4px;}
    .promo-title {font-size:1.08rem;font-weight:700;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .promo-desc {font-size:0.97rem;color:#666;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .promo-meta {display:flex;gap:12px;font-size:0.98rem;color:#888;margin:4px 0 2px 0;align-items:center;}
    .promo-deal {font-size:0.98rem;color:#00b14f;font-weight:500;display:flex;align-items:center;gap:4px;}
    .promo-arrow {position:absolute;top:50%;transform:translateY(-50%);background:#fff;border:none;box-shadow:0 2px 8px rgba(0,0,0,0.08);border-radius:50%;width:38px;height:38px;display:flex;align-items:center;justify-content:center;z-index:2;cursor:pointer;transition:background 0.18s;}
    .promo-arrow-left {left:-18px;}
    .promo-arrow-right {right:-18px;}
    .promo-arrow:hover {background:#e6f9f0;}
    .no-scrollbar {scrollbar-width: none; -ms-overflow-style: none;}
    .no-scrollbar::-webkit-scrollbar {display: none;}
    @media (max-width: 900px) {.promo-card,.promo-carousel{min-width:220px;max-width:220px;}}
    @media (max-width: 600px) {
      .promo-card,.promo-carousel{min-width:170px;max-width:170px;}
      .promo-img-wrap{height:90px;}
      .promo-arrow{width:30px;height:30px;}
      .promo-arrow-left{left:-8px;}.promo-arrow-right{right:-8px;}
    }
  </style>
  <script>
    const promoCarousel = document.getElementById('promoCarousel');
    const promoCards = promoCarousel.querySelectorAll('.promo-card');
    let promoIndex = 0;
    function getVisibleCount() {
      const card = promoCards[0];
      if (!card) return 1;
      const cardWidth = card.offsetWidth + 22;
      return Math.floor(promoCarousel.parentElement.offsetWidth / cardWidth);
    }
    function promoNext() {
      const visible = getVisibleCount();
      const maxIndex = promoCards.length - visible;
      promoIndex = Math.min(promoIndex + 1, maxIndex > 0 ? maxIndex : 0);
      scrollToIndex();
    }
    function promoPrev() {
      promoIndex = Math.max(promoIndex - 1, 0);
      scrollToIndex();
    }
    function scrollToIndex() {
      const card = promoCards[0];
      if (!card) return;
      const cardWidth = card.offsetWidth + 22;
      promoCarousel.scrollTo({left: promoIndex * cardWidth, behavior: 'smooth'});
    }
    window.addEventListener('resize', function() {
      scrollToIndex();
    });
  </script>
</section>
<!-- Popular Foods Section End -->
 



<!-- Featured Restaurants Section Start -->
    <section class="featured-restaurants">
      <div class="container">
          <div class="row mb-3">
            <div class="col-12">
              <h2 style="font-weight:700;font-size:2.2rem;"><span style="color:#00b14f;">Quán ngon</span> <span style="color:#00b14f;">Quanh đây</span></h2>
            </div>
          </div>
          <div class="row g-4">
            <?php  
            $ress= mysqli_query($db,"select * from restaurant LIMIT 4");  
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $now = date('H:i');
            // Lấy danh sách id các nhà hàng đã yêu thích của user
            $user_fav_ids = array();
            if (!empty($_SESSION['user_id'])) {
              $uid = $_SESSION['user_id'];
              $fav_res = mysqli_query($db, "SELECT res_id FROM worthy WHERE user_id='$uid'");
              while($row_fav = mysqli_fetch_assoc($fav_res)) {
                $user_fav_ids[] = $row_fav['res_id'];
              }
            }
            while($rows=mysqli_fetch_array($ress))
            {
              $open = $rows['o_hr'];
              $close = $rows['c_hr'];
              $isOpen = ($now >= $open && $now <= $close);
              $statusText = $isOpen ? 'Mở cửa' : 'Đóng cửa';
              $statusClass = $isOpen ? 'open-badge' : 'close-badge';
              $statusColor = $isOpen ? '#43a047' : '#d32f2f';
              $query= mysqli_query($db,"select * from res_category where c_id='".$rows['c_id']."' ");
              $rowss=mysqli_fetch_array($query);
              $isFav = in_array($rows['rs_id'], $user_fav_ids);
              echo '<div class="col-12 col-sm-6 col-md-3">';
              echo '<div class="restaurant-card shadow-sm" style="border-radius:16px; background:#fff; overflow:hidden; position:relative;">';
              echo '<div class="promo-badge" style="position:absolute;top:12px;left:12px;background:#00b14f;color:#fff;font-size:0.93rem;font-weight:600;padding:2px 12px;border-radius:8px;z-index:2;">PROMO</div>';
              echo '<a href="dishes.php?res_id='.$rows['rs_id'].'" style="display:block;">';
              echo '<img src="admin/Res_img/'.$rows['image'].'" alt="Restaurant logo" style="width:100%;height:120px;object-fit:cover;">';
              echo '</a>';
              echo '<div style="padding:16px 16px 10px 16px;">';
              echo '<div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">';
              echo '<span class="'.$statusClass.'" style="color:'.$statusColor.';font-weight:600;font-size:1.01rem;">'.$statusText.'</span>';
              // Icon trái tim: đỏ nếu đã yêu thích, viền nếu chưa
              if($isFav) {
                echo '<button class="heart-btn" onclick="handleHeart('.$rows['rs_id'].')" style="background:none;border:none;outline:none;float:right;cursor:pointer;"><i class="fa fa-heart" id="heart-'.$rows['rs_id'].'" style="font-size:1.3rem;color:#d32f2f;"></i></button>';
              } else {
                echo '<button class="heart-btn" onclick="handleHeart('.$rows['rs_id'].')" style="background:none;border:none;outline:none;float:right;cursor:pointer;"><i class="fa fa-heart-o" id="heart-'.$rows['rs_id'].'" style="font-size:1.3rem;color:#d32f2f;"></i></button>';
              }
              echo '</div>';
              echo '<a href="dishes.php?res_id='.$rows['rs_id'].'" style="text-decoration:none;color:#222;"><div style="font-weight:700;font-size:1.08rem;line-height:1.2;">'.$rows['title'].'</div></a>';
              echo '<div style="color:#888;font-size:0.98rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'.$rows['address'].'</div>';
              // Giả lập rating
              echo '<div style="margin-top:6px;font-size:0.97rem;color:#444;"><i class="fa fa-star" style="color:#ffc107;"></i> 4,9 <span style="color:#888;">(66)</span></div>';
              echo '</div>';
              echo '</div>';
              echo '</div>';
            }
            ?>
          </div>
          <div class="text-center mt-4">
            <a href="Foods.php" class="btn btn-outline-primary" style="border-radius:12px;padding:10px 38px;font-weight:600;font-size:1.08rem;">Xem thêm</a>
          </div>
      </div>
      <style>
        .restaurant-card {transition:box-shadow 0.2s;}
        .restaurant-card:hover {box-shadow:0 8px 32px rgba(0,177,79,0.13);}
        .close-badge {color:#d32f2f!important;}
        .open-badge {color:#43a047!important;}
        .heart-btn:active i, .heart-btn:focus i {color:#b71c1c;}
      </style>
      <script>
        function handleHeart(resId) {
          <?php if(empty($_SESSION['user_id'])): ?>
            window.location.href = 'login.php';
          <?php else: ?>
            window.location.href = 'worthy.php?res_id=' + resId;
          <?php endif; ?>
        }
      </script>
</section>
<!-- Featured Restaurants Section End -->  





<!-- Order Protection Section Start -->
<section class="order-protection-section">
    <div class="container">
        <h2 class="section-title">Đơn hàng của bạn sẽ được bảo quản như thế nào?</h2>
        <div class="protection-content">
            <img src="images/img/quytrinhnhan.png" alt="protection-img" class="protection-image">
            <p>FastFood sẽ bảo quản đơn của bạn bằng túi & thùng để chống nắng mưa, giữ nhiệt... trên đường đi một cách tốt nhất.</p>
        </div>
    </div>
  <style>
    .order-protection-section {
        background-color: #f8f8f8;
        padding: 30px 0;
        margin: 20px 0;
        border-radius: 8px;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .section-title {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        text-align: center;
    }
    
    .protection-content {
        text-align: center;
        font-size: 16px;
        color: #555;
        line-height: 1.6;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .protection-content p {
        margin: 0;
    }
  </style>
</section>

<!-- Order Protection Section End -->



<!-- Testimonial Section Start -->
<section class="testimonial-section">
  <div class="container">
    <h1 class="main-title">CÁC ĐỐI TÁC CỦA FASTFOOD</h1>
    
    <div class="testimonial-grid">
      <!-- Testimonial 1 -->
      <div class="testimonial-card">
        <h2 class="partner-name">THE CUPS COFFEE</h2>
        <p class="partner-location">- Đà Nẵng</p>
        <div class="partner-comment">
          <p>Trước đây, phần lớn doanh thu của chúng tôi đến từ khách hàng thường thức tại quán, thì nay hình thức đặt hàng trực tuyến đã đóng góp hơn 70% doanh thu và hỗ trợ duy trì phát triển kinh doanh ổn định. Trong đó, FastFood vẫn luôn đóng vai trò quan trọng hơn hết.</p>
        </div>
      </div>
      <!-- Testimonial 2 -->
      <div class="testimonial-card">
        <h2 class="partner-name">BÁNH MÌ MINH NHẬT</h2>
        <p class="partner-location">- Hà Nội</p>
        <div class="partner-comment">
          <p>Nhờ có FastFood, việc đảm bảo kinh doanh và vận hành trong thời kỳ dịch bệnh diễn biến phức tạp không còn là nỗi lo cho những người làm nghề như chúng tôi. Doanh thu chúng tôi nhận được từ việc giao nhận thức ăn qua FastFood giờ đạt mức tăng trưởng hơn 20% và lượng đơn hàng tăng gần 30% so với trước Tết.</p>
        </div>
      </div>
      
      <!-- Testimonial 3 -->
      <div class="testimonial-card">
        <h2 class="partner-name">LONG KEE CHA</h2>
        <p class="partner-location">- TP. Hồ Chí Minh</p>
        <div class="partner-comment">
          <p>Là một startup mới trong ngành dịch vụ ăn uống tại thời điểm bất lợi như hiện nay, việc phối hợp cùng dịch vụ đặt thức ăn trực tuyến và giao nhận như FastFood giúp chúng tôi tiếp cận nguồn khách hàng tiềm năng tại thành phố HCM một cách nhanh chóng... kết quả thể hiện rõ rệt ở mức tăng trưởng hơn 30% doanh thu so với thời điểm chúng tôi chỉ tập trung bán tại Quận.</p>
        </div>
      </div>
    </div>
  </div>
  <style>
    .testimonial-section {
      background-color: #f9f9f9;
      padding: 60px 0;
      font-family: 'Arial', sans-serif;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    .main-title {
      text-align: center;
      font-size: 32px;
      color: #e74c3c;
      margin-bottom: 50px;
      text-transform: uppercase;
      font-weight: 700;
    }
    
    .testimonial-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
    }
    
    .testimonial-card {
      background: white;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .partner-name {
      font-size: 22px;
      color: #333;
      margin-bottom: 5px;
    }
    
    .partner-location {
      color: #e74c3c;
      font-style: italic;
      margin-bottom: 15px;
    }
    
    .partner-comment p {
      color: #555;
      line-height: 1.6;
      font-size: 16px;
    }
 </style>
</section>
<!-- Testimonial Section End -->




<!-- Topcontrol Navigation Start -->
<div class="topcontrol-nav" id="topcontrolNav">
  <div class="topcontrol-group" tabindex="0">
    <button class="topcontrol-main-btn"><i class="fa fa-bars"></i></button>
    <div class="topcontrol-popup">
      <button class="topcontrol-btn" title="Lên đầu trang" onclick="window.scrollTo({top:0,behavior:'smooth'})"><i class="fa fa-arrow-up"></i></button>
      <button class="topcontrol-btn" title="Đăng ký tài xế" onclick="window.location.href='shipper_app/login.php'"><i class="fa fa-motorcycle"></i></button>
      <button class="topcontrol-btn" title="Quản lý cửa hàng" onclick="window.location.href='Mng_shop/login.php'"><i class="fa fa-rocket"></i></button>
      <button class="topcontrol-btn" title="Trang admin" onclick="window.location.href='admin/login.php'"><i class="fa fa-user-secret"></i></button>
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
</script>
<script>
// Popup quảng cáo slider chuyên nghiệp
document.addEventListener('DOMContentLoaded', function() {
  var adModal = document.getElementById('adModal');
  var closeAdModal = document.getElementById('closeAdModal');
  var adSlides = document.querySelectorAll('.ad-slide');
  var adPrev = document.getElementById('adPrev');
  var adNext = document.getElementById('adNext');
  var adDots = document.getElementById('adDots');
  var currentAd = 0;
  var adTimer = null;
  function showAdSlide(idx) {
    adSlides.forEach(function(slide,i){slide.style.display = (i===idx)?'block':'none';});
    adDots.innerHTML = '';
    for(var i=0;i<adSlides.length;i++){
      var dot = document.createElement('span');
      dot.style.cssText = 'display:inline-block;width:12px;height:12px;margin:0 4px;background:'+(i===idx?'#ff9800':'#eee')+';border-radius:50%;cursor:pointer;transition:background 0.18s;';
      dot.onclick = (function(j){return function(){currentAd=j;showAdSlide(j);resetAdTimer();};})(i);
      adDots.appendChild(dot);
    }
  }
  function nextAd(){currentAd=(currentAd+1)%adSlides.length;showAdSlide(currentAd);resetAdTimer();}
  function prevAd(){currentAd=(currentAd-1+adSlides.length)%adSlides.length;showAdSlide(currentAd);resetAdTimer();}
  function resetAdTimer(){
    if(adTimer)clearInterval(adTimer);
    adTimer=setInterval(nextAd,3500);
  }
  adPrev.onclick=prevAd;
  adNext.onclick=nextAd;
  showAdSlide(currentAd);
  resetAdTimer();
  adModal.style.display = 'flex';
  closeAdModal.onclick = function() {
    adModal.style.display = 'none';
    if(adTimer)clearInterval(adTimer);
  };
  adModal.onclick = function(e) {
    if (e.target === adModal) {
      adModal.style.display = 'none';
      if(adTimer)clearInterval(adTimer);
    }
  };
});
</script>
</script>
<script>
// Hiển thị popup quảng cáo khi vào trang
document.addEventListener('DOMContentLoaded', function() {
  var adModal = document.getElementById('adModal');
  var closeAdModal = document.getElementById('closeAdModal');
  adModal.style.display = 'flex';
  closeAdModal.onclick = function() {
    adModal.style.display = 'none';
  };
  // Đóng khi click ra ngoài modal
  adModal.onclick = function(e) {
    if (e.target === adModal) adModal.style.display = 'none';
  };
});
</script>
</body>

</html>