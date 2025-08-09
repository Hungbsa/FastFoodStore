<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

function sendOrderSuccessMail($to, $username, $orderInfo, $orderItems, $total, $address) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'nguyenjason504@gmail.com';
        $mail->Password   = 'pmzw mxai lxle xwlv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        //Recipients
        $mail->setFrom('nguyenjason504@gmail.com', 'FastFood');
        $mail->addAddress($to, $username);

        // Embed logo image (đường dẫn từ gốc project)
        $logoPath = __DIR__ . '/../images/img/iconss.png';
        if (file_exists($logoPath)) {
            $mail->addEmbeddedImage($logoPath, 'logoimg');
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Xác nhận đặt hàng thành công - FastFood';
        $mail->Body    = getOrderSuccessTemplate($username, $orderInfo, $orderItems, $total, $address, true);
        $mail->AltBody = 'Đơn hàng của bạn đã được đặt thành công.';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function getOrderSuccessTemplate($username, $orderInfo, $orderItems, $total, $address, $embedLogo = false) {
    $itemRows = '';
    foreach ($orderItems as $item) {
        $itemRows .= '<tr style="border-bottom:1px solid #eee;">'
            .'<td style="padding:8px 12px;">'.htmlspecialchars($item['title']).'</td>'
            .'<td style="padding:8px 12px; text-align:center;">'.$item['quantity'].'</td>'
            .'<td style="padding:8px 12px; text-align:right;">'.number_format($item['price'],0).'</td>'
            .'</tr>';
    }
    $logoImg = $embedLogo
        ? '<img src="cid:logoimg" alt="FastFood" style="width:80px;height:80px;border-radius:50%;box-shadow:0 2px 8px #ff9800;">'
        : '<img src="../img/iconss.png" alt="FastFood" style="width:80px;height:80px;border-radius:50%;box-shadow:0 2px 8px #ff9800;">';
    return '
    <div style="font-family:Segoe UI,Arial,sans-serif;max-width:600px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 2px 12px #eee;padding:24px;">
        <div style="text-align:center;margin-bottom:24px;">
            '.$logoImg.'
            <h2 style="color:#ff9800;margin:16px 0 0 0;">Cảm ơn bạn đã đặt hàng!</h2>
            <p style="color:#555;font-size:1.1rem;">Xin chào <b>'.htmlspecialchars($username).'</b>, đơn hàng của bạn đã được xác nhận.</p>
        </div>
        <div style="background:#fff3e0;padding:18px 16px;border-radius:8px;margin-bottom:18px;">
            <h3 style="color:#ff9800;font-size:1.15rem;margin:0 0 10px 0;">Thông tin đơn hàng</h3>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#ffe0b2;">
                        <th style="padding:8px 12px;text-align:left;">Món</th>
                        <th style="padding:8px 12px;text-align:center;">SL</th>
                        <th style="padding:8px 12px;text-align:right;">Giá</th>
                    </tr>
                </thead>
                <tbody>'.$itemRows.'</tbody>
            </table>
            <div style="margin-top:12px;text-align:right;font-size:1.1rem;font-weight:600;color:#ff6b6b;">
                Tổng cộng: '.number_format($total,0).' VNĐ
            </div>
        </div>
        <div style="margin-bottom:18px;">
            <span style="color:#888;font-size:1.05rem;">Địa chỉ giao hàng:</span><br>
            <span style="color:#222;font-size:1.1rem;font-weight:500;">'.htmlspecialchars($address).'</span>
        </div>
        <div style="text-align:center;margin-top:24px;">
            <a href="http://localhost:8080/OnlineFood-PHP/your_orders.php" style="display:inline-block;padding:12px 32px;background:#ff9800;color:#fff;border-radius:8px;font-weight:600;text-decoration:none;font-size:1.1rem;">Xem đơn hàng của bạn</a>
        </div>
        <div style="margin-top:32px;color:#aaa;font-size:0.98rem;text-align:center;">Nếu bạn có bất kỳ thắc mắc nào, hãy liên hệ với bộ phận CSKH của FastFood.<br>Hotline: 1900 2042 | Email: cskh@support.fastfood.vn</div>
    </div>';
}
