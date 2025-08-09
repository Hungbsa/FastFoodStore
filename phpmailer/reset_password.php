<?php
// reset_password.php
include("../connection/connect.php");
$message = '';
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($db, $_GET['token']);
    $userq = mysqli_query($db, "SELECT u_id, reset_token_expiry FROM users WHERE reset_token='$token' LIMIT 1");
    $user = mysqli_fetch_assoc($userq);
    if (!$user) {
        $message = 'Link không hợp lệ hoặc đã hết hạn!';
    } else if (strtotime($user['reset_token_expiry']) < time()) {
        $message = 'Link đã hết hạn!';
    } else if (isset($_POST['new_password'])) {
        $new_password = $_POST['new_password'];
        if (strlen($new_password) < 6) {
            $message = 'Mật khẩu phải từ 6 ký tự!';
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            mysqli_query($db, "UPDATE users SET password='$hashed', reset_token=NULL, reset_token_expiry=NULL WHERE u_id='{$user['u_id']}'");
            $message = 'Đổi mật khẩu thành công! Bạn có thể đăng nhập.';
        }
    }
} else {
    $message = 'Link không hợp lệ!';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background: linear-gradient(120deg, #ffb347 0%, #ff9800 100%);
            min-height: 100vh;
        }
        .reset-container {
            max-width: 400px;
            margin: 80px auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.13);
            padding: 38px 32px 28px 32px;
        }
        .reset-container h3 {
            margin-bottom: 24px;
            color: #ff9800;
            font-weight: bold;
            text-align: center;
        }
        .form-group label {
            font-weight: 500;
            color: #333;
        }
        .form-control {
            border-radius: 8px;
            font-size: 1.08rem;
            padding: 10px 12px;
            border: 1px solid #bbb;
        }
        .btn-primary {
            background: #ff9800;
            border: none;
            font-weight: 600;
            font-size: 1.13rem;
            border-radius: 8px;
            padding: 12px 0;
            margin-top: 8px;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background: #fb8c00;
        }
        .msg {
            font-size: 1.05rem;
            margin-bottom: 18px;
            text-align: center;
        }
        .msg-success {
            color: #43a047;
        }
        .msg-error {
            color: #d32f2f;
        }
        .back-link {
            display: block;
            margin-top: 24px;
            text-align: center;
            color: #2196f3;
            font-size: 1.05rem;
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #1565c0;
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="reset-container">
    <h3>Đổi mật khẩu</h3>
    <?php if ($message) {
        $msgClass = strpos($message,'thành công')!==false ? 'msg-success' : 'msg-error';
        echo '<div class="msg '.$msgClass.'">'.$message.'</div>';
    } ?>
    <?php if (isset($user) && $user && strtotime($user['reset_token_expiry']) >= time() && (!isset($_POST['new_password']) || strpos($message,'thành công')===false)) { ?>
    <form method="post" id="resetForm">
        <div class="form-group">
            <label for="new_password">Mật khẩu mới</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required minlength="6" />
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Đổi mật khẩu</button>
    </form>
    <script>
        // Hiệu ứng focus cho input
        document.getElementById('new_password').addEventListener('focus', function(){
            this.style.borderColor = '#ff9800';
            this.style.boxShadow = '0 0 0 2px #ffe0b2';
        });
        document.getElementById('new_password').addEventListener('blur', function(){
            this.style.borderColor = '#bbb';
            this.style.boxShadow = 'none';
        });
        // Hiệu ứng submit
        document.getElementById('resetForm').addEventListener('submit', function(){
            var btn = this.querySelector('button');
            btn.innerText = 'Đang xử lý...';
            btn.disabled = true;
        });
    </script>
    <?php } ?>
    <a href="../login.php" class="back-link">Quay lại đăng nhập</a>
</div>
</body>
</html>
