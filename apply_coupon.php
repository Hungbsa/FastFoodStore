
<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'connection/connect.php'; 

$couponCode = $_POST['coupon_code'] ?? '';
$totalAmount = floatval($_POST['total_amount'] ?? 0);


try {
    // 1. Kiểm tra coupon tồn tại
    $stmt = $db->prepare("SELECT * FROM coupons WHERE code = ?");
    $stmt->bind_param("s", $couponCode);
    $stmt->execute();
    $coupon = $stmt->get_result()->fetch_assoc();
    if (!$coupon) {
        throw new Exception("Mã giảm giá không tồn tại");
    }
    // 2. Kiểm tra trạng thái active
    if (!$coupon['active']) {
        throw new Exception("Mã giảm giá đã bị vô hiệu hóa");
    }
    // 3. Kiểm tra ngày hết hạn
    if ($coupon['expiry_date'] && strtotime($coupon['expiry_date']) < time()) {
        throw new Exception("Mã giảm giá đã hết hạn từ ngày ".date('d/m/Y', strtotime($coupon['expiry_date'])));
    }
    // 4. Kiểm tra số lần sử dụng (đặc biệt cho coupon LIMITED)
    if ($coupon['usage_limit'] > 0 && $coupon['times_used'] >= $coupon['usage_limit']) {
        throw new Exception("Mã ".$coupon['code']." chỉ có ".$coupon['usage_limit']." lượt sử dụng và đã hết");
    }
    // 5. Tính toán giảm giá
    $discount = 0;
    if ($coupon['discount_type'] == 'percentage') {
        $discount = $totalAmount * ($coupon['discount_value'] / 100);
        if ($coupon['max_discount'] > 0) {
            $discount = min($discount, $coupon['max_discount']);
        }
    } else {
        $discount = min($coupon['discount_value'], $totalAmount);
    }
    echo json_encode([
        'success' => true,
        'message' => 'Áp dụng mã '.$coupon['code'].' thành công',
        'discount_amount' => intval($discount),
    'discounted_total' => intval($totalAmount - $discount),
        'remaining_uses' => $coupon['usage_limit'] > 0 ? ($coupon['usage_limit'] - $coupon['times_used'] - 1) : 'VÔ HẠN'
    ]);
    $_SESSION['applied_coupon'] = [
        'code' => $couponCode,
        'discount' => $discount,
        'coupon_id' => $coupon['id']
    ];
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
