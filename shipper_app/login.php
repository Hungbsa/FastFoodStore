<?php
session_start();
require_once '../connection/connect.php';

$error = $success = '';

// Thêm PHPMailer
require_once '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $stmt = $db->prepare("SELECT * FROM shippers WHERE phone_number = ? AND is_active = 1");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $shipper = $result->fetch_assoc();
        // Xác minh mật khẩu đã băm
        if (password_verify($password, $shipper['password'])) {
            $_SESSION['shipper'] = $shipper;
            header('Location: dashboard.php?login=success');
            exit();
        } else {
            $error = "Sai số điện thoại hoặc mật khẩu!";
        }
    } else {
        $error = "Sai số điện thoại hoặc mật khẩu hoặc tài khoản chưa kích hoạt!";
    }
    $stmt->close();
}

// Xử lý đăng ký với OTP qua email
if (isset($_POST['register'])) {
    $full_name = $_POST['name'];
    $phone_number = $_POST['phone'];
    $password = $_POST['password'];
    $email = $_POST['email'] ?? '';
    $id_card_number = $_POST['id_card_number'] ?? '';
    $vehicle_type = $_POST['vehicle_type'] ?? '';
    $license_plate = $_POST['license_plate'] ?? '';

    // Kiểm tra trùng lặp số điện thoại hoặc CCCD
    $check = $db->prepare("SELECT shipper_id FROM shippers WHERE phone_number=? OR id_card_number=? LIMIT 1");

    if ($check) {
        $check->bind_param("ss", $phone_number, $id_card_number);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $error = "Số điện thoại hoặc CCCD đã được đăng ký. Vui lòng kiểm tra lại.";
        } else {
            // Tạo mã OTP
            $otp = rand(100000, 999999);
            $_SESSION['pending_shipper'] = [
                'full_name' => $full_name,
                'phone_number' => $phone_number,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'email' => $email,
                'id_card_number' => $id_card_number,
                'vehicle_type' => $vehicle_type,
                'license_plate' => $license_plate,
                'otp' => $otp
            ];
            // Gửi OTP qua email bằng PHPMailer
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'quizwhite212@gmail.com';
                $mail->Password   = 'uphx puek mztu dvsv';   
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('quizwhite212@gmail.com', 'ShipperPro');
                $mail->addAddress($email, $full_name);

                //Content
                $mail->isHTML(false);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Mã xác thực đăng ký ShipperPro';
                $mail->Body    = "Xin chào $full_name,\n\nMã xác thực đăng ký của bạn là: $otp\n\nVui lòng nhập mã này để hoàn tất đăng ký.";

                $mail->send();
                $success = "Đã gửi mã OTP đến email. Vui lòng kiểm tra và nhập mã xác thực.";
                $_SESSION['otp_sent'] = true;
            } catch (Exception $e) {
                $error = "Không thể gửi email xác thực. Lỗi: {$mail->ErrorInfo}";
            }
        }
        $check->close();
    } else {
        $error = "Lỗi hệ thống: Không thể kiểm tra trùng lặp. Vui lòng thử lại.";
    }
}


