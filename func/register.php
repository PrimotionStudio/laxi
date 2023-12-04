<?php
require_once("session.php");
require_once("sql.php");
require_once("misc.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = ucwords(str_replace(' ', '', clean($_POST["fullname"])));
    $email = clean($_POST["email"]);
    $password = (clean($_POST["password"]));
    $confirm = (clean($_POST["confirm"]));

    if (($fullname != "") || ($email != "")) {
        if ($password == $confirm) {
            // Check for duplicate
            $selectuser = "SELECT email FROM users WHERE email='$email'";
            $queryuser = mysqli_query($con, $selectuser);
            $numuser = mysqli_num_rows($queryuser);
            if ($numuser == 0) {
                // Disable User Reg
                // $insertuser = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$password')";
                // if (mysqli_query($con, $insertuser)) {
                //     $selectuser = "SELECT id FROM users WHERE fullname='$fullname' && email='$email'";
                //     $queryuser = mysqli_query($con, $selectuser);
                //     $getuserid = mysqli_fetch_assoc($queryuser)["id"];
                //     alert(ucwords($fullname)." was created successfully", [], "../login");
                // } else {
                    // alert("An error occured when scheduling post", [], "../app/schedule");
                // }
                alert("User Registration has been disabled", [], "../app/schedule");
            } else {
                alert("The email: $email is already registered in our database...", [], "../register");
            }
        } else {
            alert("The passwords do not match", [], "../register");
        }
    } else {
        alert("Please fill all fields", [], "../register");
    }
} else {
    alert("An error occured connecting to server", [], "../register");
}
?>