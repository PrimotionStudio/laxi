<?php
require_once("func/session.php");
require_once("func/sql.php");
require_once("func/misc.php");
if (isset($_SESSION["userid"]) && isset($_SESSION["loginkey"])) {
    $userid = $_SESSION["userid"];
    $loginkey = $_SESSION["loginkey"];
    $selectuser = "SELECT * FROM users WHERE id='$userid'";
    $queryuser = mysqli_query($con, $selectuser);
    $getuser = mysqli_fetch_assoc($queryuser);
    if ($loginkey == $getuser["loginkey"]) {
        alert("", [], "app/index");
    }
}
require_once("partials/alert.php");
$page_title = "Register - ";
require_once("partials/head.php");
?>
  <body>

    <div class="signup-panel">
      <svg-to-inline path="assets/svg/directions.svg" class-name="svg-bg"></svg-to-inline>

      <div class="signup-sidebar">
        <div class="signup-sidebar-body">
          <a href="#" class="sidebar-logo mg-b-40"><span>LAXI</span></a>
          <h4 class="signup-title">Create New Account!</h4>
          <h5 class="signup-subtitle">It's free and only takes a minute.</h5>

          <form action="func/register" method="post" class="signup-form">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Fullname</label>
                  <input type="text" name="fullname" class="form-control" placeholder="Enter your fullname">
                </div>
              </div><!-- col -->
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" placeholder="Enter your email">
                </div>
              </div><!-- col -->
            </div><!-- row -->

            <div class="row mg-b-5">
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" placeholder="Enter your password">
                </div>
              </div><!-- col -->
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Confirm Password</label>
                  <input type="password" name="confirm" class="form-control" placeholder="Enter your password">
                </div>
              </div><!-- col -->
            </div><!-- row -->

            <div class="form-group d-flex mg-b-0">
              <button type="submit" class="btn btn-brand-01 btn-uppercase btn-block">Create New Account</button>
            </div>
          </form>
          <p class="mg-t-auto mg-b-0 tx-color-03">Already have an account? <a href="login">Signin</a></p>
        </div><!-- signup-sidebar-body -->
      </div><!-- signup-sidebar -->
    </div><!-- signup-panel -->
<?php
require_once("partials/nonauthfooter.php");
?>