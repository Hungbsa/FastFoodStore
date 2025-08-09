<?php
include("../connection/connect.php");
session_start();
if(empty($_SESSION["user_id"])){
    header("Location: ../login.php");
    exit();
}
$uid = $_SESSION["user_id"];

// Lấy id nhà hàng từ GET
$res_id = isset($_GET['res_id']) ? intval($_GET['res_id']) : 0;
$rows = null;
if ($res_id > 0) {
    $res_q = mysqli_query($db, "SELECT * FROM restaurant WHERE rs_id='$res_id' LIMIT 1");
    $rows = mysqli_fetch_assoc($res_q);
}
if (!$rows) {
    echo '<div style="padding:40px;text-align:center;color:red;font-size:1.2rem;">Không tìm thấy thông tin nhà hàng!</div>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<?php
    error_reporting(0);
    session_start();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đánh giá - <?php echo isset($rows['title']) ? htmlspecialchars($rows['title']) : 'Nhà hàng'; ?></title>
    <link rel="icon" href="../images/img/iconss.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/animsition.min.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet"> 
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary: #f72585;
            --success: #4cc9f0;
            --warning: #ffbe0b;
            --danger: #f44336;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f5f7ff;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Header */
        .restaurant-header {
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .restaurant-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
        }

        .restaurant-info {
            padding: 25px;
        }

        .restaurant-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .restaurant-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            color: var(--gray);
        }

        .meta-item i {
            margin-right: 8px;
            color: var(--primary);
            font-size: 1rem;
        }

        .rating-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .rating-stars {
            color: var(--warning);
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .rating-badge {
            background: var(--warning);
            color: white;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-right: 8px;
        }

        .rating-count {
            color: var(--gray);
            font-size: 0.95rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .status-open {
            background: rgba(25, 195, 125, 0.1);
            color: #19c37d;
        }

        .status-closed {
            background: rgba(244, 67, 54, 0.1);
            color: var(--danger);
        }

        .restaurant-map {
            width: 100%;
            height: 250px;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 20px;
            border: 1px solid var(--light-gray);
        }

        /* Main Content */
        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 30px;
        }

        @media (max-width: 992px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
        }

        /* Reviews Section */
        .reviews-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--dark);
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--primary);
        }

        /* Review Form */
        .review-form {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--light-gray);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            outline: none;
        }

        .rating-select {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rating-select select {
            flex: 1;
            max-width: 200px;
        }

        .rating-preview {
            display: flex;
            gap: 3px;
            color: var(--warning);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .btn i {
            margin-right: 8px;
        }

        /* Review List */
        .review-list {
            margin-top: 30px;
        }

        .review-item {
            padding: 20px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .review-user {
            font-weight: 600;
            color: var(--dark);
        }

        .review-date {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .review-rating {
            color: var(--warning);
            margin-right: 8px;
            font-size: 0.95rem;
        }

        .review-content {
            color: var(--dark);
            margin: 10px 0;
            line-height: 1.6;
        }

        .review-image {
            max-width: 200px;
            border-radius: 8px;
            margin-top: 10px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .review-image:hover {
            transform: scale(1.03);
        }

        .review-reply {
            margin-top: 15px;
            padding: 15px;
            background: #f5f7ff;
            border-radius: 10px;
            position: relative;
        }

        .review-reply:before {
            content: "";
            position: absolute;
            top: -10px;
            left: 20px;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 10px solid #f5f7ff;
        }

        .reply-label {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 5px;
            display: block;
        }

        /* Featured Reviews */
        .featured-reviews {
            background: #f5f7ff;
            border-radius: 16px;
            padding: 25px;
            margin-top: 30px;
        }

        .featured-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
            display: flex;
            align-items: center;
        }

        .featured-title i {
            color: var(--warning);
            margin-right: 10px;
        }

        /* Alert */
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .alert-success {
            background: rgba(76, 201, 240, 0.1);
            border: 1px solid var(--success);
            color: var(--success);
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .review-item {
            animation: fadeIn 0.4s ease-out forwards;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .restaurant-title {
                font-size: 1.7rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .restaurant-image {
                height: 200px;
            }
            
            .restaurant-info {
                padding: 20px;
            }
            
            .reviews-section, .review-form {
                padding: 20px;
            }
        }
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

<?php

// Lấy id nhà hàng từ GET
$res_id = isset($_GET['res_id']) ? intval($_GET['res_id']) : 0;
$rows = null;
if ($res_id > 0) {
    $res_q = mysqli_query($db, "SELECT * FROM restaurant WHERE rs_id='$res_id' LIMIT 1");
    $rows = mysqli_fetch_assoc($res_q);
}
if (!$rows) {
    echo '<div style="padding:40px;text-align:center;color:red;font-size:1.2rem;">Không tìm thấy thông tin nhà hàng!</div>';
    exit;
}

function timeToMinutes($time) {
    $parts = explode(':', $time);
    return intval($parts[0]) * 60 + intval($parts[1]);
}
$now = new DateTime();
$today = (int)$now->format('N')-1;
$open = isset($rows['o_hr_'.$today]) ? $rows['o_hr_'.$today] : $rows['o_hr'];
$close = isset($rows['c_hr_'.$today]) ? $rows['c_hr_'.$today] : $rows['c_hr'];
$now_minutes = intval($now->format('H'))*60+intval($now->format('i'));
$open_minutes = intval(substr($open,0,2))*60+intval(substr($open,3,2));
$close_minutes = intval(substr($close,0,2))*60+intval(substr($close,3,2));

if ($open_minutes == $close_minutes) {
    $is_open = false;
} else if ($open_minutes < $close_minutes) {
    $is_open = ($now_minutes >= $open_minutes && $now_minutes < $close_minutes);
} else {
    $is_open = ($now_minutes >= $open_minutes || $now_minutes < $close_minutes);
}

$avg_rating = 0;
$rating_count = 0;
$rt_q = mysqli_query($db, "SELECT AVG(rating) as avg_rating, COUNT(*) as cnt FROM rating WHERE rs_id='$res_id'");
if ($rt = mysqli_fetch_assoc($rt_q)) {
    $avg_rating = round(floatval($rt['avg_rating']),1);
    $rating_count = intval($rt['cnt']);
}
?>
<div class="container" style="margin-top:90px;">
    <!-- Restaurant Header -->
    <div class="restaurant-header">
        <img src="../Mng_shop/admin/Res_img/<?php echo htmlspecialchars($rows['image']); ?>" alt="<?php echo htmlspecialchars($rows['title']); ?>" class="restaurant-image">
        <div class="restaurant-info">
            <h1 class="restaurant-title">
                <?php echo htmlspecialchars($rows['title']); ?>
                <span class="status-badge <?php echo $is_open ? 'status-open' : 'status-closed'; ?>" style="position:absolute;right:32px;top:18px;background:<?php echo $is_open ? 'rgba(25,195,125,0.1)' : 'rgba(244,67,54,0.12)'; ?>;color:<?php echo $is_open ? '#19c37d' : '#f44336'; ?>;padding:8px 18px;font-size:1rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border-radius:22px;display:inline-flex;align-items:center;gap:6px;">
                    <i class="fas fa-circle" style="font-size:0.8rem;margin-right:6px;"></i>
                    <?php echo $is_open ? 'Đang mở cửa' : 'Đã đóng cửa'; ?>
                </span>
            </h1>
            <div class="restaurant-meta">
                <div class="meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo htmlspecialchars($rows['address']); ?>
                </div>
                <div class="meta-item">
                    <i class="fas fa-phone"></i>
                    <?php echo htmlspecialchars($rows['phone']); ?>
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                        <?php echo date('H:i', strtotime($open)); ?> - <?php echo date('H:i', strtotime($close)); ?>
                        <span id="openTimeInfo" style="margin-left:8px;cursor:pointer;color:#2196f3;" title="Xem lịch mở cửa"><i class="fas fa-info-circle"></i></span>
                </div>
            </div>
            <div class="rating-container">
                <div class="rating-stars">
                    <?php 
                    $full_stars = floor($avg_rating);
                    $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
                    $empty_stars = 5 - $full_stars - $half_star;
                    for ($i = 0; $i < $full_stars; $i++) echo '<i class="fas fa-star"></i>';
                    if ($half_star) echo '<i class="fas fa-star-half-alt"></i>';
                    for ($i = 0; $i < $empty_stars; $i++) echo '<i class="far fa-star"></i>';
                    ?>
                </div>
                <span class="rating-badge"><?php echo $avg_rating; ?></span>
                <span class="rating-count"><?php echo $rating_count; ?> đánh giá</span>
            </div>
            <div class="restaurant-map">
                <iframe width="100%" height="100%" frameborder="0" style="border:0" src="https://www.google.com/maps?q=<?php echo urlencode($rows['address']); ?>&output=embed" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    
    <div class="content-wrapper">
        <!-- Left Column (Featured Reviews) -->
        <div>
            <div class="featured-reviews">
                <h3 class="featured-title"><i class="fas fa-award"></i> Đánh giá nổi bật</h3>
                <?php
                $featured_q = mysqli_query($db, "SELECT r.*, u.username FROM rating r LEFT JOIN users u ON r.user_id = u.u_id WHERE r.rs_id='$res_id' ORDER BY r.rating DESC, r.date DESC LIMIT 3");
                if ($featured_q && mysqli_num_rows($featured_q) > 0) {
                    while($fr = mysqli_fetch_assoc($featured_q)) {
                        echo '<div class="review-item">';
                        echo '<div class="review-header">';
                        echo '<div>';
                        echo '<span class="review-user">'.htmlspecialchars($fr['username'] ?? 'Ẩn danh').'</span>';
                        echo '<span class="review-date">'.date('d/m/Y H:i', strtotime($fr['date'])).'</span>';
                        echo '</div>';
                        echo '<div class="review-rating">';
                        echo number_format($fr['rating'],1).' ';
                        for($i=1;$i<=5;$i++) echo ($fr['rating'] >= $i ? '<i class="fas fa-star"></i>' : ($fr['rating'] >= $i-0.5 ? '<i class="fas fa-star-half-alt"></i>' : '<i class="far fa-star"></i>'));
                        echo '</div>';
                        echo '</div>';
                        if (!empty($fr['image']) && $fr['image'] !== 'NULL' && $fr['image'] !== '-') {
                            $img_path = '../uploads/review_images/'.htmlspecialchars($fr['image']);
                            if (file_exists($img_path) && !is_dir($img_path)) {
                                echo '<img src="'.$img_path.'" class="review-image">';
                            }
                        }
                        $content = $fr['comment'] ?? $fr['review'];
                        echo '<div class="review-content">'.nl2br(htmlspecialchars($content)).'</div>';
                        if (!empty($fr['reply'])) {
                            echo '<div class="review-reply">';
                            echo '<span class="reply-label"><i class="fas fa-reply"></i> Phản hồi từ nhà hàng</span>';
                            echo htmlspecialchars($fr['reply']);
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<div style="color:var(--gray);padding:20px 0;">Chưa có đánh giá nổi bật.</div>';
                }
                ?>
            </div>
        </div>
        
        <!-- Right Column -->
        <div>
            <!-- Review Form -->
            <div class="review-form">
                <h3 class="section-title"><i class="fas fa-pen-alt"></i> Viết đánh giá</h3>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group rating-select">
                        <label class="form-label">Đánh giá của bạn:</label>
                        <div class="rating-preview" id="starRating" style="font-size:2rem; cursor:pointer;">
                            <i class="far fa-star" data-value="1"></i>
                            <i class="far fa-star" data-value="2"></i>
                            <i class="far fa-star" data-value="3"></i>
                            <i class="far fa-star" data-value="4"></i>
                            <i class="far fa-star" data-value="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="5">
                        <div id="ratingLabel" style="margin-top:8px;color:var(--warning);font-weight:500;">5 - Tuyệt vời</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="comment" class="form-label">Nội dung đánh giá</label>
                        <textarea name="comment" id="comment" class="form-control" required placeholder="Hãy chia sẻ trải nghiệm của bạn..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="review_image" class="form-label">Hình ảnh (nếu có)</label>
                        <input type="file" name="review_image" id="review_image" accept="image/*" class="form-control">
                        <small style="color: var(--gray); font-size: 0.85rem;">Tải lên hình ảnh về món ăn hoặc không gian nhà hàng</small>
                    </div>
                    
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) { ?>
                        <button type="submit" name="submit_review" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Gửi đánh giá
                        </button>
                    <?php } else { ?>
                        <div class="alert alert-warning" style="margin-top:20px;">
                            <i class="fas fa-exclamation-circle"></i> Vui lòng <a href="../login.php" style="color:var(--primary);text-decoration:underline;">đăng nhập</a> để gửi đánh giá.
                        </div>
                    <?php } ?>
                </form>
                
                <?php
                if(isset($_POST['submit_review'])) {
                    if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
                        echo '<div class="alert alert-danger" style="margin-top:20px;">
                                <i class="fas fa-exclamation-circle"></i> Bạn cần đăng nhập để gửi đánh giá.
                            </div>';
                    } else {
                        $user_id = $_SESSION['user_id'];
                        $rating = floatval($_POST['rating']);
                        $comment = mysqli_real_escape_string($db, $_POST['comment']);
                        $image_name = '';
                        if(isset($_FILES['review_image']) && $_FILES['review_image']['error'] == 0) {
                            $ext = pathinfo($_FILES['review_image']['name'], PATHINFO_EXTENSION);
                            $image_name = uniqid('rv_', true).'.'.$ext;
                            move_uploaded_file($_FILES['review_image']['tmp_name'], '../uploads/review_images/'.$image_name);
                        }
                        $now = date('Y-m-d H:i:s');
                        $insert_query = "INSERT INTO rating (rs_id, user_id, rating, comment, image, date) 
                                        VALUES ('$res_id', '$user_id', '$rating', '$comment', '$image_name', '$now')";
                        if(mysqli_query($db, $insert_query)) {
                            echo '<div class="alert alert-success" style="margin-top:20px;">
                                    <i class="fas fa-check-circle"></i> Đánh giá của bạn đã được gửi thành công!
                                </div>';
                            // Reload lại trang để hiển thị đánh giá mới
                            header("Location: " . $_SERVER['REQUEST_URI']);
                            exit;
                        } else {
                            echo '<div class="alert alert-danger" style="margin-top:20px;">
                                    <i class="fas fa-exclamation-circle"></i> Lỗi: '.mysqli_error($db).'
                                </div>';
                        }
                    }
                }
                ?>
            </div>
            
            <!-- All Reviews -->
            <div class="reviews-section">
                <h3 class="section-title"><i class="fas fa-comments"></i> Tất cả đánh giá</h3>
                
                <div class="review-list">
                    <?php
                    $review_q = mysqli_query($db, "SELECT r.*, u.username FROM rating r LEFT JOIN users u ON r.user_id = u.u_id WHERE r.rs_id='$res_id' ORDER BY r.date DESC");
                    if ($review_q && mysqli_num_rows($review_q) > 0) {
                        while($rv = mysqli_fetch_assoc($review_q)) {
                            echo '<div class="review-item">';
                            echo '<div class="review-header">';
                            echo '<div>';
                            echo '<span class="review-user">'.htmlspecialchars($rv['username'] ?? 'Ẩn danh').'</span>';
                            echo '<span class="review-date">'.date('d/m/Y H:i', strtotime($rv['date'])).'</span>';
                            echo '</div>';
                            echo '<div class="review-rating">';
                            echo number_format($rv['rating'],1).' ';
                            for($i=1;$i<=5;$i++) echo ($rv['rating'] >= $i ? '<i class="fas fa-star"></i>' : ($rv['rating'] >= $i-0.5 ? '<i class="fas fa-star-half-alt"></i>' : '<i class="far fa-star"></i>'));
                            echo '</div>';
                            echo '</div>';
                            if (!empty($rv['image']) && $rv['image'] !== 'NULL' && $rv['image'] !== '-') {
                                $img_path_rel = '../uploads/review_images/'.htmlspecialchars($rv['image']);
                                $img_path_abs = realpath(__DIR__ . '/../uploads/review_images/' . $rv['image']);
                                if ($img_path_abs && file_exists($img_path_abs) && !is_dir($img_path_abs)) {
                                    echo '<img src="'.$img_path_rel.'" class="review-image">';
                                }
                            }
                            $content = $rv['comment'] ?? $rv['review'];
                            echo '<div class="review-content">'.nl2br(htmlspecialchars($content)).'</div>';
                            if (!empty($rv['reply'])) {
                                echo '<div class="review-reply">';
                                echo '<span class="reply-label"><i class="fas fa-reply"></i> Phản hồi từ nhà hàng</span>';
                                echo htmlspecialchars($rv['reply']);
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                    } else {
                        echo '<div style="text-align:center; padding:30px 0; color:var(--gray);">
                                <i class="far fa-comment-dots" style="font-size:2rem; margin-bottom:15px; opacity:0.5;"></i>
                                <p>Chưa có đánh giá nào cho nhà hàng này.</p>
                              </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Popup giờ mở cửa từng ngày, đặt ngoài container để đảm bảo overlay toàn trang -->
<div id="openTimePopup" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.18);">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,0.12);padding:32px 28px;min-width:320px;max-width:95vw;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
            <h4 style="font-size:1.2rem;font-weight:700;color:#222;margin:0;display:flex;align-items:center;gap:8px;"><i class="fas fa-clock"></i> Giờ mở cửa trong tuần</h4>
            <button id="closeOpenTimePopup" style="background:none;border:none;font-size:1.5rem;color:#888;cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <table style="width:100%;border-collapse:collapse;">
            <tbody>
            <?php
            $weekday_names = ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'];
            $today_idx = (int)date('N')-1;
            $now_minutes = intval(date('H'))*60+intval(date('i'));
            for($i=0;$i<7;$i++) {
                $o_hr = isset($rows['o_hr_'.$i]) && $rows['o_hr_'.$i] ? $rows['o_hr_'.$i] : $rows['o_hr'];
                $c_hr = isset($rows['c_hr_'.$i]) && $rows['c_hr_'.$i] ? $rows['c_hr_'.$i] : $rows['c_hr'];
                $o_min = intval(substr($o_hr,0,2))*60+intval(substr($o_hr,3,2));
                $c_min = intval(substr($c_hr,0,2))*60+intval(substr($c_hr,3,2));
                $is_open_day = false;
            if ($i == $today_idx) {
                if ($o_min == $c_min) {
                    $is_open_day = false; // Coi như đóng cửa nếu giờ mở = giờ đóng
                } else if ($o_min < $c_min) {
                    // Trường hợp mở cửa trong cùng 1 ngày (ví dụ 8:00 - 22:00)
                    $is_open_day = ($now_minutes >= $o_min && $now_minutes < $c_min);
                } else {
                    // Trường hợp mở cửa qua đêm (ví dụ 18:00 - 2:00 sáng hôm sau)
                    $is_open_day = ($now_minutes >= $o_min || $now_minutes < $c_min);
                }
            } 
                echo '<tr style="border-bottom:1px solid #eee;">';
                echo '<td style="padding:8px 0;font-weight:500;color:#222;">'.$weekday_names[$i].'</td>';
                echo '<td style="padding:8px 0;color:#555;">'.date('H:i',strtotime($o_hr)).' - '.date('H:i',strtotime($c_hr)).'</td>';
                echo '<td style="padding:8px 0;">';
                if ($i==$today_idx) {
                    echo '<span class="status-badge '.($is_open_day?'status-open':'status-closed').'" style="font-size:0.95rem;padding:4px 10px;background:'.($is_open_day?'rgba(25,195,125,0.1)':'rgba(244,67,54,0.12)').';color:'.($is_open_day?'#19c37d':'#f44336').';">'.($is_open_day?'<i class="fas fa-circle" style="font-size:0.7rem;margin-right:5px;"></i>Đang mở cửa':'<i class="fas fa-circle" style="font-size:0.7rem;margin-right:5px;"></i>Đã đóng cửa').'</span>';
                }
                echo '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
                    
                    <footer class="footer">
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
<script>
    // Star rating interactive
    const starRating = document.getElementById('starRating');
    const ratingInput = document.getElementById('ratingInput');
    const ratingLabel = document.getElementById('ratingLabel');
    const ratingTexts = {
        1: 'Không hài lòng',
        2: 'Bình thường',
        3: 'Tốt',
        4: 'Rất tốt',
        5: 'Tuyệt vời'
    };
    let selected = 5;
    function updateStars(rating) {
        Array.from(starRating.children).forEach((star, idx) => {
            star.className = (idx < rating) ? 'fas fa-star' : 'far fa-star';
        });
        ratingLabel.textContent = ratingTexts[rating];
    }
    starRating.addEventListener('mousemove', function(e) {
        if (e.target.dataset.value) {
            updateStars(Number(e.target.dataset.value));
        }
    });
    starRating.addEventListener('mouseleave', function() {
        updateStars(selected);
    });
    starRating.addEventListener('click', function(e) {
        if (e.target.dataset.value) {
            selected = Number(e.target.dataset.value);
            ratingInput.value = selected;
            updateStars(selected);
        }
    });
    updateStars(selected);

    // Hide header on scroll down, show on scroll up
    let lastScroll = 0;
    const header = document.getElementById('header');
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        if (currentScroll > lastScroll && currentScroll > 80) {
            header.style.transform = 'translateY(-100%)';
        } else {
            header.style.transform = 'translateY(0)';
        }
        lastScroll = currentScroll;
    });
</script>
<script>
    const openTimeInfo = document.getElementById('openTimeInfo');
    const openTimePopup = document.getElementById('openTimePopup');
    const closeOpenTimePopup = document.getElementById('closeOpenTimePopup');
    if (openTimeInfo && openTimePopup && closeOpenTimePopup) {
        openTimeInfo.addEventListener('click', function(e) {
            e.stopPropagation();
            openTimePopup.style.display = 'block';
        });
        closeOpenTimePopup.addEventListener('click', function(e) {
            e.stopPropagation();
            openTimePopup.style.display = 'none';
        });
        // Đóng popup khi click ra ngoài vùng popup
        window.addEventListener('click', function(e) {
            if (openTimePopup.style.display === 'block' && !openTimePopup.contains(e.target) && e.target !== openTimeInfo) {
                openTimePopup.style.display = 'none';
            }
        });
        // Ngăn sự kiện click bên trong popup bị lan ra ngoài
        openTimePopup.addEventListener('click', function(e) {
            if (e.target === openTimePopup) {
                openTimePopup.style.display = 'none';
            }
        });
    }
</script>
</body>
</html>