<?php
session_start();
// Nếu chưa có group, chuyển về trang chủ
if (empty($_SESSION['group'])) {
    header('Location: ../dishes.php');
    exit;
}
$group = $_SESSION['group'];
$user = $_SESSION['username'] ?? 'Bạn';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nhóm của <?php echo htmlspecialchars($group['owner']); ?></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        .group-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            padding: 28px 32px 18px 32px;
            margin: 32px auto;
            max-width: 700px;
        }
        .group-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }
        .group-header i {
            font-size: 1.5rem;
            color: #1976d2;
        }
        .group-owner {
            font-weight: 600;
            color: #1976d2;
        }
        .group-code {
            background: #f5f5f5;
            border-radius: 8px;
            padding: 6px 14px;
            font-weight: 600;
            margin-left: 12px;
            color: #333;
        }
        .group-actions {
            margin-top: 18px;
            display: flex;
            gap: 12px;
        }
        .group-actions a {
            border-radius: 20px;
        }
    </style>
</head>
<body style="background: #f8f9fa;">
    <div class="group-box">
        <div class="group-header">
            <i class="fa fa-users"></i>
            <span class="group-owner">Nhóm: <?php echo htmlspecialchars($group['name']); ?> (của <?php echo htmlspecialchars($group['owner']); ?>)</span>
            <span class="group-code">Mã nhóm: <?php echo htmlspecialchars($group['code']); ?></span>
        </div>
        <div>
            <b>Thành viên:</b>
            <ul>
                <?php foreach ($group['members'] as $member): ?>
                    <li><?php echo htmlspecialchars($member); ?><?php if ($member == $group['owner']) echo ' (Trưởng nhóm)'; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="group-actions">
            <a href="../dishes.php?res_id=<?php echo isset($group['res_id']) ? intval($group['res_id']) : '' ?>" class="btn btn-outline-primary">Thêm món</a>
            <a href="#" class="btn btn-outline-danger" onclick="return confirmCancelGroup();">Huỷ nhóm</a>
        </div>
    </div>
    <script src="../js/jquery.min.js"></script>
    <script>
function confirmCancelGroup() {
    if (confirm('Bạn có chắc chắn muốn huỷ nhóm này?')) {
        window.location.href = 'cancel_group.php';
    }
    return false;
}
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</body>
</html>
