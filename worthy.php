<?php
session_start();
include("connection/connect.php");
if(empty($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('Asia/Ho_Chi_Minh');
// Hiển thị thông báo nếu có
if(isset($_SESSION['delete_success'])) {
    $delete_message = $_SESSION['delete_success'];
    unset($_SESSION['delete_success']);
}

$user_id = $_SESSION["user_id"];
$res_id = isset($_GET['res_id']) ? intval($_GET['res_id']) : 0;
$remove_fav = isset($_GET['remove_fav']) ? intval($_GET['remove_fav']) : 0;

// Xử lý thêm hoặc xóa yêu thích
if ($res_id > 0) {
    $checkTable = mysqli_query($db, "SHOW TABLES LIKE 'worthy'");
    if(mysqli_num_rows($checkTable) == 0) {
        mysqli_query($db, "CREATE TABLE worthy (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, res_id INT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
    }
    $check = mysqli_query($db, "SELECT * FROM worthy WHERE user_id='$user_id' AND res_id='$res_id'");
    if(mysqli_num_rows($check) == 0) {
        mysqli_query($db, "INSERT INTO worthy (user_id, res_id) VALUES ('$user_id', '$res_id')");
    }
}

if ($remove_fav > 0) {
    mysqli_query($db, "DELETE FROM worthy WHERE user_id='$user_id' AND res_id='$remove_fav'");
    $_SESSION['delete_success'] = "Đã xóa khỏi danh sách yêu thích!";
    session_write_close();
    header("Location: worthy.php");
    exit();
}

$fav_query = mysqli_query($db, "SELECT restaurant.*, 
                               TIME_FORMAT(o_hr, '%H:%i') as open_time, 
                               TIME_FORMAT(c_hr, '%H:%i') as close_time,
                               o_days
                               FROM worthy 
                               JOIN restaurant ON worthy.res_id = restaurant.rs_id 
                               WHERE worthy.user_id='$user_id' 
                               ORDER BY worthy.created_at DESC");

function isOpenToday($o_days) {
    $current_day = strtolower(date('D')); // "mon", "tue",...
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

function isOpenNow($open_time, $close_time, $o_days) {
    if (!isOpenToday($o_days)) {
        return false;
    }
    $current_time = date('H:i');
    return ($current_time >= $open_time && $current_time <= $close_time);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images\img\iconss.png">
    <title>Cửa hàng yêu thích - FastFood</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <style>
        .fav-list {max-width:600px;margin:40px auto;}
        .fav-card {background:#fff;border-radius:14px;box-shadow:0 2px 16px rgba(0,0,0,0.07);margin-bottom:18px;}
        .fav-card h5 {margin-bottom:8px;}
        .fav-restaurant-img {width:100%;height:120px;object-fit:cover;border-radius:14px 14px 0 0;}
        .fav-restaurant-title {font-weight:600;font-size:1.08rem;}
        .fav-restaurant-address {color:#888;font-size:0.97rem;}
        .fav-empty {text-align:center;color:#888;margin:40px 0;}
        .promo-badge {position:absolute;top:10px;left:10px;background:#00b14f;color:#fff;font-size:0.93rem;font-weight:600;padding:2px 12px;border-radius:8px;z-index:2;}
        .heart-btn {position:absolute;top:10px;right:10px;background:none;border:none;outline:none;cursor:pointer;z-index:3;}
        .close-badge {position:absolute;top:48px;left:10px;background:#fff;color:#d32f2f;font-size:1.01rem;font-weight:600;padding:2px 12px;border-radius:8px;z-index:2;box-shadow:0 1px 4px rgba(0,0,0,0.04);}
        .open-badge {position:absolute;top:48px;left:10px;background:#fff;color:#00b14f;font-size:1.01rem;font-weight:600;padding:2px 12px;border-radius:8px;z-index:2;box-shadow:0 1px 4px rgba(0,0,0,0.04);}
        .alert-success {position:fixed;top:20px;right:20px;z-index:1000;}
    </style>
</head>
<body>
    <!-- Hiển thị thông báo xóa -->
    <?php if(!empty($delete_message)): ?>
        <div class="alert alert-success alert-dismissible fade show delete-toast" role="alert" style="position:fixed;top:24px;right:24px;z-index:10000;min-width:220px;">
            <i class="fa fa-check-circle" style="color:#00b14f;font-size:1.3rem;margin-right:8px;"></i>
            <?php echo $delete_message; ?>
            <button type="button" class="close" onclick="this.parentElement.style.display='none';" aria-label="Close" style="outline:none;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <script>
            setTimeout(function() {
                var toast = document.querySelector('.delete-toast');
                if(toast) {
                    toast.classList.remove('show');
                    toast.classList.add('fade');
                    setTimeout(function(){ toast.style.display = 'none'; }, 400);
                }
            }, 2000);
        </script>
    <?php endif; ?>

    <div class="container fav-list">
        <h2 class="mb-4" style="font-weight:700;color:#00b14f;">Cửa hàng yêu thích</h2>
        <?php if(mysqli_num_rows($fav_query) == 0): ?>
            <div class="fav-empty">
                <i class="fa fa-heart-o" style="font-size:2.2rem;color:#d32f2f;"></i><br>
                Bạn chưa có cửa hàng yêu thích nào.<br>
                <a href="index.php" class="btn btn-outline-primary mt-3">Khám phá ngay</a>
            </div>
        <?php else: ?>
            <?php while($fav = mysqli_fetch_assoc($fav_query)): 
                $is_open = isOpenNow($fav['open_time'], $fav['close_time'], $fav['o_days']);
                // Debug - có thể bỏ sau khi kiểm tra
                echo "<!-- Debug: {$fav['title']} - ".
                    "Open: {$fav['open_time']}, Close: {$fav['close_time']}, ".
                    "Days: {$fav['o_days']}, Current: ".date('H:i l').", ".
                    "Result: ".($is_open ? 'Mở cửa' : 'Đóng cửa')." -->";
            ?>
                <div class="fav-card" style="overflow:hidden;position:relative;">
                    <div style="position:relative;">
                        <img src="Mng_shop/admin/Res_img/<?php echo $fav['image']; ?>" class="fav-restaurant-img" alt="<?php echo htmlspecialchars($fav['title']); ?>">
                        <div class="promo-badge">PROMO</div>
                        <a href="worthy.php?remove_fav=<?php echo $fav['rs_id']; ?>" class="heart-btn" onclick="return confirm('Bạn có chắc muốn xóa khỏi danh sách yêu thích?')">
                            <i class="fa fa-heart" style="font-size:1.5rem;color:#d32f2f;"></i>
                        </a>
                        <span class="<?php echo $is_open ? 'open-badge' : 'close-badge'; ?>">
                            <?php echo $is_open ? 'Mở cửa' : 'Đóng cửa'; ?>
                            (<?php echo $fav['open_time']; ?> - <?php echo $fav['close_time']; ?>)
                            <?php if(!isOpenToday($fav['o_days'])) echo ' - Hôm nay nghỉ'; ?>
                        </span>
                    </div>
                    <div style="padding:16px 16px 10px 16px;">
                        <div style="font-weight:700;font-size:1.08rem;line-height:1.2;"><?php echo htmlspecialchars($fav['title']); ?></div>
                        <div style="color:#888;font-size:0.98rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($fav['address']); ?></div>
                        <div style="margin-top:6px;font-size:0.97rem;color:#444;"><i class="fa fa-star" style="color:#ffc107;"></i> 4,9 <span style="color:#888;">(66)</span></div>
                        <a href="dishes.php?res_id=<?php echo $fav['rs_id']; ?>" class="btn btn-sm btn-success mt-2">Xem menu</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
        <div style="margin-top:18px;">
            <a href="index.php" class="btn btn-outline-primary">Quay lại trang chủ</a>
        </div>
    </div>

    <script src="js/jquery-3.5.1.slim.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>