// Xử lý xác thực OTP
if (isset($_POST['verify_otp'])) {
    $input_otp = $_POST['otp'] ?? '';
    if (!empty($_SESSION['pending_shipper'])) {
        $pending = $_SESSION['pending_shipper'];
        if ($input_otp == $pending['otp']) {
            // Lưu vào DB
            $stmt = $db->prepare("INSERT INTO shippers (full_name, phone_number, password, email, id_card_number, vehicle_type, license_plate, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
            $stmt->bind_param("sssssss", $pending['full_name'], $pending['phone_number'], $pending['password'], $pending['email'], $pending['id_card_number'], $pending['vehicle_type'], $pending['license_plate']);
            if ($stmt->execute()) {
                $success = "Đăng ký thành công! Vui lòng đăng nhập.";
                unset($_SESSION['pending_shipper']);
                unset($_SESSION['otp_sent']);
            } else {
                $error = "Đăng ký thất bại! Vui lòng thử lại. (" . $db->error . ")";
            }
            $stmt->close();
        } else {
            $error = "Mã OTP không đúng. Vui lòng kiểm tra lại.";
        }
    } else {
        $error = "Không tìm thấy thông tin đăng ký. Vui lòng đăng ký lại.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>ShipperPro - Đăng nhập & Đăng ký</title>
    <link rel="icon" href="../images/img/shipper.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../shipper_app/assess/login.css">
</head>
<body>
<div class="main-header">
    <div class="circle-logo">
        <i class="fa-solid fa-truck"></i>
    </div>
    <h1>ShipperPro</h1>
    <p>Hệ thống quản lý giao hàng</p>
</div>
<div class="auth-wrapper">
    <div class="card position-relative">
        <div class="card-body position-relative">
            <?php if ($error): ?>
                <div class="custom-toast toast-error" role="alert"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="custom-toast toast-success" role="alert"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['otp_sent']) && !empty($_SESSION['pending_shipper'])): ?>
            <div id="otp-form" class="slide-form active">
                <div class="scrollable-form-content">
                    <form method="post" autocomplete="off">
                        <div class="form-title">Xác Thực Email</div>
                        <div class="form-group">
                            <label for="otp">Nhập mã OTP đã gửi đến email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                <input type="text" id="otp" name="otp" class="form-control" placeholder="Nhập mã OTP" required>
                            </div>
                        </div>
                        <button type="submit" name="verify_otp" class="btn btn-success">Xác Thực</button>
                        <div class="form-links mt-4">
                            <a href="?" class="toggle-link">Đăng ký lại</a>
                        </div>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <div id="login-form" class="slide-form active">
                <div class="scrollable-form-content">
                    <form method="post" autocomplete="off">
                        <div class="form-title">Đăng Nhập</div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" id="phone" name="phone" class="form-control" placeholder="Nhập số điện thoại" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                            </div>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary">Đăng Nhập</button>
                        <div class="form-links">
                            <a href="#" class="support-link">Quên mật khẩu?</a>
                            <a href="#" class="support-link">Cần hỗ trợ? Liên hệ CSKH</a>
                        </div>
                        <div class="form-links mt-4">
                            <a href="#" class="toggle-link" onclick="showRegister()">Chưa có tài khoản? Đăng ký ngay</a>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <div id="register-form" class="slide-form">
                <div class="scrollable-form-content">
                    <form method="post" autocomplete="off">
                        <div class="form-title">Đăng Ký Tài Khoản</div>
                        <div class="form-group">
                            <label for="reg_name">Họ tên</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" id="reg_name" name="name" class="form-control" placeholder="Nhập họ và tên" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reg_phone">Số điện thoại</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" id="reg_phone" name="phone" class="form-control" placeholder="Nhập số điện thoại" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reg_password">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" id="reg_password" name="password" class="form-control" placeholder="Tạo mật khẩu mạnh" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="reg_email">Email (Không bắt buộc)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                        <input type="email" id="reg_email" name="email" class="form-control" placeholder="Nhập địa chỉ email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="reg_id_card">Số CMND/CCCD</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                                        <input type="text" id="reg_id_card" name="id_card_number" class="form-control" placeholder="Nhập số CMND/CCCD">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="reg_vehicle">Loại phương tiện (Không bắt buộc)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-car"></i></span>
                                        <input type="text" id="reg_vehicle" name="vehicle_type" class="form-control" placeholder="Ví dụ: Xe máy, Ô tô, Xe đạp">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="reg_license">Biển số xe</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-rectangle-list"></i></span>
                                        <input type="text" id="reg_license" name="license_plate" class="form-control" placeholder="Nhập biển số xe">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="register" class="btn btn-primary">Đăng Ký Ngay</button>
                        <div class="form-links mt-4">
                            <a href="#" class="toggle-link" onclick="showLogin()">Đã có tài khoản? Đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function showRegister() {
        document.body.classList.add('register-active');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');

        loginForm.classList.remove('active');
        loginForm.classList.add('slide-out-left');

        setTimeout(function() {
            registerForm.classList.add('active');
            registerForm.classList.remove('slide-out-right');
            loginForm.classList.remove('slide-out-left'); // Reset for next transition
        }, 50); // Small delay to ensure slide-out starts
    }

    function showLogin() {
        document.body.classList.remove('register-active');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');

        registerForm.classList.remove('active');
        registerForm.classList.add('slide-out-right');

        setTimeout(function() {
            loginForm.classList.add('active');
            loginForm.classList.remove('slide-out-left');
            registerForm.classList.remove('slide-out-right'); // Reset for next transition
        }, 50); // Small delay to ensure slide-out starts
    }

    // Tự động ẩn toast sau 3.5s
    window.addEventListener('DOMContentLoaded', function() {
        var toast = document.querySelector('.custom-toast');
        if (toast) {
            setTimeout(function() {
                toast.style.display = 'none';
            }, 3500);
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>