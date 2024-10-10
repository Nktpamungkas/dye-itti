<?php
ini_set("error_reporting", 1);
session_start();
//include config
//require_once "waktu.php";
include_once('koneksi.php');

?>
<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dyeing | Lockscreen</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!--alerts CSS -->
    <link href="bower_components/sweetalert/sweetalert2.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <!--<link rel="stylesheet"
        href="dist/css/font/font.css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">-->
    <style>
      body{
		font-family: Calibri, "sans-serif", "Courier New";  /* "Calibri Light","serif" */
		font-style: normal;
	}
	   .headline {
  color: #fff;
  text-shadow: 1px 3px 5px rgba(0, 0, 0, 0.5);
  font-weight: 300;
  -webkit-font-smoothing: antialiased !important;
  opacity: 0.8;
  margin: 10px 0 30px 0;
  font-size: 60px;
}
</style>
    <link rel="icon" type="image/png" href="dist/img/index.ico">
  </head>

  <body class="hold-transition lockscreen ">

    <!-- Automatic element centering -->
    <div class="lockscreen-wrapper">
      <div class="lockscreen-logo">
        <a href="index1.php"><b>Dyeing ITTI</b></a>
      </div>
      <div class="headline text-center" id="time">
        <!-- Time auto generated by js -->
      </div>
      <!-- User name -->
      <div class="lockscreen-name">
        <?php echo strtoupper($_SESSION['user_id10']);?>
      </div>

      <!-- START LOCK SCREEN ITEM -->
      <div class="lockscreen-item">
        <!-- lockscreen image -->
        <div class="lockscreen-image">
          <img src="dist/img/<?php echo $_SESSION['foto10'];?>.png" alt="User Image">
        </div>
        <!-- /.lockscreen-image -->

        <!-- lockscreen credentials (contains the form) -->
        <form class="lockscreen-credentials" method="post" enctype="multipart/form-data" name="form1" action="">
          <div class="input-group">
            <input type="password" name="password" class="form-control" placeholder="password">

            <div class="input-group-btn">
              <button type="button" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
            </div>
          </div>
        </form>
        <!-- /.lockscreen credentials -->

      </div>
      <!-- /.lockscreen-item -->
      <div class="help-block text-center">
        Enter your password to retrieve your session
      </div>
      <div class="text-center">
        <a href="login">Or sign in as a different user</a>
      </div>
      <div class="lockscreen-footer text-center">
        Copyright &copy; 2019 <b><a href="#" class="text-black">DIT</a></b><br>
        All rights reserved
      </div>
    </div>
    <!-- /.center -->

    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Sweet Alert -->
    <script type="text/javascript" src="bower_components/sweetalert/sweetalert2.min.js"></script>
    <!-- page script -->
    <script type="text/javascript">
      $(function() {
        startTime();
        $(".center").center();
        $(window).resize(function() {
          $(".center").center();
        });
      });

      /*  */
      function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();

        // add a zero in front of numbers<10
        m = checkTime(m);
        s = checkTime(s);

        //Check for PM and AM
        var day_or_night = (h > 11) ? "PM" : "AM";

        //Convert to 12 hours system
        if (h > 12)
          h -= 12;

        //Add time to the headline and update every 500 milliseconds
        $('#time').html(h + ":" + m + ":" + s + " " + day_or_night);
        setTimeout(function() {
          startTime()
        }, 500);
      }

      function checkTime(i) {
        if (i < 10) {
          i = "0" + i;
        }
        return i;
      }

      /* CENTER ELEMENTS IN THE SCREEN */
      jQuery.fn.center = function() {
        this.css("position", "absolute");
        this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
          $(window).scrollTop()) - 30 + "px");
        this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
          $(window).scrollLeft()) + "px");
        return this;
      }

    </script>
  </body>

</html>
<?php
if ($_POST) { //login user
    extract($_POST);
    $username = $_SESSION['user_id10'];
    $password = mysqli_real_escape_string($con,$_POST['password']);
    $sql=mysqli_query($con,"select * from tbl_user where username='$username' and password='$password'  and (dept='DYE' or dept='DIT') limit 1", $con);
    if (mysqli_num_rows($sql)>0) {
        $_SESSION['user_id10']=$username;
        $_SESSION['pass_id10']=$password;
        $r = mysqli_fetch_array($sql);
		$_SESSION['nama10']=$r['nama'];
        $_SESSION['lvl_id10']=$r['level'];
        $_SESSION['status10']=$r['status'];
        $_SESSION['foto10']=$r['foto'];
        $_SESSION['ket10']=$r['ket'];
        //login_validate();
        echo "<script>window.location='index1.php?p=Home';</script>";
    } else {
        echo "<script> swal({
            title: 'Login Gagal!!',
            text: ' Klik Ok untuk Login kembali',
            type: 'error'
        }, function(){
            window.location='login';
        });</script>";
    }
} else {
    unset($_SESSION['pass_id10']);
}

?>