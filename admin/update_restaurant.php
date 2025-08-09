<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if(isset($_POST['submit']))        
{
        if(empty($_POST['c_name'])||empty($_POST['res_name'])||$_POST['email']==''||$_POST['phone']==''||$_POST['url']==''||$_POST['o_hr']==''||$_POST['c_hr']==''||$_POST['o_days']==''||$_POST['address']=='')
        {	
            $error = '<div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>All fields Must be Fillup!</strong></div>';						
        }
    else
        {
                $fname = $_FILES['file']['name'];
                                $temp = $_FILES['file']['tmp_name'];
                                $fsize = $_FILES['file']['size'];
                                $extension = explode('.',$fname);
                                $extension = strtolower(end($extension));  
                                $fnew = uniqid().'.'.$extension;
                                $store = "Res_img/".basename($fnew);                   
                    if($extension == 'jpg'||$extension == 'png'||$extension == 'gif' )
                    {        
                                    if($fsize>=1000000)
                                        {
                                            $error = '<div class="alert alert-danger alert-dismissible fade show">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <strong>Max Image Size is 1024kb!</strong> Try different Image.</div>';
       
                                        }
                                    else
                                        {
                                                $res_name=$_POST['res_name'];
                                                $sql = "update restaurant set c_id='$_POST[c_name]', title='$res_name',email='$_POST[email]',phone='$_POST[phone]',url='$_POST[url]',o_hr='$_POST[o_hr]',c_hr='$_POST[c_hr]',o_days='$_POST[o_days]',address='$_POST[address]',image='$fnew' where rs_id='$_GET[res_upd]' ";  // store the submited data ino the database :images												mysqli_query($db, $sql); 
                                                mysqli_query($db, $sql); 
                                                move_uploaded_file($temp, $store);			  
                                                $success = 	'<div class="alert alert-success alert-dismissible fade show">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <strong>Record Updated!</strong>.</div>';
                                        }
                    }
                    elseif($extension == '')
                    {
                        $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>select image</strong></div>';
                    }
                    else{
                        $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>invalid extension!</strong>png, jpg, Gif are accepted.</div>';
                        }               
       }
}

?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">   
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link rel="icon" href="../images/img/admin.png">
    <title>Cập nhật cửa hàng</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <div id="main-wrapper">
         <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php"> 
                        <span><img src="images/icn.png" alt="homepage" class="dark-logo" /></span>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0">
                    </ul>
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                            <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/bookingSystem/user-icn.png" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Đăng xuất</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                   <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Trang Chủ</li>
                        <li> <a href="dashboard.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
                        <li class="nav-label">Log</li>
                        <li> <a href="all_users.php">  <span><i class="fa fa-user f-s-20 "></i></span><span>Người dùng</span></a></li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Shop / Cửa hàng</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_restaurant.php">Shop / Cửa hàng</a></li>
                                <li><a href="add_category.php">Thêm danh mục</a></li>
                                <li><a href="add_restaurant.php">Thêm Shop / Cửa hàng</a></li>
                            </ul>
                        </li>
                      <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Menu</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_menu.php">Tất cả menu</a></li>
                                <li><a href="add_menu.php">Thêm Menu</a></li>
                            </ul>
                        </li>
                         <li> <a href="all_orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Đơn hàng</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>
   
        <div class="page-wrapper">
      
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3> </div>
            </div>
            <div class="container-fluid">		
                                    <?php  
                                    echo $error;
                                    echo $success; ?>

                        <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <h4 class="m-b-0 "><?php echo isset($row['title']) ? htmlspecialchars($row['title']) : 'Cập nhật'; ?></h4>
                            <div class="card-body">
                                <form action='' method='post'  enctype="multipart/form-data">
                                    <div class="form-body">
                                       <?php $ssql ="select * from restaurant where rs_id='$_GET[res_upd]'";
                                                    $res=mysqli_query($db, $ssql); 
                                                    $row=mysqli_fetch_array($res);?>
                                        <hr>
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Tên cửa hàng</label>
                                                    <input type="text" name="res_name" value="<?php echo $row['title'];  ?>" class="form-control" placeholder="John doe">
                                                   </div>
                                            </div>
                                   
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">E-mail</label>
                                                    <input type="text" name="email" value="<?php echo $row['email'];  ?>"class="form-control form-control-danger" placeholder="example@gmail.com">
                                                    </div>
                                            </div>
                                        
                                        </div>
                                     
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">SĐT </label>
                                                    <input type="text" name="phone" class="form-control" value="<?php echo $row['phone'];  ?>" placeholder="1-(555)-555-5555">
                                                   </div>
                                            </div>
                         
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">website</label>
                                                    <input type="text" name="url" class="form-control form-control-danger" value="<?php echo $row['url'];  ?>" placeholder="http://example.com">
                                                    </div>
                                            </div>
                                       
                                        </div>
                                
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Giờ mở cửa</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                        <input type="time" name="o_hr" class="form-control" value="<?php echo isset($row['o_hr']) ? htmlspecialchars($row['o_hr']) : ''; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                      
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Giờ đóng cửa</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                        <input type="time" name="c_hr" class="form-control" value="<?php echo isset($row['c_hr']) ? htmlspecialchars($row['c_hr']) : ''; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Ngày hoạt động</label>
                                                    <select name="o_days" class="form-control custom-select"  data-placeholder="Chọn ngày hoạt động" tabindex="1">
                                                        <option value="">Chọn ngày hoạt động</option>
                                                        <option value="mon-tue">Thứ 2 - Thứ 3</option>
                                                        <option value="mon-wed">Thứ 2 - Thứ 4</option>
                                                        <option value="mon-thu">Thứ 2 - Thứ 5</option>
                                                        <option value="mon-fri">Thứ 2 - Thứ 6</option>
                                                        <option value="mon-sat">Thứ 2 - Thứ 7</option>
                                                        <option value="24hr-x7">Cả tuần (24/7)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">Image</label>
                                                    <input type="file" name="file"  id="lastName"  class="form-control form-control-danger" placeholder="12n">
                                                    </div>
                                            </div>
                                   
                                            
                                             <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Danh mục</label>
                                                    <select name="c_name" class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1">
                                                        <option>Danh mục</option>
                                                 <?php $ssql ="select * from res_category";
                                                    $res=mysqli_query($db, $ssql); 
                                                    while($rows=mysqli_fetch_array($res))  
                                                    {
                                                       echo' <option value="'.$rows['c_id'].'">'.$rows['c_name'].'</option>';;
                                                    }  
                                                 
                                                    ?> 
                                                     </select>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <h3 class="box-title m-t-40">Địa chỉ</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <div class="form-group">
                                                    <textarea name="address" type="text" style="height:100px;" class="form-control" > <?php echo $row['address']; ?> </textarea>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <input type="submit" name="submit" class="btn btn-primary" value="Save"> 
                                        <a href="all_restaurant.php" class="btn btn-inverse">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
       
            </div>
            
        </div>
  
    </div>
  
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>

</body>

</html>