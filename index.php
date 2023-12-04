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
alert("", [], "login");
?>