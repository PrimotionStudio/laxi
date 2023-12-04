<?php
require_once("session.php");
require_once("sql.php");
require_once("misc.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = ucwords(str_replace(' ', '', clean($_POST["email"])));
    $password = (clean($_POST["password"]));

    if (($email != "")) {
        // Check if account exists
        $selectuser = "SELECT * FROM users WHERE email='$email'";
        $queryuser = mysqli_query($con, $selectuser);
        $numuser = mysqli_num_rows($queryuser);
        if ($numuser == 1) {
            $getuser = mysqli_fetch_assoc($queryuser);
            if ($password == $getuser["password"]) {
                $userid = $getuser["id"];
                $loginkey = password_hash(time(), PASSWORD_BCRYPT);
                $_SESSION["userid"] = $userid;
                $_SESSION["loginkey"] = $loginkey;
                $insertloginkey = "UPDATE users SET loginkey='$loginkey' WHERE id='$userid'";
                if (mysqli_query($con, $insertloginkey)) {
                    alert("", "", "../");
                } else {
                    unset($_SESSION["userid"]);
                    unset($_SESSION["loginkey"]);
                    alert("An error occured", [], "../login");
                }
            } else {
                    alert("Invalid email and password combination", [], "../login");
            }
        } else {
            alert("Invalid email and password combination", [], "../login");
        }
    } else {
        alert("Please fill all fields", [], "../login");
    }
} else {
    alert("An error occured connecting to server", [], "../login");
}
?>