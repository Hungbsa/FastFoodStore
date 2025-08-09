
<?php
include("../connection/connect.php");
session_start();
if(empty($_SESSION['user_id'])) {
    header('location:../login.php');
    exit();
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = mysqli_real_escape_string($db, $_POST['current_password']);
    $new = mysqli_real_escape_string($db, $_POST['new_password']);
    $confirm = mysqli_real_escape_string($db, $_POST['confirm_password']);
    $user_id = $_SESSION['user_id'];
    $q = mysqli_query($db, "SELECT password FROM users WHERE u_id='$user_id' LIMIT 1");
    $row = mysqli_fetch_assoc($q);
    $isValid = false;
    if ($row) {
        if (
            password_verify($current, $row['password']) ||
            $row['password'] === $current ||
            md5($current) === $row['password']
        ) {
            $isValid = true;
        }
    }
    if (!$row || !$isValid) {
        $msg = '<div class="alert alert-danger">Mật khẩu hiện tại không đúng!</div>';
    } elseif (strlen($new) < 6) {
        $msg = '<div class="alert alert-warning">Mật khẩu mới phải từ 6 ký tự trở lên.</div>';
    } elseif ($new !== $confirm) {
        $msg = '<div class="alert alert-warning">Xác nhận mật khẩu không khớp.</div>';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $update = mysqli_query($db, "UPDATE users SET password='$hash' WHERE u_id='$user_id'");
        if ($update) {
            session_unset();
            session_destroy();
            echo '<script>alert("Đổi mật khẩu thành công! Vui lòng đăng nhập lại."); window.location="../login.php";</script>';
            exit();
        } else {
            $msg = '<div class="alert alert-danger">Có lỗi xảy ra, vui lòng thử lại.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #7209b7;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --error-color: #f72585;
            --success-color: #4cc9f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .change-password-container {
            max-width: 480px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            padding: 40px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .change-password-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 30px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 15px;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            padding-right: 50px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            background-color: white;
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            color: #adb5bd;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: var(--primary-color);
        }

        .btn-primary {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--accent-color);
        }

        .password-strength {
            margin-top: 10px;
            height: 6px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.4s, background-color 0.4s;
        }

        .alert {
            margin-top: 20px;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            display: none;
        }

        .alert-success {
            background-color: rgba(76, 201, 240, 0.1);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        .alert-error {
            background-color: rgba(247, 37, 133, 0.1);
            border: 1px solid var(--error-color);
            color: var(--error-color);
        }

        @media (max-width: 600px) {
            .change-password-container {
                padding: 30px 24px;
                margin: 20px;
            }
            
            .change-password-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="change-password-container">
        <div class="change-password-title">
            <i class="fas fa-lock"></i> Đổi mật khẩu
        </div>
        <div class="alert alert-success" id="successAlert" style="display: none;">
            Mật khẩu đã được thay đổi thành công!
        </div>
        
        <div class="alert alert-error" id="errorAlert" style="display: none;">
            Có lỗi xảy ra khi đổi mật khẩu. Vui lòng thử lại.
        </div>

        <form method="post" autocomplete="off" id="changePasswordForm">
            <div class="form-group">
                <label for="current_password">Mật khẩu hiện tại</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="current_password" name="current_password" required minlength="6">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('current_password', this)"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label for="new_password">Mật khẩu mới</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6" oninput="checkPasswordStrength()">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('new_password', this)"></i>
                </div>
                <div class="password-strength">
                    <div class="strength-bar" id="strengthBar"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu mới</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6" oninput="checkPasswordMatch()">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password', this)"></i>
                </div>
                <div class="alert alert-error" id="passwordMatchError" style="display: none; margin-top: 8px;">
                    Mật khẩu không khớp!
                </div>
            </div>
            
            <button type="submit" class="btn-primary">Đổi mật khẩu</button>
        </form>
        
        <div style="text-align: center;">
            <a href="../profile.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Quay lại tài khoản
            </a>
        </div>
    </div>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
    function togglePassword(id, el) {
        var input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
            el.querySelector('i').classList.remove('fa-eye');
            el.querySelector('i').classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            el.querySelector('i').classList.remove('fa-eye-slash');
            el.querySelector('i').classList.add('fa-eye');
        }
    }
    // Validate form client-side
    $(function(){
        $('#changePasswordForm').on('submit', function(e){
            var newPass = $('#new_password').val();
            var confirmPass = $('#confirm_password').val();
            if(newPass.length < 6) {
                alert('Mật khẩu mới phải từ 6 ký tự trở lên!');
                e.preventDefault();
                return false;
            }
            if(newPass !== confirmPass) {
                alert('Xác nhận mật khẩu không khớp!');
                e.preventDefault();
                return false;
            }
        });
    });
    </script>
</body>
</html>
