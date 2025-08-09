<!DOCTYPE html>
<html lang="en" >
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if(isset($_POST['submit']))
{
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  if(!empty($_POST["submit"])) 
     {
  $loginquery ="SELECT * FROM admin WHERE username='$username' && password='".md5($password)."'";
  $result=mysqli_query($db, $loginquery);
  $row=mysqli_fetch_array($result);
  
                          if(is_array($row))
                {
                                    $_SESSION["adm_id"] = $row['adm_id'];
                                    echo '<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><meta http-equiv="refresh" content="1.5;url=dashboard.php"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Đăng nhập thành công</title><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body style="background:#f8f9fa;">';
                                    echo '<div style="position:fixed;top:20px;left:20px;z-index:9999;min-width:260px;max-width:90vw;" class="alert alert-success shadow fade show"><i class="fa fa-check-circle"></i> Đăng nhập thành công!</div>';
                                    echo '<div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10000;text-align:center;">';
                                    echo '<div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status"></div>';
                                    echo '<div class="mt-3 text-primary">Đang chuyển hướng...</div>';
                                    echo '</div>';
                                    echo '<script>setTimeout(function(){window.location.href="dashboard.php";},1500);</script>';
                                    echo '</body></html>';
                                    exit;
                              } 
              else
                  {
                    echo "<script>alert('Invalid Username or Password!');</script>"; 
                                }
   }
  
  
}

?>

<head>
  <meta charset="UTF-8">
  <link rel="icon" href="../images/img/admin.png">
  <title>Đăng nhập admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
<link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

      <link rel="stylesheet" href="css/login.css">

  
</head>

<body>

  
<div class="container">
  <div class="info">
    <h1>Admin Panel </h1>
  </div>
</div>
<div class="form">
  <div class="thumbnail"><img src="images/manager.png"/></div>
  <span style="color:red;"><?php echo $message; ?></span>
   <span style="color:green;"><?php echo $success; ?></span>
  <form class="login-form" action="login.php" method="post">
    <input type="text" placeholder="Username" name="username"/>
    <input type="password" placeholder="Password" name="password"/>
    <input type="submit"  name="submit" value="Login" />

  </form>
  
</div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src='js/index.js'></script>
</body>

</html>
