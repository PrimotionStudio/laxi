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
$page_title = "Login - ";
require_once("partials/head.php");
?>
  <body>

    <div class="signin-panel">
      <svg-to-inline path="assets/svg/citywalk.svg" class-name="svg-bg"></svg-to-inline>

      <div class="signin-sidebar">
        <div class="signin-sidebar-body">
          <a href="#" class="sidebar-logo mg-b-40"><span>LAXI</span></a>
          <h4 class="signin-title">Welcome back!</h4>
          <h5 class="signin-subtitle">Please signin to continue.</h5>

          <form class="signin-form" action="func/login" method="post">
            <div class="form-group">
              <label>Email address</label>
              <input type="email" name="email" class="form-control" placeholder="Enter your email address">
            </div>

            <div class="form-group">
              <label class="d-flex justify-content-between">
                <span>Password</span>
              </label>
              <input type="password" name="password" class="form-control" placeholder="Enter your password">
            </div>

            <div class="form-group d-flex mg-b-0">
              <button type="submit" class="btn btn-brand-01 btn-uppercase flex-fill">Sign In</button>
              <a href="register" class="btn btn-white btn-uppercase flex-fill mg-l-10">Sign Up</a>
            </div>

          </form>
        </div><!-- signin-sidebar-body -->
      </div><!-- signin-sidebar -->
    </div><!-- signin-panel -->
<?php
require_once("partials/nonauthfooter.php");
?>