<?php
if (isset($_SESSION["userid"]) && isset($_SESSION["loginkey"])) {
    $userid = $_SESSION["userid"];
    $loginkey = $_SESSION["loginkey"];
    $selectuser = "SELECT * FROM users WHERE id='$userid'";
    $queryuser = mysqli_query($con, $selectuser);
    $getuser = mysqli_fetch_assoc($queryuser);
    if ($loginkey != $getuser["loginkey"]) {
        alert("Your session has expired<br>Login to continue...", [], "../login");
    }
} else {
    alert("Your session has expired<br>Login to continue...", [], "../login");
}
?>