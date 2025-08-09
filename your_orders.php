<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if(empty($_SESSION['user_id']))  
{
    header('location:login.php');
}
else
{
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images\img\iconss.png">
    <title>Đơn Hàng - FastFood</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style type="text/css" rel="stylesheet">
        .indent-small {
        margin-left: 5px;
        }
        .form-group.internal {
        margin-bottom: 0;
        }
        .dialog-panel {
        margin: 10px;
        }
        .datepicker-dropdown {
        z-index: 200 !important;
        }
        .panel-body {
        background: #e5e5e5;
        /* Old browsers */
        background: -moz-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        /* FF3.6+ */
        background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, #e5e5e5), color-stop(100%, #ffffff));
        /* Chrome,Safari4+ */
        background: -webkit-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        /* Opera 12+ */
        background: -ms-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        /* IE10+ */
        background: radial-gradient(ellipse at center, #e5e5e5 0%, #ffffff 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e5e5e5', endColorstr='#ffffff', GradientType=1);
        font: 600 15px "Open Sans", Arial, sans-serif;
        }
        label.control-label {
        font-weight: 600;
        color: #777;
        }
        /* 
        table { 
            width: 750px; 
            border-collapse: collapse; 
            margin: auto;
            
            }

        /* Zebra striping */
        /* tr:nth-of-type(odd) { 
            background: #eee; 
            }

        th { 
            background: #404040; 
            color: white; 
            font-weight: bold; 
            
            }

        td, th { 
            padding: 10px; 
            border: 1px solid #ccc; 
            text-align: left; 
            font-size: 14px;
            
            } */ */


        @media 
        only screen and (max-width: 760px),
        (min-device-width: 768px) and (max-device-width: 1024px)  {

            /* table { 
                width: 100%; 
            }

            
            table, thead, tbody, th, td, tr { 
                display: block; 
            } */
            
            
            /* thead tr { 
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            
            tr { border: 1px solid #ccc; } */
            
            /* td { 
                
                border: none;
                border-bottom: 1px solid #eee; 
                position: relative;
                padding-left: 50%; 
            }

            td:before { 
                
                position: absolute;
            
                top: 6px;
                left: 6px;
                width: 45%; 
                padding-right: 10px; 
                white-space: nowrap;
                
                content: attr(data-column);

                color: #000;
                font-weight: bold;
            } */
        }
    </style>
</head>
<body>
    
      
<header id="header" class="header-scroll top-header headrom" style="background: linear-gradient(90deg, #ffb347 0%, #ff9800 100%); box-shadow: 0 2px 12px rgba(0,0,0,0.07);">
    <nav class="navbar navbar-dark" style="padding: 0.7rem 0;">
        <div class="container" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center;">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse" style="margin-right: 18px; background: #fff3e0; border: none; color: #ff9800; font-size: 1.5rem; padding: 6px 12px; border-radius: 6px;">&#9776;</button>
                <a class="navbar-brand" href="index.php" style="display: flex; align-items: center;">
                    <img class="img-rounded" src="images/img/newimg.jpg" style="width: 62px; height: 62px; margin-right: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); background: #fff; border-radius: 12px;">
                    <span style="font-weight: bold; font-size: 1.5rem; color: #fff; letter-spacing: 1px;">FastFood</span>
                </a>
            </div>
            <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
                <ul class="nav navbar-nav" style="gap: 8px; align-items: center;">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php" style="color: #fff; font-weight: 600; font-size: 1.1rem; padding: 8px 18px; border-radius: 20px; transition: background 0.2s; background: rgba(255,255,255,0.08);">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="Foods.php" style="color: #fff; font-weight: 600; font-size: 1.1rem; padding: 8px 18px; border-radius: 20px; transition: background 0.2s; background: rgba(255,255,255,0.08);">Foods</a>
                    </li>
                    <?php
                    if(empty($_SESSION["user_id"]))
                    {
                        echo '<li class="nav-item"><a href="login.php" class="nav-link active" style="color: #fff; font-weight: 600; font-size: 1.1rem; padding: 8px 18px; border-radius: 20px; background: #ff9800; margin-left: 6px;">Login</a></li>';
                        echo '<li class="nav-item"><a href="registration.php" class="nav-link active" style="color: #fff; font-weight: 600; font-size: 1.1rem; padding: 8px 18px; border-radius: 20px; background: #ff9800; margin-left: 6px;">Register</a></li>';
                    }
                    else
                    {
                        // Lấy tên user và ảnh đại diện từ database
                        $uid = $_SESSION["user_id"];
                        $userq = mysqli_query($db, "SELECT username, picture FROM users WHERE u_id='$uid' LIMIT 1");
                        $udata = mysqli_fetch_assoc($userq);
                        $username = $udata ? $udata['username'] : 'User';
                        $firstChar = strtoupper(substr($username,0,1));
                        $picture = ($udata && !empty($udata['picture'])) ? $udata['picture'] : '';
                        // Xử lý đường dẫn ảnh cho đúng thư mục
                        $avatarPath = $picture;
                        if ($avatarPath && strpos($avatarPath, 'images/') === 0) {
                            $avatarPath = $avatarPath;
                        }
                        echo '<li class="nav-item dropdown" style="margin-left: 12px;">';
                        echo '<a href="#" class="nav-link active dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center; gap: 8px; color: #222; background: #fff; border-radius: 22px; padding: 4px 16px 4px 6px; font-weight: 600; font-size: 1.08rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">';
                        if ($picture) {
                        echo '<span style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: #c2185b; color: #fff; border-radius: 50%; font-size: 1.3rem; font-weight: bold; overflow:hidden;"><img src="'.$avatarPath.'" alt="avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;"></span>';
                        }else {
                        echo '<span style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: #c2185b; color: #fff; border-radius: 50%; font-size: 1.3rem; font-weight: bold;">'.$firstChar.'</span>';
                        }
                        echo '<span style="max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'.$username.'</span>';
                        echo '<span style="font-size: 1.1rem; color: #888; margin-left: 4px;"></span>';
                        echo '</a>';
                        echo '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" style="min-width: 220px; padding: 0;">';
                        echo '<a class="dropdown-item" href="your_orders.php" style="display:flex;align-items:center;gap:10px;padding:12px 18px;font-size:1.08rem;"><span style="color:#4caf50;font-size:1.5rem;"><i class="fa fa-calendar"></i></span> Lịch sử đơn hàng</a>';
                        echo '<a class="dropdown-item" href="voucher_wallet.php" style="display:flex;align-items:center;gap:10px;padding:12px 18px;font-size:1.08rem;"><span style="color:#2196f3;font-size:1.5rem;"><i class="fa fa-ticket"></i></span> Ví Voucher</a>';
                        echo '<a class="dropdown-item" href="profile.php" style="display:flex;align-items:center;gap:10px;padding:12px 18px;font-size:1.08rem;"><span style="color:#ff9800;font-size:1.5rem;"><i class="fa fa-user"></i></span> Cập nhật tài khoản</a>';
                        echo '<div class="dropdown-divider" style="margin:0;"></div>';
                        echo '<a class="dropdown-item" href="logout.php" style="display:flex;align-items:center;gap:10px;padding:12px 18px;font-size:1.08rem;"><span style="color:#555;font-size:1.5rem;"><i class="fa fa-power-off"></i></span> Đăng xuất</a>';
                        echo '</div>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>



        <div class="page-wrapper">
            <div class="inner-page-hero bg-image" data-image-src="images/img/pimg.jpg">
                <div class="container"> </div>
            </div>
            <div class="result-show">
                <div class="container">
                    <div class="row">
                    </div>
                </div>
            </div>
                    <!-- section history -->
    <section class="order-history-page">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2>Lịch sử đơn hàng</h2>
                    <!-- Filter section -->

    <div class="filter-section" style="margin-bottom: 20px;">
        <form id="order-filter-form" method="get" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
            <div>
                <strong>Trạng thái</strong>
                <div style="margin-top: 5px;">
                    <select class="form-control" name="status" style="width: 120px;">
                        <option value="">Tất cả</option>
                        <option value="NULL">Đang xử lý</option>
                        <option value="in process">Đang giao</option>
                        <option value="closed">Hoàn thành</option>
                        <option value="rejected">Đã hủy</option>
                    </select>
                </div>
            </div>
            <div class="date-input-container">
                <strong>Từ ngày</strong>
                <div style="margin-top: 5px; position: relative;">
                    <input type="text" class="form-control date-input" name="from" value="<?php echo isset($_GET['from']) ? htmlspecialchars($_GET['from']) : date('d-m-Y', strtotime('-7 days')); ?>" style="width: 120px;" readonly>
                    <div class="date-picker">
                        <div class="date-picker-header">
                            <button class="prev-year">&lt;&lt;</button>
                            <button class="prev-month">&lt;</button>
                            <span class="current-month-year">Tháng/Năm</span>
                            <button class="next-month">&gt;</button>
                            <button class="next-year">&gt;&gt;</button>
                        </div>
                        <div class="date-picker-weekdays">
                            <div>CN</div>
                            <div>T2</div>
                            <div>T3</div>
                            <div>T4</div>
                            <div>T5</div>
                            <div>T6</div>
                            <div>T7</div>
                        </div>
                        <div class="date-picker-days"></div>
                    </div>
                </div>
            </div>
            <div class="date-input-container">
                <strong>Đến ngày</strong>
                <div style="margin-top: 5px; position: relative;">
                    <input type="text" class="form-control date-input" name="to" value="<?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : date('d-m-Y'); ?>" style="width: 120px;" readonly>
                    <div class="date-picker">
                        <div class="date-picker-header">
                            <button class="prev-year">&lt;&lt;</button>
                            <button class="prev-month">&lt;</button>
                            <span class="current-month-year">Tháng/Năm</span>
                            <button class="next-month">&gt;</button>
                            <button class="next-year">&gt;&gt;</button>
                        </div>
                        <div class="date-picker-weekdays">
                            <div>CN</div>
                            <div>T2</div>
                            <div>T3</div>
                            <div>T4</div>
                            <div>T5</div>
                            <div>T6</div>
                            <div>T7</div>
                        </div>
                        <div class="date-picker-days"></div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" style="margin-top: 22px;" type="submit">Tìm kiếm</button>
        </form>
    </div>

    
                 <!-- Filter section -->
                <div class="bg-gray">
                    <div class="order-table">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                                <thead style="background: #f8f9fa; color: #333;">
                                    <tr>
                                        <th style="width: 50px;">STT</th>
                                        <th>Tên món</th>
                                        <th style="width: 100px;">Số lượng</th>
                                        <th style="width: 100px;">Giá tiền</th>
                                        <th style="width: 150px;">Thời gian đặt</th>
                                        <th style="width: 120px;">Trạng thái</th>
                                        <th style="width: 100px;">Chi tiết</th>
                                    </tr>
                                </thead>
                            <tbody>
                                <?php 
                                // Xử lý filter
                                $where = "u_id='".$_SESSION['user_id']."'";
                                if(isset($_GET['status']) && $_GET['status'] !== "") {
                                $status = mysqli_real_escape_string($db, $_GET['status']);
                                if($status === 'NULL') {
                                    $where .= " AND (status='' OR status IS NULL)";
                                } else {
                                    $where .= " AND status='".$status."'";
                                }}
                                if(isset($_GET['from']) && $_GET['from'] != "") {
                                    $from = DateTime::createFromFormat('d-m-Y', $_GET['from']);
                                    if($from) $where .= " AND date >= '".$from->format('Y-m-d')." 00:00:00'";
                                }
                                if(isset($_GET['to']) && $_GET['to'] != "") {
                                    $to = DateTime::createFromFormat('d-m-Y', $_GET['to']);
                                    if($to) $where .= " AND date <= '".$to->format('Y-m-d')." 23:59:59'";
                                }
                                $query_res = mysqli_query($db, "select * from users_orders where $where ORDER BY date DESC");
                                if(!mysqli_num_rows($query_res) > 0) {
                                    echo '<tr><td colspan="7" style="text-align: center;">Bạn chưa có đơn hàng nào</td></tr>';
                                } else {
                                    $stt = 1;
                                    while($row = mysqli_fetch_array($query_res)) {
                                ?>
                                <tr>    
                                    <td><?php echo $stt++; ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td><?php echo number_format($row['price'], 0, '.', ','); ?> VNĐ</td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['date'])); ?></td>
                                    <td>
                                        <?php 
                                        $status = $row['status'];
                                        if($status == "" || $status == "NULL") {
                                            echo '<span class="label label-default">Đang xử lý</span>';
                                        } elseif($status == "in process") {
                                            echo '<span class="label label-warning">Đang giao</span>';
                                        } elseif($status == "closed") {
                                            echo '<span class="label label-success">Hoàn thành</span>';
                                        } elseif($status == "rejected") {
                                            echo '<span class="label label-danger">Đã hủy</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-default btn-xs order-detail-btn" 
                                            data-order-id="<?php echo $row['o_id']; ?>"
                                            data-title="<?php echo htmlspecialchars($row['title']); ?>"
                                            data-quantity="<?php echo $row['quantity']; ?>"
                                            data-price="<?php echo number_format($row['price'], 0, '.', ','); ?> VNĐ"
                                            data-date="<?php echo date('d/m/Y H:i', strtotime($row['date'])); ?>"
                                            data-status="<?php echo $row['status']; ?>"
                                            >Chi tiết đơn hàng</button>
                                        <?php if($row['status'] != 'rejected' && $row['status'] != 'closed') { ?>
                                            <button class="btn btn-danger btn-xs cancel-order-btn" data-order-id="<?php echo $row['o_id']; ?>">Huỷ đơn</button>
                                        <?php } elseif($row['status'] == 'rejected') { ?>
                                            <button class="btn btn-success btn-xs reorder-btn" data-order-id="<?php echo $row['o_id']; ?>">Đặt lại</button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } } ?>
                            </tbody>
                        </table>
                        <!-- Modal chi tiết đơn hàng đặt ngoài vòng lặp -->
                        <div id="orderDetailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content" style="border-radius: 12px;">
                              <div class="modal-header" style="background: #ff9800; color: #fff; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                <h4 class="modal-title" id="orderDetailModalLabel" style="font-weight: bold;">Chi tiết đơn hàng</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; font-size: 2rem; opacity: 1;">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body" id="order-detail-content" style="background: #fff;">
                                <!-- Nội dung chi tiết đơn hàng sẽ được render ở đây bằng JS -->
                                <div id="order-detail-extra"></div>
                              </div>
                              <div class="modal-footer" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                              </div>
                            </div>
                          </div>
                        </div>

                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <style>
        #orderDetailModal .modal-content {
        border-radius: 12px;
        box-shadow: 0 4px 32px rgba(0,0,0,0.12);
        }
        #orderDetailModal .modal-header {
        background: linear-gradient(90deg, #ffb347 0%, #ff9800 100%);
        color: #fff;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        }
        #orderDetailModal .modal-title {
        font-size: 1.4rem;
        font-weight: bold;
        }
        #orderDetailModal .modal-body {
        padding: 24px 18px 12px 18px;
        }
        #orderDetailModal .order-detail-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
        }
        #orderDetailModal .order-detail-table th, #orderDetailModal .order-detail-table td {
        padding: 8px 10px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 15px;
        }
        #orderDetailModal .order-detail-table th {
        background: #fff3e0;
        color: #ff9800;
        font-weight: 600;
        }
        #orderDetailModal .order-detail-table td {
        color: #333;
        }
        #orderDetailModal .order-summary {
        font-size: 1.1rem;
        font-weight: 600;
        color: #ff9800;
        text-align: right;
        margin-top: 10px;
        }
        #orderDetailModal .order-detail-label {
        color: #888;
        font-size: 14px;
        }
        #orderDetailModal .order-detail-value {
        color: #222;
        font-size: 15px;
        font-weight: 500;
        }
    </style>

    <style>
        .label {
            display: inline-block;
            padding: 3px 6px;
            font-size: 12px;
            font-weight: 500;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 3px;
        }
        .label-default { background-color: #777; color: #fff; }
        .label-warning { background-color: #f0ad4e; color: #fff; }
        .label-success { background-color: #5cb85c; color: #fff; }
        .label-danger { background-color: #d9534f; color: #fff; }
    </style>

    <style>
            .date-input-container {
                position: relative;
            }

            .date-picker {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 240px;
                background: white;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                z-index: 1000;
                padding: 10px;
            }

            .date-input-container.active .date-picker {
                display: block;
            }

            .date-picker-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }

            .date-picker-header button {
                background: none;
                border: none;
                cursor: pointer;
                font-size: 16px;
                padding: 0 5px;
            }

            .date-picker-weekdays {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                text-align: center;
                font-weight: bold;
                margin-bottom: 5px;
            }

            .date-picker-days {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 5px;
            }

            .date-picker-days div {
                padding: 5px;
                text-align: center;
                cursor: pointer;
                border-radius: 3px;
            }

            .date-picker-days div:hover {
                background-color: #f0f0f0;
            }

            .date-picker-days .other-month {
                color: #ccc;
            }

            .date-picker-days .selected {
                background-color: #337ab7;
                color: white;
            }

            .current-month-year {
                font-weight: bold;
                margin: 0 10px;
            }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInputs = document.querySelectorAll('.date-input');
            const dateContainers = document.querySelectorAll('.date-input-container');
            
            // Initialize date pickers
            dateContainers.forEach(container => {
                const picker = container.querySelector('.date-picker');
                const monthYearDisplay = picker.querySelector('.current-month-year');
                const daysContainer = picker.querySelector('.date-picker-days');
                const prevMonthBtn = picker.querySelector('.prev-month');
                const nextMonthBtn = picker.querySelector('.next-month');
                const prevYearBtn = picker.querySelector('.prev-year');
                const nextYearBtn = picker.querySelector('.next-year');
                const input = container.querySelector('.date-input');
                
                // Parse initial date from input
                let currentDate = parseInputDate(input.value);
                
                // Render calendar
                function renderCalendar() {
                    const year = currentDate.getFullYear();
                    const month = currentDate.getMonth();
                    
                    // Update month/year display
                    monthYearDisplay.textContent = new Date(year, month).toLocaleDateString('en-US', {
                        month: 'long',
                        year: 'numeric'
                    });
                    
                    // Get first and last day of month
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);
                    
                    // Get days from previous month to show
                    const prevMonthLastDay = new Date(year, month, 0).getDate();
                    const firstDayOfWeek = firstDay.getDay();
                    
                    // Get days from next month to show
                    const totalDays = lastDay.getDate();
                    const lastDayOfWeek = lastDay.getDay();
                    const nextMonthDays = 6 - lastDayOfWeek;
                    
                    // Clear days container
                    daysContainer.innerHTML = '';
                    
                    // Add days from previous month
                    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
                        const dayElement = document.createElement('div');
                        dayElement.classList.add('other-month');
                        dayElement.textContent = prevMonthLastDay - i;
                        daysContainer.appendChild(dayElement);
                    }
                    
                    // Add days of current month
                    for (let i = 1; i <= totalDays; i++) {
                        const dayElement = document.createElement('div');
                        dayElement.textContent = i;
                        
                        // Check if this day is selected
                        const inputDate = parseInputDate(input.value);
                        if (inputDate && inputDate.getDate() === i && 
                            inputDate.getMonth() === month && 
                            inputDate.getFullYear() === year) {
                            dayElement.classList.add('selected');
                        }
                        
                        dayElement.addEventListener('click', function() {
                            // Remove selected class from all days
                            daysContainer.querySelectorAll('div').forEach(day => {
                                day.classList.remove('selected');
                            });
                            
                            // Add selected class to clicked day
                            this.classList.add('selected');
                            
                            // Update input value
                            const selectedDate = new Date(year, month, parseInt(this.textContent));
                            input.value = formatDate(selectedDate);
                            
                            // Close picker
                            container.classList.remove('active');
                        });
                        
                        daysContainer.appendChild(dayElement);
                    }
                    
                    // Add days from next month
                    for (let i = 1; i <= nextMonthDays; i++) {
                        const dayElement = document.createElement('div');
                        dayElement.classList.add('other-month');
                        dayElement.textContent = i;
                        daysContainer.appendChild(dayElement);
                    }
                }
                
                // Navigation handlers
                prevMonthBtn.addEventListener('click', function() {
                    currentDate.setMonth(currentDate.getMonth() - 1);
                    renderCalendar();
                });
                
                nextMonthBtn.addEventListener('click', function() {
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    renderCalendar();
                });
                
                prevYearBtn.addEventListener('click', function() {
                    currentDate.setFullYear(currentDate.getFullYear() - 1);
                    renderCalendar();
                });
                
                nextYearBtn.addEventListener('click', function() {
                    currentDate.setFullYear(currentDate.getFullYear() + 1);
                    renderCalendar();
                });
                
                // Initial render
                renderCalendar();
            });
            
            // Helper functions
            function parseInputDate(dateString) {
                const parts = dateString.split('-');
                if (parts.length === 3) {
                    return new Date(parts[2], parts[1] - 1, parts[0]);
                }
                return new Date(); // Fallback to current date
            }
            
            function formatDate(date) {
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            }
            
            // Toggle date pickers
            dateInputs.forEach(input => {
                input.addEventListener('click', function() {
                    // Close all other pickers
                    dateContainers.forEach(container => {
                        container.classList.remove('active');
                    });
                    
                    // Open this picker
                    this.closest('.date-input-container').classList.add('active');
                });
            });
            
            // Close picker when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.date-input-container')) {
                    dateContainers.forEach(container => {
                        container.classList.remove('active');
                    });
                }
            });
            
            // Prevent closing when clicking inside picker
            document.querySelectorAll('.date-picker').forEach(picker => {
                picker.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
                                <!-- section history -->                                                    


        <!-- Topcontrol Navigation Start -->
<div class="topcontrol-nav" id="topcontrolNav">
  <div class="topcontrol-group" tabindex="0">
    <button class="topcontrol-main-btn"><i class="fa fa-bars"></i></button>
    <div class="topcontrol-popup">
      <button class="topcontrol-btn" title="Lên đầu trang" onclick="window.scrollTo({top:0,behavior:'smooth'})"><i class="fa fa-arrow-up"></i></button>
      <button class="topcontrol-btn" title="Đăng ký tài xế" onclick="window.location.href='driver_register.php'"><i class="fa fa-motorcycle"></i></button>
      <button class="topcontrol-btn" title="Startup cửa hàng" onclick="window.location.href='startup.php'"><i class="fa fa-rocket"></i></button>
      <button class="topcontrol-btn" title="Trang admin" onclick="window.location.href='admin/index.php'"><i class="fa fa-user-secret"></i></button>
      <button class="topcontrol-btn" title="Cửa hàng yêu thích" onclick="handleWorthyNav()"><i class="fa fa-heart"></i></button>
    </div>
  </div>
  <style>
    .topcontrol-nav {
      position: fixed;
      top: 38%;
      left: 18px;
      z-index: 9999;
      display: flex;
      align-items: center;
      background: transparent;
      box-shadow: none;
      padding: 0;
    }
    .topcontrol-group {
      position: relative;
      outline: none;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .topcontrol-main-btn {
      background: #fff;
      border: none;
      outline: none;
      border-radius: 50%;
      width: 54px;
      height: 54px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: #ff9800;
      box-shadow: 0 2px 16px rgba(0,0,0,0.13);
      cursor: pointer;
      transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.18s;
    }
    .topcontrol-main-btn:hover {
      background: #ffe0b2;
      color: #00b14f;
      box-shadow: 0 4px 18px rgba(0,177,79,0.13);
      transform: scale(1.08) translateY(-2px);
    }
    .topcontrol-popup {
      position: absolute;
      left: 70px;
      top: 50%;
      transform: translateY(-50%) scale(0.95);
      background: rgba(255,255,255,0.98);
      border-radius: 18px;
      box-shadow: 0 4px 32px rgba(0,0,0,0.13);
      padding: 18px 16px;
      display: flex;
      flex-direction: column;
      gap: 16px;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.18s, transform 0.18s;
      min-width: 60px;
    }
    .topcontrol-group:hover .topcontrol-popup,
    .topcontrol-group:focus-within .topcontrol-popup {
      opacity: 1;
      pointer-events: auto;
      transform: translateY(-50%) scale(1);
    }
    .topcontrol-btn {
      background: #fff;
      border: none;
      outline: none;
      border-radius: 50%;
      width: 44px;
      height: 44px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.45rem;
      color: #ff9800;
      box-shadow: 0 1px 6px rgba(0,0,0,0.07);
      margin: 0;
      cursor: pointer;
      transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.18s;
    }
    .topcontrol-btn:hover {
      background: #ffe0b2;
      color: #00b14f;
      box-shadow: 0 4px 18px rgba(0,177,79,0.13);
      transform: scale(1.12) translateY(-2px);
    }
    .topcontrol-btn:active {
      transform: scale(0.97);
    }
    .topcontrol-btn[title]:hover:after {
      content: attr(title);
      position: absolute;
      left: 120%;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(34,34,34,0.97);
      color: #fff;
      padding: 7px 16px;
      border-radius: 8px;
      font-size: 1.02rem;
      font-weight: 500;
      white-space: nowrap;
      box-shadow: 0 2px 12px rgba(0,0,0,0.13);
      opacity: 1;
      pointer-events: none;
      transition: opacity 0.18s, left 0.18s;
      z-index: 10000;
    }
    .topcontrol-btn[title]:hover:before {
      content: '';
      position: absolute;
      left: 114%;
      top: 50%;
      transform: translateY(-50%);
      border-width: 7px;
      border-style: solid;
      border-color: transparent rgba(34,34,34,0.97) transparent transparent;
      z-index: 10001;
    }
    @media (max-width: 900px) {
      .topcontrol-nav {left: 4px;}
      .topcontrol-main-btn {width: 44px;height: 44px;font-size:1.3rem;}
      .topcontrol-btn {width: 36px;height: 36px;font-size:1.08rem;}
      .topcontrol-popup {left: 54px; padding: 10px 8px;}
      .topcontrol-btn[title]:hover:after {font-size:0.95rem;}
    }
    @media (max-width: 600px) {
      .topcontrol-nav {top: unset; bottom: 18px; left: 8px;}
      .topcontrol-group {flex-direction: row;}
      .topcontrol-main-btn {width: 38px;height: 38px;font-size:1rem;}
      .topcontrol-popup {left: 0; top: unset; bottom: 54px; transform: translateY(0) scale(0.95); flex-direction: row; gap: 10px;}
      .topcontrol-group:hover .topcontrol-popup,
      .topcontrol-group:focus-within .topcontrol-popup {transform: translateY(0) scale(1);}
      .topcontrol-btn {width: 32px;height: 32px;font-size:1rem;}
      .topcontrol-btn[title]:hover:after {left: 50%; top: -38px; transform: translateX(-50%);}
      .topcontrol-btn[title]:hover:before {left: 50%; top: -10px; transform: translateX(-50%) rotate(90deg); border-width: 7px; border-color: transparent transparent rgba(34,34,34,0.97) transparent;}
    }
  </style>
  <script>
    function handleWorthyNav() {
      <?php if(empty($_SESSION['user_id'])): ?>
        window.location.href = 'login.php';
      <?php else: ?>
        window.location.href = 'worthy.php';
      <?php endif; ?>
    }
  </script>
</div>

<!-- Topcontrol Navigation End -->

                                
            <footer class="footer">
<!-- Chatbot Widget Start -->
  <div id="chatbot-widget">
    <div id="chatbot-header">
      <span style="display:flex;align-items:center;gap:8px;"><img src="images/img/iconss.png" alt="Bot" style="width:32px;height:32px;border-radius:50%;margin-right:6px;box-shadow:0 1px 4px rgba(0,0,0,0.10);border:2px solid #fff;background:#fff;"> Chat hỗ trợ</span>
      <span style="display:flex;align-items:center;gap:0;">
        <span id="chatbot-minimize" title="Thu nhỏ" style="cursor:pointer;font-size:1.3rem;padding:0 8px;">&#8211;</span>
        <span id="chatbot-close" title="Đóng" style="cursor:pointer;font-size:1.3rem;padding:0 8px;">&times;</span>
      </span>
    </div>
    <div id="chatbot-messages"></div>
    <div id="chatbot-input-area">
      <input type="text" id="chatbot-input" placeholder="Nhập câu hỏi..." autocomplete="off"/>
      <button id="chatbot-send"><i class="fa fa-paper-plane"></i></button>
    </div>
  </div>
  <button id="chatbot-toggle"><i class="fa fa-comments"></i></button>
  <style>
    #chatbot-widget {
      position: fixed; bottom: 36px; right: 36px; width: 400px; background: #fff; border-radius: 20px;
      box-shadow: 0 6px 40px rgba(0,0,0,0.16); display: none; flex-direction: column; z-index: 99999;
      min-height: 480px; max-height: 80vh; overflow: hidden;
    }
    .chatbot-suggestions-inline {
      display: inline-flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-left: 12px;
      vertical-align: middle;
    }
    .chatbot-suggestion {
      background: #fff3e0;
      color: #ff9800;
      padding: 8px 14px;
      border-radius: 16px;
      font-size: 0.98rem;
      cursor: pointer;
      font-weight: 500;
      border: 1.5px solid #ff9800;
      transition: background 0.2s, color 0.2s;
      display: inline-block;
      margin-bottom: 0;
    }
    .chatbot-suggestion:hover {
      background: #ff9800;
      color: #fff;
    }
    #chatbot-header { background: #ff9800; color: #fff; padding: 16px 20px; border-radius: 20px 20px 0 0; font-weight: bold; display: flex; justify-content: space-between; align-items: center; font-size: 1.18rem;}
    #chatbot-messages { padding: 18px; height: 340px; overflow-y: auto; background: #f9f9f9; display: flex; flex-direction: column; gap: 10px; }
    #chatbot-input-area { display: flex; border-top: 1px solid #eee; background: #fff; }
    #chatbot-input { flex: 1; border: none; padding: 14px; border-radius: 0 0 0 20px; outline: none; font-size: 1.08rem; }
    #chatbot-send { background: #ff9800; color: #fff; border: none; padding: 0 24px; border-radius: 0 0 20px 0; cursor: pointer; font-size: 1.25rem; }
    #chatbot-toggle { position: fixed; bottom: 36px; right: 36px; background: #ff9800; color: #fff; border: none; border-radius: 50%; width: 64px; height: 64px; font-size: 2.2rem; box-shadow: 0 2px 16px rgba(0,0,0,0.13); cursor: pointer; z-index: 99999; }
    .chatbot-msg-row { display: flex; align-items: flex-end; gap: 10px; }
    .chatbot-msg-row.user { justify-content: flex-end; }
    .chatbot-avatar { width: 38px; height: 38px; border-radius: 50%; background: #ff9800; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.3rem; font-weight: bold; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
    .chatbot-avatar.bot {
      background: #fff;
      color: #ff9800;
      border: 2.5px solid #ff9800;
      background-image: url('images/img/iconss.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      box-shadow: 0 2px 8px rgba(255,152,0,0.10);
    }
    .chatbot-bubble { max-width: 75%; padding: 12px 18px; border-radius: 16px; font-size: 1.08rem; line-height: 1.5; box-shadow: 0 1px 6px rgba(0,0,0,0.04); word-break: break-word; }
    .chatbot-msg-row.user .chatbot-bubble { background: #ff9800; color: #fff; border-bottom-right-radius: 6px; }
    .chatbot-msg-row.bot .chatbot-bubble { background: #eee; color: #222; border-bottom-left-radius: 6px; }
    .chatbot-loading { display: inline-block; width: 32px; height: 18px; }
    .chatbot-loading span { display: inline-block; width: 8px; height: 8px; margin: 0 2px; background: #ff9800; border-radius: 50%; animation: chatbot-bounce 1.1s infinite alternate; }
    .chatbot-loading span:nth-child(2) { animation-delay: 0.2s; }
    .chatbot-loading span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes chatbot-bounce { 0% { transform: translateY(0); } 100% { transform: translateY(-8px); } }
    @media (max-width: 600px) {
      #chatbot-widget { right: 2vw; left: 2vw; width: 96vw; min-width: unset; min-height: 320px; }
      #chatbot-toggle { right: 2vw; bottom: 2vw; width: 54px; height: 54px; font-size: 1.5rem; }
      #chatbot-header { font-size: 1rem; padding: 12px 10px; }
      #chatbot-messages { padding: 10px; height: 180px; }
      #chatbot-input { padding: 10px; font-size: 1rem; }
      #chatbot-send { padding: 0 12px; font-size: 1rem; }
    }
  </style>
  <script>
    const chatbotWidget = document.getElementById('chatbot-widget');
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotMinimize = document.getElementById('chatbot-minimize');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotMessages = document.getElementById('chatbot-messages');

    chatbotToggle.onclick = function() {
      chatbotWidget.style.display = 'flex';
      chatbotToggle.style.display = 'none';
      setTimeout(() => chatbotInput.focus(), 200);
    };
    chatbotClose.onclick = function() {
      chatbotWidget.style.display = 'none';
      chatbotToggle.style.display = 'block';
    };
    chatbotMinimize.onclick = function() {
      chatbotWidget.style.display = 'none';
      chatbotToggle.style.display = 'block';
    };
    chatbotSend.onclick = sendMessage;
    chatbotInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') sendMessage();
    });

    // Đề xuất câu hỏi gợi ý
    const suggestedQuestions = [
      "Làm thế nào để đăng ký tài khoản?",
      "Giờ làm việc của bạn là khi nào?",
      "Tôi cần hỗ trợ thanh toán",
      "Cách liên hệ với bộ phận CSKH?"
    ];

    function sendMessage() {
      var msg = chatbotInput.value.trim();
      if (!msg) return;
      appendMessage('user', msg);
      chatbotInput.value = '';
      chatbotInput.focus();
      removeSuggestions();
      appendLoading();
      fetch('support/chatbot.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'message=' + encodeURIComponent(msg)
      })
      .then(res => res.text())
      .then(reply => {
        removeLoading();
        appendMessage('bot', reply);
      });
    }

    // Hiển thị gợi ý khi mở chat lần đầu
    chatbotToggle.onclick = function() {
      chatbotWidget.style.display = 'flex';
      chatbotToggle.style.display = 'none';
      setTimeout(() => chatbotInput.focus(), 200);
      if(chatbotMessages.children.length === 0) {
        setTimeout(() => {
          appendMessage('bot', 'Xin chào! Bạn cần hỗ trợ gì ạ?');
          showSuggestions();
        }, 400);
      }
    };

    function showSuggestions() {
      removeSuggestions();
      // Tìm bubble của tin nhắn bot đầu tiên
      const lastBotMsg = Array.from(chatbotMessages.children).find(row => row.classList.contains('bot'));
      if (lastBotMsg) {
        const bubble = lastBotMsg.querySelector('.chatbot-bubble');
        if (bubble) {
          const suggestions = document.createElement('span');
          suggestions.className = 'chatbot-suggestions-inline';
          suggestedQuestions.forEach(q => {
            const btn = document.createElement('span');
            btn.className = 'chatbot-suggestion';
            btn.textContent = q;
            btn.onclick = function() {
              chatbotInput.value = q;
              chatbotInput.focus();
              sendMessage();
            };
            suggestions.appendChild(btn);
          });
          bubble.appendChild(suggestions);
        }
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
      }
    }
    function removeSuggestions() {
      const exist = chatbotMessages.querySelectorAll('.chatbot-suggestions');
      exist.forEach(e => e.remove());
    }
    function appendMessage(sender, text) {
      var row = document.createElement('div');
      row.className = 'chatbot-msg-row ' + (sender === 'user' ? 'user' : 'bot');
      var avatar = document.createElement('div');
      avatar.className = 'chatbot-avatar ' + (sender === 'user' ? '' : 'bot');
      avatar.innerHTML = sender === 'user' ? '<i class="fa fa-user"></i>' : '<i class="fa fa-robot"></i>';
      var bubble = document.createElement('div');
      bubble.className = 'chatbot-bubble';
      if(sender === 'bot') {
        bubble.innerHTML = text;
      } else {
        bubble.textContent = text;
      }
      if(sender === 'user') {
        row.appendChild(bubble);
        row.appendChild(avatar);
      } else {
        row.appendChild(avatar);
        row.appendChild(bubble);
      }
      chatbotMessages.appendChild(row);
      chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function appendLoading() {
      removeLoading();
      var row = document.createElement('div');
      row.className = 'chatbot-msg-row bot chatbot-loading-row';
      var avatar = document.createElement('div');
      avatar.className = 'chatbot-avatar bot';
      avatar.innerHTML = '<i class="fa fa-robot"></i>';
      var loading = document.createElement('div');
      loading.className = 'chatbot-bubble chatbot-loading';
      loading.innerHTML = '<span></span><span></span><span></span>';
      row.appendChild(avatar);
      row.appendChild(loading);
      chatbotMessages.appendChild(row);
      chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }
    function removeLoading() {
      var loading = chatbotMessages.querySelector('.chatbot-loading-row');
      if(loading) chatbotMessages.removeChild(loading);
    }

    function initShipperMap(lat, lng) {
        var map = L.map('shipperMap').setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
        // Custom marker icon
        var shipperIcon = L.icon({
            iconUrl: 'images/shipper.png', // Đặt icon shipper nổi bật, có thể dùng icon riêng
            iconSize: [38, 48],
            iconAnchor: [19, 48],
            popupAnchor: [0, -48]
        });
        var marker = L.marker([lat, lng], {icon: shipperIcon}).addTo(map)
            .bindPopup('<b>Vị trí shipper</b><br><span style="color:#ff9800;font-weight:500;">Đang giao hàng</span>')
            .openPopup();
        // Hiệu ứng focus marker
        marker.on('mouseover', function(){ marker.openPopup(); });
        marker.on('mouseout', function(){ marker.closePopup(); });
    }
    function fetchOrderDetails(orderId) {
        $.ajax({
            url: 'order_detail_api.php',
            type: 'GET',
            data: { order_id: orderId },
            dataType: 'json',
            success: function(res) {
                if(res.success && res.shipper_id && res.current_latitude && res.current_longitude) {
                    initShipperMap(res.current_latitude, res.current_longitude);
                }
            }
        });
    }
  </script>
<!-- Chatbot Widget End -->
 <!-- Real-time Chat with Mng_shop (WebSocket) -->
  <!-- Bong bóng chat với Mng_shop -->
  <div id="ws-chat-bubble" style="position:fixed;bottom:120px;right:36px;z-index:99998;">
    <button id="ws-chat-toggle" style="width:64px;height:64px;border-radius:50%;background:#ff9800;color:#fff;border:none;box-shadow:0 2px 16px rgba(0,0,0,0.13);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.18s;padding:0;">
      <img src="images/img/master.png" alt="Chat" style="width:48px;height:48px;border-radius:50%;box-shadow:0 1px 6px rgba(0,0,0,0.10);background:#fff;">
    </button>
    <div id="ws-chat-box" style="display:none;position:absolute;bottom:80px;right:0;width:340px;background:#fff;border-radius:18px;box-shadow:0 2px 16px rgba(0,0,0,0.13);padding:16px;">
      <div style="font-weight:600;font-size:1.08rem;margin-bottom:8px;color:#ff9800;display:flex;justify-content:space-between;align-items:center;">
        Bộ phận CSKH
        <button id="ws-chat-close" style="background:none;border:none;font-size:1.3rem;color:#888;cursor:pointer;">&times;</button>
      </div>
      <div id="chat-messages" style="height:180px;overflow-y:auto;margin-bottom:12px;background:#f9f9f9;border-radius:8px;padding:8px;"></div>
      <div style="display:flex;gap:8px;">
        <input type="text" id="chat-input" placeholder="Nhập tin nhắn..." style="flex:1;padding:8px;border-radius:6px;border:1px solid #eee;">
        <button id="ws-chat-send" style="padding:8px 18px;background:#ff9800;color:#fff;border:none;border-radius:6px;font-weight:600;"><i class="fa fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
  <style>
    #ws-chat-bubble {z-index:99998;}
    #ws-chat-toggle {transition:background 0.18s;}
    #ws-chat-toggle:hover {background:#ffe0b2;color:#ff9800;}
    #ws-chat-box {animation: wsChatFadeIn 0.22s;}
    @keyframes wsChatFadeIn {from{opacity:0;transform:scale(0.95);}to{opacity:1;transform:scale(1);}}
    @media (max-width:600px){#ws-chat-box{width:96vw;right:-16vw;}}
  </style>
  <script>
    // --- Bong bóng chat real-time: bỏ tên, thêm icon gửi, gửi bằng Enter ---
    var wsChatToggle = document.getElementById('ws-chat-toggle');
    var wsChatBox = document.getElementById('ws-chat-box');
    var wsChatClose = null;
    var chatMessagesDiv = document.getElementById('chat-messages');
    var chatInput = document.getElementById('chat-input');
    var wsChatSend = document.getElementById('ws-chat-send');
    var chatHistory = [];
    function renderChatMessages() {
      chatMessagesDiv.innerHTML = '';
      chatHistory.forEach(function(msg) {
        var html = '<div style="margin-bottom:6px;display:flex;align-items:center;gap:8px;">';
        if(msg.sender === 'user') {
          html += '<span style="background:#ff9800;color:#fff;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;"><i class="fa fa-user"></i></span>';
        } else {
          html += '<span style="background:#fff;border:2px solid #ff9800;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;overflow:hidden;">';
          html += '<img src="images/img/master.png" alt="Mng_shop" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">';
          html += '</span>';
        }
        html += '<span style="background:#f9f9f9;padding:10px 16px;border-radius:14px;font-size:1.05rem;">'+msg.content+'</span>';
        html += '</div>';
        chatMessagesDiv.innerHTML += html;
      });
      chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
    }
    wsChatToggle.onclick = function() {
      wsChatBox.style.display = 'block';
      wsChatToggle.style.display = 'none';
      wsChatClose = document.getElementById('ws-chat-close');
      if(wsChatClose) wsChatClose.onclick = function(){
        wsChatBox.style.display = 'none';
        wsChatToggle.style.display = 'block';
      };
      setTimeout(function(){chatInput.focus();},200);
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
      var msg = chatInput.value.trim();
      if (!msg) return;
      var data = {sender: 'user', receiver: 'mng_shop', content: msg};
      ws.send(JSON.stringify(data));
      chatHistory.push(data);
      renderChatMessages();
      chatInput.value = '';
    }
    wsChatSend.onclick = sendMessage;
    chatInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') sendMessage();
    });
  </script>
<!-- End Real-time Chat -->
            <div class="container">
                <div class="bottom-footer">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 payment-options color-gray">
                            <h5>Thanh Toán Đa Dịch Vụ</h5>
                            <ul>
                                <li>
                                    <a href="#"> <img src="images/img/momo.png" style="width: 32px; height: 24px;" alt="momo"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="images/img/msc.png" style="width: 32px; height: 24px;" alt="Mastercard"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="images/img/visa.png" style="width: 32px; height: 24px;" alt="visa"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="images/img/vnpay.png" style="width: 32px; height: 24px;" alt="vnpay"> </a>
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
        
        </div>
  
    
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
    <!-- SweetAlert2 for beautiful popup (optional, if you want to use for notifications) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
    $(document).ready(function() {
        // Xử lý nút Chi tiết đơn hàng
        $('.order-detail-btn').on('click', function(e) {
            e.preventDefault();
            var orderId = $(this).data('order-id');
            // Gọi ajax lấy chi tiết đơn hàng
            $.ajax({
                url: 'order_detail_api.php',
                type: 'GET',
                data: { order_id: orderId },
                dataType: 'json',
                success: function(res) {
                    if(res.success) {
                        // Render chi tiết đơn hàng dạng bảng đẹp
                        var html = '';
                        html += '<div style="font-size: 1.1rem; margin-bottom: 10px;"><b>Đơn của bạn tại</b> <span style="color:#ff9800; font-weight:bold;">' + res.restaurant + '</span></div>';
                        html += '<button class="btn btn-primary" id="printInvoiceBtn" style="float:right;margin-bottom:10px;"><i class="fa fa-print"></i> In hoá đơn</button>';
                        html += '<table class="order-detail-table">';
                        html += '<thead><tr><th>Món</th><th>Số lượng</th><th>Giá</th></tr></thead><tbody>';
                        res.items.forEach(function(item) {
                            html += '<tr>';
                            html += '<td>' + item.title + '</td>';
                            html += '<td>' + item.quantity + '</td>';
                            html += '<td>' + parseFloat(item.price).toFixed(3) + '</td>';
                            html += '</tr>';
                        });
                        html += '</tbody></table>';
                        html += '<div class="order-detail-label">Thời gian đặt: <span class="order-detail-value">' + res.date + '</span></div>';
                        html += '<div class="order-detail-label">Trạng thái: <span class="order-detail-value">' + res.status + '</span></div>';
                        html += '<hr style="margin: 10px 0;">';
                        html += '<div class="order-summary">Tổng cộng: <span style="color:#d32f2f; font-size:1.2rem; font-weight:bold;">' + parseFloat(res.total).toFixed(3) + '</span></div>';
                        if(res.discount && res.discount > 0) {
                            html += '<div class="order-detail-label">Giảm giá: <span class="order-detail-value" style="color:#388e3c;"> VNĐ' + parseFloat(res.discount).toFixed(3) + '</span></div>';
                        }
                        if(res.fee && res.fee > 0) {
                            html += '<div class="order-detail-label">Phí giao hàng: <span class="order-detail-value">VNĐ' + parseFloat(res.fee).toFixed(3) + '</span></div>';
                        }
                        html += '<div class="order-detail-label">Địa chỉ giao hàng: <span class="order-detail-value">' + (res.address || '') + '</span></div>';
                        // Thông tin shipper + bản đồ
                        if(res.shipper_id) {
                            html += '<div class="shipper-info" style="margin:18px 0 12px 0; padding:12px; background:#e6f4ea; border-radius:8px;">';
                            html += '<b>Shipper:</b> ' + (res.shipper_name ? res.shipper_name : '') + '<br>';
                            html += '<b>SĐT:</b> ' + (res.shipper_phone ? res.shipper_phone : '') + '<br>';
                            html += '</div>';
                            html += '<div id="shipperMap" style="height:320px; border-radius:18px; margin-bottom:16px; background:#fffbe6; box-shadow:0 4px 24px rgba(0,0,0,0.13); border:2px solid #ff9800; display:flex; align-items:center; justify-content:center; color:#888; font-size:1.08rem;"></div>';
                            html += '<button id="refreshLocationBtn" class="btn btn-info" style="margin-bottom:12px;">Làm mới vị trí shipper</button>';
                        }
                        $('#order-detail-content').html(html);
                        $('#orderDetailModal').modal('show');
                        $('#printInvoiceBtn').off('click').on('click', function() {
                            printInvoice(res);
                        });
                        // Hàm in hoá đơn chuyên nghiệp
                        function printInvoice(order) {
                            var win = window.open('', 'INVOICE', 'height=700,width=600');
                            var logoUrl = window.location.origin + '/OnlineFood-PHP/images/img/iconss.png';
                            var html = '';
                            html += '<div style="font-family:Segoe UI,Arial,sans-serif;max-width:520px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 2px 12px #eee;padding:24px;">';
                            html += '<div style="text-align:center;margin-bottom:18px;">';
                            html += '<img src="' + logoUrl + '" alt="FastFood" style="width:70px;height:70px;border-radius:50%;box-shadow:0 2px 8px #ff9800;">';
                            html += '<h2 style="color:#ff9800;margin:12px 0 0 0;">HOÁ ĐƠN THANH TOÁN</h2>';
                            html += '<div style="color:#888;font-size:1.08rem;">Công Ty Cổ Phần Foody<br>Lầu G, Tòa nhà Jabes 1, 244 Cống Quỳnh, Q.1, TPHCM<br>Hotline: 1900 2042 | Email: cskh@support.fastfood.vn</div>';
                            html += '</div>';
                            html += '<div style="margin-bottom:12px;">';
                            html += '<b>Khách hàng:</b> ' + (order.customer_name || '') + '<br>';
                            html += '<b>Địa chỉ giao hàng:</b> ' + (order.address || '') + '<br>';
                            html += '<b>Thời gian đặt:</b> ' + (order.date || '') + '<br>';
                            html += '<b>Trạng thái:</b> ' + (order.status || '') + '<br>';
                            html += '</div>';
                            html += '<table style="width:100%;border-collapse:collapse;margin-bottom:12px;">';
                            html += '<thead><tr style="background:#ffe0b2;"><th style="padding:8px 8px;text-align:left;">Món</th><th style="padding:8px 8px;text-align:center;">SL</th><th style="padding:8px 8px;text-align:right;">Giá</th></tr></thead><tbody>';
                            order.items.forEach(function(item) {
                                html += '<tr>';
                                html += '<td style="padding:8px 8px;">' + item.title + '</td>';
                                html += '<td style="padding:8px 8px;text-align:center;">' + item.quantity + '</td>';
                                html += '<td style="padding:8px 8px;text-align:right;">' + parseFloat(item.price).toLocaleString() + '</td>';
                                html += '</tr>';
                            });
                            html += '</tbody></table>';
                            if(order.discount && order.discount > 0) {
                                html += '<div style="color:#388e3c;font-weight:600;margin-bottom:6px;">Giảm giá: -' + parseFloat(order.discount).toLocaleString() + ' VNĐ</div>';
                            }
                            if(order.fee && order.fee > 0) {
                                html += '<div style="color:#888;font-weight:500;margin-bottom:6px;">Phí giao hàng: ' + parseFloat(order.fee).toLocaleString() + ' VNĐ</div>';
                            }
                            html += '<div style="font-size:1.15rem;font-weight:700;color:#d32f2f;text-align:right;margin-top:10px;">Tổng cộng: ' + parseFloat(order.total).toLocaleString() + ' VNĐ</div>';
                            html += '<div style="margin-top:24px;color:#aaa;font-size:0.98rem;text-align:center;">Cảm ơn bạn đã sử dụng dịch vụ của FastFood!</div>';
                            html += '</div>';
                            win.document.write('<html><head><title>Hoá đơn FastFood</title>');
                            win.document.write('</head><body style="background:#f5f5f5;">');
                            win.document.write(html);
                            win.document.write('</body></html>');
                            setTimeout(function() { win.print(); win.close(); }, 500);
                        }
                        // Luôn hiển thị bản đồ shipper nếu có shipper_id
                        if(res.shipper_id) {
                            if(res.current_latitude && res.current_longitude) {
                                initShipperMap(res.current_latitude, res.current_longitude);
                                document.getElementById('refreshLocationBtn').onclick = function() {
                                    fetchOrderDetails(res.o_id);
                                };
                            } else {
                                // Nếu chưa có vị trí thì hiển thị thông báo trong map area
                                document.getElementById('shipperMap').innerHTML = '<span style="color:#888; font-size:1.08rem;">Chưa có vị trí của shipper. Vui lòng thử lại sau hoặc bấm Làm mới.</span>';
                                document.getElementById('refreshLocationBtn').onclick = function() {
                                    fetchOrderDetails(res.o_id);
                                };
                            }
                        }
                    } else {
                        Swal.fire('Lỗi', 'Không tìm thấy chi tiết đơn hàng!', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Lỗi', 'Không thể lấy chi tiết đơn hàng!', 'error');
                }
            });
        });

        // Xử lý huỷ đơn hàng
        $(document).on('click', '.cancel-order-btn', function(e) {
            e.preventDefault();
            var orderId = $(this).data('order-id');
            Swal.fire({
                title: 'Bạn chắc chắn muốn huỷ đơn này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Huỷ đơn',
                cancelButtonText: 'Không',
                confirmButtonColor: '#d9534f',
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: 'product-action.php',
                        type: 'POST',
                        data: { action: 'cancel_order', order_id: orderId },
                        dataType: 'json',
                        success: function(res) {
                            if(res.success) {
                                Swal.fire('Thành công', 'Đơn hàng đã được huỷ!', 'success').then(()=>{location.reload();});
                            } else {
                                Swal.fire('Lỗi', res.message || 'Không thể huỷ đơn!', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Lỗi', 'Không thể huỷ đơn!', 'error');
                        }
                    });
                }
            });
        });

        // Xử lý đặt lại đơn hàng
        $(document).on('click', '.reorder-btn', function(e) {
            e.preventDefault();
            var orderId = $(this).data('order-id');
            Swal.fire({
                title: 'Bạn muốn đặt lại đơn này?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Đặt lại',
                cancelButtonText: 'Không',
                confirmButtonColor: '#4caf50',
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: 'product-action.php',
                        type: 'POST',
                        data: { action: 'reorder', order_id: orderId },
                        dataType: 'json',
                        success: function(res) {
                            if(res.success) {
                                Swal.fire('Thành công', 'Đơn hàng đã được đặt lại!', 'success').then(()=>{location.reload();});
                            } else {
                                Swal.fire('Lỗi', res.message || 'Không thể đặt lại đơn!', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Lỗi', 'Không thể đặt lại đơn!', 'error');
                        }
                    });
                }
            });
        });
    });
    </script>
    
</body>

</html>
<?php
}
?>