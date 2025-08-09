
<?php
header('Content-Type: application/json');
include "../connection/connect.php";

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'msg' => 'Email không hợp lệ!']);
    exit;
}

$userq = mysqli_query($db, "SELECT u_id, username, email FROM users WHERE email='".mysqli_real_escape_string($db, $email)."' LIMIT 1");
$user = mysqli_fetch_assoc($userq);
if (!$user) {
    echo json_encode(['success' => false, 'msg' => 'Email không tồn tại!']);
    exit;
}

// Tạo token reset
$token = bin2hex(random_bytes(32));
$expiry = date('Y-m-d H:i:s', strtotime('+30 minutes'));

mysqli_query($db, "UPDATE users SET reset_token='$token', reset_token_expiry='$expiry' WHERE u_id='{$user['u_id']}'");

// Link đổi mật khẩu cho localhost
$resetLink = 'http://localhost:8080/OnlineFood-PHP/phpmailer/reset_password.php?token=' . $token;

// Gửi email bằng PHPMailer

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'nguyenjason504@gmail.com'; 
    $mail->Password = 'pmzw mxai lxle xwlv';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    $mail->setFrom('nguyenjason504@gmail.com', 'FastFood');
    $mail->addAddress($user['email'], $user['username']);
    $mail->Subject = 'Yêu cầu đổi mật khẩu FastFood';
    $mail->isHTML(true);
    $mail->Body = '<h3>Xin chào ' . htmlspecialchars($user['username']) . ',</h3>' .
        '<p>Bạn vừa yêu cầu đổi mật khẩu. Vui lòng nhấn vào link bên dưới để đặt lại mật khẩu mới:</p>' .
        '<p><a href="' . $resetLink . '" style="background:#039be5;color:#fff;padding:10px 18px;border-radius:6px;text-decoration:none;font-weight:bold;">Đổi mật khẩu</a></p>' .
        '<p>Link này sẽ hết hạn sau 30 phút.</p>' .
        '<p>Nếu bạn không yêu cầu, hãy bỏ qua email này.</p>';
    $mail->send();
    echo json_encode(['success' => true, 'msg' => 'Đã gửi email đổi mật khẩu! Vui lòng kiểm tra hộp thư.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'msg' => 'Gửi email thất bại: ' . $mail->ErrorInfo . '<br>Lỗi chi tiết: ' . $e->getMessage()]);
}
