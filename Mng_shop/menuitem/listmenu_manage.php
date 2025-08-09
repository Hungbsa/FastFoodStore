<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../connection/connect.php';
// Kết nối PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=fastfood;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    $pdo = null;
}
// Xử lý cập nhật mức tồn kho tối thiểu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_min_btn']) && $pdo) {
    $id = intval($_POST['set_min_id']);
    $min_stock = intval($_POST['set_min_stock']);
    $pdo->prepare('UPDATE ingredients SET min_stock = ? WHERE ingredient_id = ?')->execute([$min_stock, $id]);
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
// Xử lý nhập kho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_stock']) && $pdo) {
    $ingredient_id = intval($_POST['ingredient_id']);
    $quantity = intval($_POST['quantity']);
    $unit_price = floatval($_POST['unit_price']);
    $notes = trim($_POST['notes'] ?? '');
    $user_id = $_SESSION['user_id'];
    $total_price = $quantity * $unit_price;
    // Lấy shop_name của shop hiện tại
    $shop = $pdo->query("SELECT * FROM shops WHERE id = $user_id")->fetch(PDO::FETCH_ASSOC);
    $shop_name = $shop ? $shop['shop_name'] : '';
    // Tạo phiếu nhập mới
    $pdo->prepare('INSERT INTO inventory_receipts (receipt_date, total_amount, notes, created_by, created_at) VALUES (NOW(), ?, ?, ?, NOW())')
        ->execute([$total_price, $notes, $user_id]);
    $receipt_id = $pdo->lastInsertId();
    // Thêm chi tiết nhập kho
    $pdo->prepare('INSERT INTO inventory_receipt_items (receipt_id, ingredient_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)')
        ->execute([$receipt_id, $ingredient_id, $quantity, $unit_price, $total_price]);
    // Cập nhật tồn kho
    $pdo->prepare('UPDATE ingredients SET current_quantity = current_quantity + ? WHERE ingredient_id = ?')->execute([$quantity, $ingredient_id]);
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
// Lấy danh sách nguyên liệu
$ingredients = $pdo ? $pdo->query('SELECT * FROM ingredients ORDER BY name ASC')->fetchAll(PDO::FETCH_ASSOC) : [];
// Lấy danh sách cảnh báo nguyên liệu sắp hết
$low_stock = $pdo ? $pdo->query('SELECT * FROM ingredients WHERE current_quantity <= min_stock ORDER BY current_quantity ASC')->fetchAll(PDO::FETCH_ASSOC) : [];
// Lấy lịch sử nhập kho, lấy owner_name của shop
$import_history = $pdo ? $pdo->query('SELECT r.*, s.shop_name FROM inventory_receipts r INNER JOIN shops s ON r.created_by = s.id ORDER BY r.receipt_date DESC LIMIT 20')->fetchAll(PDO::FETCH_ASSOC) : [];
// Lấy chi tiết nhập kho
$import_items = $pdo ? $pdo->query('SELECT i.*, ing.name as ingredient_name FROM inventory_receipt_items i LEFT JOIN ingredients ing ON i.ingredient_id = ing.ingredient_id WHERE i.receipt_id = (SELECT MAX(receipt_id) FROM inventory_receipts)')->fetchAll(PDO::FETCH_ASSOC) : [];
// Lấy danh sách món ăn
$dishes = $pdo ? $pdo->query('SELECT * FROM dishes ORDER BY title ASC')->fetchAll(PDO::FETCH_ASSOC) : [];
// Lấy công thức món ăn (đảm bảo dùng đúng trường khoá chính của dishes là d_id)
$formulas = $pdo ? $pdo->query('SELECT di.*, d.title as dish_title, ing.name as ingredient_name, ing.unit FROM dish_ingredients di LEFT JOIN dishes d ON di.dish_id = d.d_id LEFT JOIN ingredients ing ON di.ingredient_id = ing.ingredient_id')->fetchAll(PDO::FETCH_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Kho & Nguyên liệu</title>
    <link rel="icon" href="../../images/img/shopp.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="logo mb-4">
            <i class="fa-solid fa-store"></i> <span>Restaurant Manager</span>
        </div>
        <ul class="sidebar-menu">
            <li><a href="../index.php"><i class="fa fa-chart-line me-2"></i> <span>Tổng quan</span></a></li>
            <li><a href="../Shop_authMng/shop_manage.php"><i class="fa fa-store me-2"></i> <span>Quản lý Cửa hàng</span></a></li>
            <li><a href="../Shop_authMng/order_manage.php"><i class="fa fa-money-check-alt me-2"></i> <span>Xử lý Đơn hàng</span></a></li>
            <li><a href="../menuitem/Menu_manage.php"><i class="fa fa-utensils me-2"></i> <span>Quản lý Thực đơn</span></a></li>
            <li class="active"><a href="../menuitem/listmenu_manage.php"><i class="fa fa-cube me-2"></i> <span>Quản lý Kho</span></a></li>
            <li><a href="../Shop_authMng/staff_manage.php"><i class="fa fa-user-friends me-2"></i> <span>Quản lý Nhân sự</span></a></li>
            <li><a href="../Listexp/vourcher.php"><i class="fa fa-percent me-2"></i> <span>Khuyến mãi</span></a></li>
            <li><a href="../Listexp/analyst.php"><i class="fa fa-chart-pie me-2"></i> <span>Phân tích & Báo cáo</span></a></li>
            <li><a href="../Listexp/settings.php"><i class="fa fa-cog me-2"></i> <span>Cài đặt</span></a></li>
            <li><a href="../logout.php" class="text-danger"><i class="fa fa-sign-out-alt me-2"></i> <span>Đăng xuất</span></a></li>
        </ul>
    </div>
    <div class="main-content" id="mainContent">
            <div class="topbar">
                <span class="hamburger" id="hamburger"><i class="fa fa-bars"></i></span>
                <span class="title">Quản lý Kho & Nguyên liệu</span>
                <span class="user ms-auto">Xin chào, <?php echo $_SESSION['username'] ?? 'Quản lý'; ?>!</span>
            </div>
            
               
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card p-3 mb-4">
                      <div style="display:flex;justify-content:space-between;align-items:center;">
                        <h5>Nguyên liệu tồn kho</h5>
                        <button class="btn btn-success btn-sm" id="btnAddIngredient"><i class="fa fa-plus"></i> Thêm nguyên liệu</button>
                      </div>
                      <!-- Box thêm nguyên liệu -->
                      <div id="addIngredientBox" style="display:none;position:fixed;top:32px;right:32px;z-index:99999;background:#fff;border-radius:14px;box-shadow:0 2px 16px #0002;padding:24px 28px;min-width:320px;max-width:96vw;">
                        <div style="font-weight:600;font-size:1.15rem;margin-bottom:12px;color:#16a34a;">Thêm nguyên liệu mới</div>
                        <form method="post" id="formAddIngredient">
                          <div class="mb-2">
                            <label class="form-label">Tên nguyên liệu</label>
                            <input type="text" name="new_name" class="form-control" required>
                          </div>
                          <div class="mb-2">
                            <label class="form-label">Đơn vị</label>
                            <input type="text" name="new_unit" class="form-control" required>
                          </div>
                          <div class="mb-2">
                            <label class="form-label">Tồn kho ban đầu</label>
                            <input type="number" name="new_quantity" class="form-control" min="0" value="0" required>
                          </div>
                          <div class="mb-2">
                            <label class="form-label">Tối thiểu</label>
                            <input type="number" name="new_min_stock" class="form-control" min="0" value="0" required>
                          </div>
                          <div class="mb-2">
                            <label class="form-label">Đơn giá</label>
                            <input type="number" name="new_unit_price" class="form-control" min="0" value="0" required>
                          </div>
                          <div style="display:flex;gap:12px;justify-content:flex-end;">
                            <button type="button" class="btn btn-secondary" id="btnCancelAdd">Hủy</button>
                            <button type="submit" class="btn btn-success">Thêm</button>
                          </div>
                          <input type="hidden" name="add_ingredient" value="1">
                        </form>
                      </div>
                      <!-- Thông báo thêm thành công -->
                        <?php if (isset($_GET['add_success']) && $_GET['add_success'] == '1'): ?>
                        <div id="addSuccessAlert" style="position:fixed;top:18px;right:32px;z-index:99999;background:#16a34a;color:#fff;padding:14px 28px;border-radius:12px;font-weight:600;box-shadow:0 2px 16px #0002;">
                        <i class="fa fa-check-circle"></i> Thêm thành công!
                        </div>
                        <script>
                        setTimeout(function(){
                            window.location.href = window.location.pathname;
                        },1800);
                        </script>
                        <?php endif; ?>
                        <!-- Thông báo xoá thành công -->
                        <?php if (isset($_GET['delete_success']) && $_GET['delete_success'] == '1'): ?>
                        <div id="deleteSuccessAlert" style="position:fixed;top:18px;right:32px;z-index:99999;background:#dc3545;color:#fff;padding:14px 28px;border-radius:12px;font-weight:600;box-shadow:0 2px 16px #0002;">
                        <i class="fa fa-trash"></i> Xoá thành công!
                        </div>
                        <script>
                        setTimeout(function(){
                            window.location.href = window.location.pathname;
                        },1800);
                        </script>
                        <?php endif; ?>
                      <table class="table table-bordered table-hover">
                        <thead class="table-light">
                          <tr>
                            <th>Tên nguyên liệu</th>
                            <th>Tồn kho</th>
                            <th>Tối thiểu</th>
                            <th>Đơn vị</th>
                            <th>Đơn giá</th>
                            <th>Cảnh báo</th>
                            <th>Thiết lập tối thiểu</th>
                            <th>Xoá</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($ingredients as $ing): ?>
                            <tr<?php if ($ing['current_quantity'] <= $ing['min_stock']) echo ' style="background:#fff3cd"'; ?> >
                              <td><?= htmlspecialchars($ing['name']) ?></td>
                              <td><?= $ing['current_quantity'] ?></td>
                              <td><?= $ing['min_stock'] ?></td>
                              <td><?= htmlspecialchars($ing['unit']) ?></td>
                              <td><?= isset($ing['unit_price']) ? number_format($ing['unit_price'],0) : '0' ?></td>
                              <td><?php if ($ing['current_quantity'] <= $ing['min_stock']) echo '<span class="text-danger">Sắp hết!</span>'; ?></td>
                              <td>
                                <form method="post" style="display:inline-block;width:100px">
                                  <input type="hidden" name="set_min_id" value="<?= $ing['ingredient_id'] ?>">
                                  <input type="number" name="set_min_stock" value="<?= $ing['min_stock'] ?>" min="0" class="form-control form-control-sm" style="width:70px;display:inline-block">
                                  <button class="btn btn-sm btn-outline-primary" name="set_min_btn">Lưu</button>
                                </form>
                              </td>
                              <td>
                                <form method="post" onsubmit="return confirm('Bạn có chắc muốn xoá nguyên liệu này?');">
                                  <input type="hidden" name="delete_ingredient_id" value="<?= $ing['ingredient_id'] ?>">
                                  <button class="btn btn-sm btn-danger" name="delete_ingredient_btn"><i class="fa fa-trash"></i></button>
                                </form>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                      <?php
                      // Xử lý cập nhật mức tồn kho tối thiểu
                      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_min_btn']) && $pdo) {
                          $id = intval($_POST['set_min_id']);
                          $min_stock = intval($_POST['set_min_stock']);
                          $pdo->prepare('UPDATE ingredients SET min_stock = ? WHERE ingredient_id = ?')->execute([$min_stock, $id]);
                          echo '<div class="alert alert-success mt-2">Đã cập nhật mức tối thiểu!</div>';
                          echo '<script>setTimeout(function(){location.reload();}, 1200);</script>';
                      }
                      ?>
                    </div>
                    <div class="card p-3 mb-4">
                        <h5>Nhập kho nguyên liệu</h5>
                        <form method="post" class="row g-2">
                            <div class="col-md-3">
                                <select name="ingredient_id" class="form-select" required>
                                    <option value="">Chọn nguyên liệu...</option>
                                    <?php foreach ($ingredients as $ing): ?>
                                        <option value="<?= $ing['ingredient_id'] ?>"><?= htmlspecialchars($ing['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="quantity" class="form-control" placeholder="Số lượng" required min="1">
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="unit_price" class="form-control" placeholder="Đơn giá" required min="0">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="notes" class="form-control" placeholder="Ghi chú">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" name="import_stock">Nhập kho</button>
                            </div>
                        </form>
                        <?php
                        // Xử lý nhập kho
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_stock']) && $pdo) {
                            $ingredient_id = intval($_POST['ingredient_id']);
                            $quantity = intval($_POST['quantity']);
                            $unit_price = floatval($_POST['unit_price']);
                            $user_id = $_SESSION['user_id'];
                            $total_price = $quantity * $unit_price;
                            // Tạo phiếu nhập mới
                            $pdo->prepare('INSERT INTO inventory_receipts (receipt_date, total_amount, notes, created_by, created_at) VALUES (NOW(), ?, ?, ?, NOW())')
                                ->execute([$total_price, '', $user_id]);
                            $receipt_id = $pdo->lastInsertId();
                            // Thêm chi tiết nhập kho
                            $pdo->prepare('INSERT INTO inventory_receipt_items (receipt_id, ingredient_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)')
                                ->execute([$receipt_id, $ingredient_id, $quantity, $unit_price, $total_price]);
                            // Cập nhật tồn kho
                            $pdo->prepare('UPDATE ingredients SET current_quantity = current_quantity + ? WHERE ingredient_id = ?')->execute([$quantity, $ingredient_id]);
                            // Chuyển hướng về trang hiện tại để tránh nhập kho lặp lại
                            header('Location: ' . $_SERVER['REQUEST_URI']);
                            exit;
                        }
                        ?>
                    </div>
                    <div class="card p-3 mb-4">
                        <h5>Báo cáo nguyên liệu sắp hết</h5>
                        <ul class="list-group">
                            <?php foreach ($low_stock as $ing): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($ing['name']) ?>
                                    <span class="badge bg-danger">Còn <?= $ing['stock'] ?> <?= htmlspecialchars($ing['unit']) ?></span>
                                </li>
                            <?php endforeach; ?>
                            <?php if (empty($low_stock)): ?>
                                <li class="list-group-item">Không có nguyên liệu nào sắp hết.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3 mb-4">
                        <h5>Lịch sử nhập kho</h5>
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Cửa hàng nhập</th>
                                    <th>Tổng tiền</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($import_history as $imp): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($imp['receipt_date'])) ?></td>
                                    <td><?= htmlspecialchars($imp['shop_name']) ?></td>
                                    <td><?= number_format($imp['total_amount'], 0) ?> VNĐ</td>
                                    <td><?= htmlspecialchars($imp['notes']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <h6 class="mt-3">Chi tiết nhập kho gần nhất</h6>
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nguyên liệu</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($import_items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['ingredient_name']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><?= number_format($item['unit_price'], 0) ?> VNĐ</td>
                                    <td><?= number_format($item['total_price'], 0) ?> VNĐ</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card p-3 mb-4">
<h5>Quản lý công thức món ăn</h5>
<div class="mb-2">
    <input type="text" id="dishSearchInput" class="form-control" placeholder="Tìm món ăn..." oninput="filterDishes()">
</div>
<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Món ăn</th>
            <th>Quản lý công thức</th>
        </tr>
    </thead>
    <tbody id="dishesTableBody">
    <?php foreach ($dishes as $dish): ?>
        <tr data-title="<?= htmlspecialchars(strtolower($dish['title'])) ?>">
            <td><?= htmlspecialchars($dish['title']) ?></td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="showFormulaBox(<?= $dish['d_id'] ?>, '<?= htmlspecialchars(addslashes($dish['title'])) ?>')">Quản lý công thức</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<script>
function filterDishes() {
    var input = document.getElementById('dishSearchInput').value.toLowerCase();
    var rows = document.querySelectorAll('#dishesTableBody tr');
    rows.forEach(function(row) {
        var title = row.getAttribute('data-title');
        if (!input || title.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
<!-- Popup quản lý công thức rút gọn -->
<div id="formulaBox" style="display:none;position:fixed;top:40px;left:50%;transform:translateX(-50%);z-index:99999;background:#fff;border-radius:14px;box-shadow:0 2px 16px #0002;padding:18px 20px;min-width:340px;max-width:96vw;">
    <div style="font-weight:600;font-size:1.1rem;margin-bottom:10px;color:#2563eb;display:flex;align-items:center;justify-content:space-between;">
        <button type="button" class="btn btn-sm btn-light" id="btnPrevDish"><i class="fa fa-chevron-left"></i></button>
        <span id="formulaDishTitle"></span>
        <button type="button" class="btn btn-sm btn-light" id="btnNextDish"><i class="fa fa-chevron-right"></i></button>
        <button type="button" class="btn btn-sm btn-danger ms-2" onclick="closeFormulaBox()"><i class="fa fa-times"></i></button>
    </div>
    <form id="formulaEditForm" method="post" style="margin-bottom:10px;">
        <input type="hidden" name="edit_formula" value="1">
        <input type="hidden" name="dish_id" id="formulaDishId">
        <table class="table table-bordered mb-2">
            <thead>
                <tr>
                    <th>Nguyên liệu</th>
                    <th>Số lượng</th>
                    <th>Đơn vị</th>
                    <th>Sửa</th>
                    <th>Xoá</th>
                </tr>
            </thead>
            <tbody id="formulaTableBody">
                <!-- Nội dung sẽ được render bằng JS -->
            </tbody>
        </table>
    </form>
    <!-- Form thêm nguyên liệu vào công thức -->
    <form id="formulaAddForm" method="post" style="border-top:1px solid #eee;padding-top:10px;">
        <input type="hidden" name="add_formula" value="1">
        <input type="hidden" name="dish_id" id="formulaAddDishId">
        <div class="row g-2">
            <div class="col-7">
                <select name="ingredient_id" class="form-select form-select-sm" required>
                    <option value="">Chọn nguyên liệu...</option>
                    <?php foreach ($ingredients as $ing): ?>
                        <option value="<?= $ing['ingredient_id'] ?>"><?= htmlspecialchars($ing['name']) ?> (<?= htmlspecialchars($ing['unit']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-4">
                <input type="number" name="quantity" class="form-control form-control-sm" placeholder="Số lượng" min="0.01" step="0.01" required>
            </div>
            <div class="col-1">
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button>
            </div>
        </div>
    </form>
</div>
<script>
    var formulas = <?php echo json_encode($formulas); ?>;
    var dishes = <?php echo json_encode($dishes); ?>;
    var currentDishIndex = 0;
    function showFormulaBox(dishId, dishTitle) {
        // Tìm index của món ăn (dùng đúng d_id)
        currentDishIndex = dishes.findIndex(d => d.d_id == dishId);
        renderFormulaBox();
    }
    function renderFormulaBox() {
        var dish = dishes[currentDishIndex];
        document.getElementById('formulaBox').style.display = 'block';
        document.getElementById('formulaDishTitle').innerText = dish.title;
        // Đảm bảo input hidden dish_id luôn có giá trị đúng
        var formulaDishIdInput = document.getElementById('formulaDishId');
        if (formulaDishIdInput) formulaDishIdInput.value = dish.d_id;
        var formulaAddDishIdInput = document.getElementById('formulaAddDishId');
        if (formulaAddDishIdInput) formulaAddDishIdInput.value = dish.d_id;
        // Lọc công thức theo món (dùng đúng dish_id)
        var rows = '';
        formulas.filter(f => f.dish_id == dish.d_id).forEach(f => {
            rows += `<tr>
                <td>${f.ingredient_name}</td>
                <td><input type='number' name='quantity_${f.id}' value='${f.quantity}' min='0' class='form-control form-control-sm' style='width:80px;'></td>
                <td>${f.unit}</td>
                <td><button type='submit' name='save_formula_${f.id}' class='btn btn-sm btn-success'>Lưu</button></td>
                <td><button type='submit' name='delete_formula_${f.id}' class='btn btn-sm btn-danger' onclick="return confirm('Xoá nguyên liệu khỏi công thức?')"><i class='fa fa-trash'></i></button></td>
            </tr>`;
        });
        document.getElementById('formulaTableBody').innerHTML = rows;
    }
    function closeFormulaBox() {
        document.getElementById('formulaBox').style.display = 'none';
    }
    document.getElementById('btnPrevDish').onclick = function() {
        currentDishIndex = (currentDishIndex - 1 + dishes.length) % dishes.length;
        renderFormulaBox();
    };
    document.getElementById('btnNextDish').onclick = function() {
        currentDishIndex = (currentDishIndex + 1) % dishes.length;
        renderFormulaBox();
    };
</script>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Real-time Chat Bubble for Mng_shop -->
    <div id="ws-chat-bubble" style="position:fixed;bottom:36px;right:36px;z-index:99999;">
      <button id="ws-chat-toggle" style="width:64px;height:64px;border-radius:50%;background:#00b14f;color:#fff;border:none;box-shadow:0 2px 16px rgba(0,0,0,0.13);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.18s;padding:0;">
        <img src="../../images/img/iconss.png" alt="Chat" style="width:48px;height:48px;border-radius:50%;box-shadow:0 1px 6px rgba(0,0,0,0.10);background:#fff;">
      </button>
      <div id="ws-chat-box" style="display:none;position:absolute;bottom:80px;right:0;width:340px;background:#fff;border-radius:18px;box-shadow:0 2px 16px rgba(0,0,0,0.13);padding:16px;">
        <div style="font-weight:600;font-size:1.08rem;margin-bottom:8px;color:#00b14f;display:flex;justify-content:space-between;align-items:center;">
          Chăm sóc khách hàng
          <button id="ws-chat-close" style="background:none;border:none;font-size:1.3rem;color:#888;cursor:pointer;">&times;</button>
        </div>
        <div id="chat-messages" style="height:180px;overflow-y:auto;margin-bottom:12px;background:#f9f9f9;border-radius:8px;padding:8px;"></div>
        <div style="display:flex;gap:8px;">
          <input type="text" id="chat-input" placeholder="Nhập tin nhắn..." style="flex:1;padding:8px;border-radius:6px;border:1px solid #eee;">
          <button onclick="sendMessage()" style="padding:8px 18px;background:#00b14f;color:#fff;border:none;border-radius:6px;font-weight:600;">Gửi</button>
        </div>
      </div>
    </div>
    <style>
      #ws-chat-bubble {z-index:99999;}
      #ws-chat-toggle {transition:background 0.18s;}
      #ws-chat-toggle:hover {background:#ffe0b2;color:#00b14f;}
      #ws-chat-box {animation: wsChatFadeIn 0.22s;}
      @keyframes wsChatFadeIn {from{opacity:0;transform:scale(0.95);}to{opacity:1;transform:scale(1);}}
      .ws-chat-row {display:flex;align-items:flex-end;gap:10px;margin-bottom:8px;}
      .ws-chat-row.user {justify-content:flex-end;}
      .ws-chat-avatar {width:38px;height:38px;border-radius:50%;background:#00b14f;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;font-weight:bold;box-shadow:0 2px 8px rgba(0,0,0,0.07);}
      .ws-chat-avatar.mng_shop {
        background: #fff;
        color: #00b14f;
        border: 2.5px solid #00b14f;
        background-image: url('../../images/img/ql2.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        box-shadow: 0 2px 8px rgba(0,177,79,0.10);
      }
      .ws-chat-bubble {max-width:75%;padding:12px 18px;border-radius:16px;font-size:1.08rem;line-height:1.5;box-shadow:0 1px 6px rgba(0,0,0,0.04);word-break:break-word;}
      .ws-chat-row.user .ws-chat-bubble {background:#00b14f;color:#fff;border-bottom-right-radius:6px;}
      .ws-chat-row.mng_shop .ws-chat-bubble {background:#eee;color:#222;border-bottom-left-radius:6px;}
    </style>
    <script>
        var wsChatToggle = document.getElementById('ws-chat-toggle');
        var wsChatBox = document.getElementById('ws-chat-box');
        var wsChatClose = null;
        var chatMessagesDiv = document.getElementById('chat-messages');
        var chatHistory = [];
        function renderChatMessages() {
          chatMessagesDiv.innerHTML = '';
          chatHistory.forEach(function(msg) {
            var row = document.createElement('div');
            row.className = 'ws-chat-row ' + (msg.sender === 'mng_shop' ? 'mng_shop' : 'user');
            var avatar = document.createElement('div');
            avatar.className = 'ws-chat-avatar ' + (msg.sender === 'mng_shop' ? 'mng_shop' : '');
            if(msg.sender === 'mng_shop') {
              avatar.innerHTML = '';
            } else {
              avatar.innerHTML = '<i class="fa fa-user"></i>';
            }
            var bubble = document.createElement('div');
            bubble.className = 'ws-chat-bubble';
            bubble.textContent = msg.content;
            if(msg.sender === 'mng_shop') {
              row.appendChild(avatar);
              row.appendChild(bubble);
            } else {
              row.appendChild(bubble);
              row.appendChild(avatar);
            }
            chatMessagesDiv.appendChild(row);
          });
          chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
        }
        wsChatToggle.onclick = function() {
          wsChatBox.style.display = 'block';
          wsChatToggle.style.display = 'none';
          wsChatClose = document.getElementById('ws-chat-close');
          if(wsChatClose) wsChatClose.onclick = function(){
            wsChatBox.style.display = 'none';
            wsChatToggle.style.display = 'flex';
          };
          setTimeout(function(){document.getElementById('chat-input').focus();},200);
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
          var msg = document.getElementById('chat-input').value;
          if (!msg) return;
          var data = {sender: 'mng_shop', receiver: 'user', content: msg};
          ws.send(JSON.stringify(data));
          chatHistory.push(data);
          renderChatMessages();
          document.getElementById('chat-input').value = '';
        }
        // Thêm sự kiện Enter để gửi tin nhắn
        document.getElementById('chat-input').addEventListener('keydown', function(e) {
          if (e.key === 'Enter') sendMessage();
        });
    </script>
<!-- Real-time Chat Bubble for Mng_shop -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        // Sidebar toggle logic
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const hamburger = document.getElementById('hamburger');
        hamburger.addEventListener('click', function() {
            sidebar.classList.toggle('hide');
            mainContent.classList.toggle('full');
        });
        // Hiển thị box thêm nguyên liệu
        document.getElementById('btnAddIngredient').onclick = function() {
          document.getElementById('addIngredientBox').style.display = 'block';
        };
        document.getElementById('btnCancelAdd').onclick = function() {
          document.getElementById('addIngredientBox').style.display = 'none';
        };
    </script>
</body>
</html>
<?php
// Xử lý thêm nguyên liệu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_ingredient']) && $pdo) {
    $name = trim($_POST['new_name']);
    $unit = trim($_POST['new_unit']);
    $quantity = intval($_POST['new_quantity']);
    $min_stock = intval($_POST['new_min_stock']);
    $unit_price = floatval($_POST['new_unit_price']);
    if ($name && $unit) {
        $pdo->prepare('INSERT INTO ingredients (name, unit, current_quantity, min_stock, unit_price) VALUES (?, ?, ?, ?, ?)')->execute([$name, $unit, $quantity, $min_stock, $unit_price]);
        $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?') . '?add_success=1';
        echo "<script>location.href='$redirectUrl';</script>";
        exit;
    }
}
// Xử lý thêm nguyên liệu vào công thức món ăn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_formula']) && $pdo) {
    $dish_id = isset($_POST['dish_id']) ? intval($_POST['dish_id']) : 0;
    $ingredient_id = isset($_POST['ingredient_id']) ? intval($_POST['ingredient_id']) : 0;
    $quantity = isset($_POST['quantity']) ? floatval($_POST['quantity']) : 0;
    // Kiểm tra đầu vào
    if ($dish_id <= 0 || $ingredient_id <= 0 || $quantity <= 0) {
        echo '<div class="alert alert-danger" style="position:fixed;top:18px;left:50%;transform:translateX(-50%);z-index:99999;">Thiếu thông tin hoặc số lượng không hợp lệ!</div>';
        echo '<script>setTimeout(function(){window.location.href = window.location.pathname;}, 1800);</script>';
        exit;
    }
    // Kiểm tra dish_id có tồn tại
    $dishCheck = $pdo->prepare('SELECT d_id FROM dishes WHERE d_id = ?');
    $dishCheck->execute([$dish_id]);
    if ($dishCheck->rowCount() == 0) {
        echo '<div class="alert alert-danger" style="position:fixed;top:18px;left:50%;transform:translateX(-50%);z-index:99999;">Món ăn không tồn tại!</div>';
        echo '<script>setTimeout(function(){window.location.href = window.location.pathname;}, 1800);</script>';
        exit;
    }
    // Kiểm tra ingredient_id có tồn tại
    $ingCheck = $pdo->prepare('SELECT ingredient_id, current_quantity FROM ingredients WHERE ingredient_id = ?');
    $ingCheck->execute([$ingredient_id]);
    $ingredient = $ingCheck->fetch(PDO::FETCH_ASSOC);
    if (!$ingredient) {
        echo '<div class="alert alert-danger" style="position:fixed;top:18px;left:50%;transform:translateX(-50%);z-index:99999;">Nguyên liệu không tồn tại!</div>';
        echo '<script>setTimeout(function(){window.location.href = window.location.pathname;}, 1800);</script>';
        exit;
    }
    // Kiểm tra đủ tồn kho để trừ
    if ($ingredient['current_quantity'] < $quantity) {
        echo '<div class="alert alert-danger" style="position:fixed;top:18px;left:50%;transform:translateX(-50%);z-index:99999;">Không đủ tồn kho để thêm vào công thức!</div>';
        echo '<script>setTimeout(function(){window.location.href = window.location.pathname;}, 1800);</script>';
        exit;
    }
    // Kiểm tra trùng nguyên liệu trong công thức
    $exists = $pdo->prepare('SELECT COUNT(*) FROM dish_ingredients WHERE dish_id = ? AND ingredient_id = ?');
    $exists->execute([$dish_id, $ingredient_id]);
    if ($exists->fetchColumn() == 0) {
        // Trừ tồn kho ngay khi thêm công thức
        $pdo->prepare('UPDATE ingredients SET current_quantity = current_quantity - ? WHERE ingredient_id = ?')->execute([$quantity, $ingredient_id]);
        $pdo->prepare('INSERT INTO dish_ingredients (dish_id, ingredient_id, quantity) VALUES (?, ?, ?)')->execute([$dish_id, $ingredient_id, number_format($quantity,2,'.','')]);
        $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?') . '?formula_success=1';
        echo "<script>location.href='$redirectUrl';</script>";
        exit;
    } else {
        echo '<div class="alert alert-warning" style="position:fixed;top:18px;left:50%;transform:translateX(-50%);z-index:99999;">Nguyên liệu đã có trong công thức!</div>';
        echo '<script>setTimeout(function(){window.location.href = window.location.pathname;}, 1800);</script>';
        exit;
    }
}
// Xử lý xoá nguyên liệu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ingredient_btn']) && $pdo) {
    $del_id = intval($_POST['delete_ingredient_id']);
    $pdo->prepare('DELETE FROM ingredients WHERE ingredient_id = ?')->execute([$del_id]);
    $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?') . '?delete_success=1';
    echo "<script>location.href='$redirectUrl';</script>";
    exit;
}
?>