<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if(empty($_SESSION["adm_id"]))
{
    header('location:login.php');
}
else
{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../images/img/admin.png">
    <title>Admin Panel </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <img src="images/icn.png" alt="Logo">
            </a>
            
            <div class="d-flex align-items-center">
                <div class="user-dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <img src="images/bookingSystem/user-icn.png" alt="User">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
                <button class="navbar-toggler ms-2 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="pt-3">
            <ul class="nav flex-column">
                <li class="nav-label">Home</li>
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-label">Danh mục</li>
                <li class="nav-item">
                    <a class="nav-link" href="all_users.php">
                        <i class="fas fa-user"></i>
                        <span>Người dùng</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#restaurantMenu" role="button">
                        <i class="fas fa-utensils"></i>
                        <span>Shop / Cửa hàng</span>
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="restaurantMenu">
                        <ul class="nav flex-column ms-4">
                            <li class="nav-item">
                                <a class="nav-link" href="all_restaurant.php">Tất cả</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="add_category.php">Thêm Danh Mục</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="add_restaurant.php">Thêm Shop / cửa hàng</a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menuMenu" role="button">
                        <i class="fas fa-book-open"></i>
                        <span>Menu</span>
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="menuMenu">
                        <ul class="nav flex-column ms-4">
                            <li class="nav-item">
                                <a class="nav-link" href="all_menu.php">Tất cả menu</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="add_menu.php">Thêm Menu</a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="all_orders.php">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <h1 class="page-title">Admin Dashboard</h1>
            
            <!-- Stats Row 1 -->
            <div class="row">
                <!-- Restaurants -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card restaurant">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="stat-title">Shop / cửa hàng</div>
                                    <div class="stat-value">
                                        <?php 
                                            $sql = "SELECT * FROM restaurant";
                                            $result = mysqli_query($db, $sql); 
                                            $rws = mysqli_num_rows($result);
                                            echo $rws;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-utensils fa-2x stat-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Dishes -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card dishes">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="stat-title">Món ăn</div>
                                    <div class="stat-value">
                                        <?php 
                                            $sql = "SELECT * FROM dishes";
                                            $result = mysqli_query($db, $sql); 
                                            $rws = mysqli_num_rows($result);
                                            echo $rws;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-utensils fa-2x stat-icon" style="color:#3498db;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Users -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card users">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="stat-title">Người dùng</div>
                                    <div class="stat-value">
                                        <?php 
                                            $sql = "SELECT * FROM users";
                                            $result = mysqli_query($db, $sql); 
                                            $rws = mysqli_num_rows($result);
                                            echo $rws;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x stat-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Orders -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card orders">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="stat-title">Đơn Hàng</div>
                                    <div class="stat-value">
                                        <?php 
                                            $sql = "SELECT * FROM users_orders";
                                            $result = mysqli_query($db, $sql); 
                                            $rws = mysqli_num_rows($result);
                                            echo $rws;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-basket fa-2x stat-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats Row 2 -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card earnings">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="stat-title">Tổng Tiền</div>
                                    <div class="stat-value">
                                        <?php 
                                            $result = mysqli_query($db, "SELECT SUM(total) AS value_sum FROM users_orders"); 
                                            $row = mysqli_fetch_assoc($result); 
                                            $sum = $row['value_sum'] ? $row['value_sum'] : 0;
                                            echo number_format($sum, 0, ',', '.') . ' VNĐ';
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x stat-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Processing Orders -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card processing">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="stat-title">Xử lý Đơn hàng</div>
                                    <div class="stat-value">
                                        <?php 
                                            $sql = "SELECT * FROM users_orders WHERE status = 'in process' ";
                                            $result = mysqli_query($db, $sql); 
                                            $rws = mysqli_num_rows($result);
                                            echo $rws;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-sync-alt fa-2x stat-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Delivered Orders -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card delivered">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="stat-title">Đơn Đang giao</div>
                                    <div class="stat-value">
                                        <?php 
                                            $sql = "SELECT * FROM users_orders WHERE status = 'closed' ";
                                            $result = mysqli_query($db, $sql); 
                                            $rws = mysqli_num_rows($result);
                                            echo $rws;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x stat-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Cancelled Orders -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card cancelled">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="stat-title">Huỷ đơn hàng</div>
                                    <div class="stat-value">
                                        <?php 
                                            $sql = "SELECT * FROM users_orders WHERE status = 'rejected' ";
                                            $result = mysqli_query($db, $sql); 
                                            $rws = mysqli_num_rows($result);
                                            echo $rws;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-times-circle fa-2x stat-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Statistics Chart & Sales/Earnings Cards -->
            <div class="row">
                <div class="col-xl-8 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">Người dùng </h5>
                                <div>
                                    <button class="btn btn-light btn-sm">Xuất</button>
                                    <button class="btn btn-light btn-sm">In</button>
                                </div>
                            </div>
                            <canvas id="userStatsChart" height="120"></canvas>
                            <div class="mt-3 d-flex justify-content-center">
                                <span class="mr-3"><span style="color:#e74c3c;font-weight:bold"> ● </span> cửa hàng</span>
                                <span class="mr-3"><span style="color:#f1c40f;font-weight:bold"> ● </span> Người dùng</span>
                                <span><span style="color:#3498db;font-weight:bold"> ● </span> Món ăn</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 mb-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Tổng tiền</h5>
                            <h2 class="card-text">
                                <?php 
                                    $result = mysqli_query($db, "SELECT SUM(total) AS value_sum FROM users_orders"); 
                                    $row = mysqli_fetch_assoc($result); 
                                    $sum = $row['value_sum'] ? $row['value_sum'] : 0;
                                    echo number_format($sum, 0, ',', '.') . ' VNĐ';
                                ?>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- World Map Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row card-tools-still-right">
                                <h4 class="card-title">Cửa hàng</h4>
                            </div>
                            <p class="card-category">
                                Bản đồ phân bố cửa hàng trên toàn thế giới
                            </p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive table-hover table-sales">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>khu vực hoạt động</th>
                                                    <th>Quốc gia</th>
                                                    <th>Ngày tạo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $result = mysqli_query($db, "SELECT c_id, c_name, country_code, date FROM res_category ORDER BY c_id ASC");
                                                $markers = [];
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo '<tr>';
                                                    echo '<td>' . htmlspecialchars($row['c_id']) . '</td>';
                                                    echo '<td>' . htmlspecialchars($row['c_name']) . '</td>';
                                                    echo '<td>' . htmlspecialchars($row['country_code']) . '</td>';
                                                    echo '<td>' . htmlspecialchars($row['date']) . '</td>';
                                                    echo '</tr>';
                                                    // Đưa dữ liệu marker ra JS
                                                    $markers[] = [
                                                        'name' => $row['c_name'],
                                                        'country_code' => $row['country_code']
                                                    ];
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                            <script>
                                                // Danh sách tọa độ trung tâm quốc gia (ví dụ, chỉ một số quốc gia)
                                                const countryCenters = {
                                                    'US': [-98.35, 39.50],
                                                    'CN': [104.1954, 35.8617],
                                                    'RU': [105.3188, 61.5240],
                                                    'AU': [133.7751, -25.2744],
                                                    'BR': [-51.9253, -14.2350],
                                                    'ID': [113.9213, -0.7893],
                                                    'IT': [12.5674, 41.8719],
                                                    'FR': [2.2137, 46.6034],
                                                    'DE': [10.4515, 51.1657],
                                                    'IN': [78.9629, 20.5937],
                                                    'JP': [138.2529, 36.2048],
                                                    'GB': [-3.435973, 55.378051],
                                                    'VN': [108.2772, 14.0583]
                                                };
                                                // Dữ liệu marker từ PHP
                                                const resMarkers = <?php echo json_encode($markers); ?>;
                                            </script>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mapcontainer">
                                        <div id="world-map" style="height: 350px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/7.8.5/d3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/topojson/3.0.2/topojson.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.querySelector('.navbar-toggler').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
        
        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggler = document.querySelector('.navbar-toggler');
            
            if (window.innerWidth < 768 && 
                !sidebar.contains(event.target) && 
                !toggler.contains(event.target) && 
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
        
        // Tạo bản đồ thế giới
        function initWorldMap() {
            const width = document.getElementById('world-map').clientWidth;
            const height = 350;
            
            const svg = d3.select("#world-map")
                .append("svg")
                .attr("width", width)
                .attr("height", height);
            
            const projection = d3.geoNaturalEarth1()
                .scale(width / 1.5 / Math.PI)
                .translate([width / 2, height / 2]);
            
            const path = d3.geoPath().projection(projection);
            
            // Tải dữ liệu bản đồ
            d3.json("https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json").then(function(world) {
                const countries = topojson.feature(world, world.objects.countries);
                // Vẽ các quốc gia
                svg.selectAll("path")
                    .data(countries.features)
                    .enter().append("path")
                    .attr("d", path)
                    .attr("fill", "#4e9a6a")
                    .attr("stroke", "#2c3e50")
                    .attr("stroke-width", 0.5)
                    .on("mouseover", function(event, d) {
                        d3.select(this).attr("fill", "#ff6b6b");
                        // Hiển thị tooltip quốc gia
                        const countryName = d.properties.name || d.id || "Unknown";
                        const tooltip = d3.select("body")
                            .append("div")
                            .attr("class", "tooltip")
                            .style("position", "absolute")
                            .style("background", "rgba(0,0,0,0.8)")
                            .style("color", "white")
                            .style("padding", "10px")
                            .style("border-radius", "5px")
                            .style("pointer-events", "none")
                            .html(countryName);
                        tooltip.style("left", (event.pageX + 10) + "px")
                            .style("top", (event.pageY - 30) + "px");
                    })
                    .on("mousemove", function(event) {
                        d3.select(".tooltip")
                            .style("left", (event.pageX + 10) + "px")
                            .style("top", (event.pageY - 30) + "px");
                    })
                    .on("mouseout", function() {
                        d3.select(this).attr("fill", "#4e9a6a");
                        d3.select(".tooltip").remove();
                    });
                // Thêm đường viền
                svg.append("path")
                    .datum(topojson.mesh(world, world.objects.countries, (a, b) => a !== b))
                    .attr("class", "boundary")
                    .attr("d", path);

                // Vẽ marker cho từng res_category
                resMarkers.forEach(function(marker) {
                    const center = countryCenters[marker.country_code];
                    if (!center) return; // Nếu không có tọa độ thì bỏ qua
                    const coords = projection(center);
                    svg.append("circle")
                        .attr("cx", coords[0])
                        .attr("cy", coords[1])
                        .attr("r", 7)
                        .attr("fill", "#e74c3c")
                        .attr("stroke", "#fff")
                        .attr("stroke-width", 2)
                        .style("cursor", "pointer")
                        .on("mouseover", function(event) {
                            d3.select(this).attr("fill", "#f6c23e");
                        })
                        .on("mouseout", function() {
                            d3.select(this).attr("fill", "#e74c3c");
                        })
                        .on("click", function(event) {
                            // Hiển thị tooltip tên khu vực hoạt động
                            d3.selectAll(".marker-tooltip").remove();
                            const tooltip = d3.select("body")
                                .append("div")
                                .attr("class", "marker-tooltip")
                                .style("position", "absolute")
                                .style("background", "#3498db")
                                .style("color", "white")
                                .style("padding", "10px")
                                .style("border-radius", "5px")
                                .style("pointer-events", "none")
                                .html(`<b>${marker.name}</b> (${marker.country_code})`);
                            tooltip.style("left", (event.pageX + 10) + "px")
                                .style("top", (event.pageY - 30) + "px");
                        });
                    // Ẩn tooltip khi click ra ngoài
                    d3.select("body").on("click", function(event) {
                        if (!event.target.closest("circle")) {
                            d3.selectAll(".marker-tooltip").remove();
                        }
                    });
                });
            });
        }
        
        // Khởi tạo biểu đồ thống kê người dùng
        <?php
            // Lấy số lượng restaurant, users, dishes theo tháng
            $restaurant = array_fill(0, 12, 0);
            $users = array_fill(0, 12, 0);
            $dishes = array_fill(0, 12, 0);
            
            // Restaurant
            $sql = "SELECT date FROM restaurant";
            $result = mysqli_query($db, $sql);
            while($row = mysqli_fetch_assoc($result)) {
                $month = (int)date('n', strtotime($row['date'])) - 1;
                $restaurant[$month]++;
            }
            
            // Users
            $sql = "SELECT date FROM users";
            $result = mysqli_query($db, $sql);
            while($row = mysqli_fetch_assoc($result)) {
                $month = (int)date('n', strtotime($row['date'])) - 1;
                $users[$month]++;
            }
            
            // Dishes
            $sql = "SELECT d_id FROM dishes";
            $result = mysqli_query($db, $sql);
            $count = mysqli_num_rows($result);
            foreach(range(0,11) as $i) $dishes[$i] = $count;
        ?>
        
        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo bản đồ
            initWorldMap();
            
            // Khởi tạo biểu đồ
            const ctx = document.getElementById('userStatsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                    datasets: [
                        {
                            label: 'Restaurant',
                            data: <?php echo json_encode($restaurant); ?>,
                            backgroundColor: 'rgba(231,76,60,0.2)',
                            borderColor: '#e74c3c',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#e74c3c',
                        },
                        {
                            label: 'Users',
                            data: <?php echo json_encode($users); ?>,
                            backgroundColor: 'rgba(241,196,15,0.2)',
                            borderColor: '#f1c40f',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#f1c40f',
                        },
                        {
                            label: 'Dishes',
                            data: <?php echo json_encode($dishes); ?>,
                            backgroundColor: 'rgba(52,152,219,0.2)',
                            borderColor: '#3498db',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#3498db',
                        }
                    ]
                },
                options: {
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled: true,
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#eee' } },
                        x: { grid: { color: '#eee' } }
                    }
                }
            });
        });
    </script>
</body>
</html>
<?php
} 
?>