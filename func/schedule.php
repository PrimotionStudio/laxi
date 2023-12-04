<?php
require_once("session.php");
require_once("sql.php");
require_once("misc.php");
require_once("validate_login.php");

if (!isset($_SESSION["token"])) {
    $token = bin2hex(random_bytes(16));
    $_SESSION["token"] = $token;
} else {
    $token = $_SESSION["token"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = ucwords(clean($_POST["title"]));
    $platform = arrayToString(($_POST["platform"]));
    $postdate = (clean($_POST["postdate"]));
    $post = str_replace("'", "\'", $_POST["post"]);

    $files = NULL;
    $file = NULL;
    $error = 0;
    if ($_FILES['mediafile']["name"] != ""){
        $file_array = array_filter($_FILES['mediafile']['name']);
        $files = array();
        foreach ($file_array as $key => $value) {
            $file_name = $_FILES['mediafile']['name'][$key];
            $file_size = $_FILES['mediafile']['size'][$key];
            $file_tmp = $_FILES['mediafile']['tmp_name'][$key];
            $file_type = $_FILES['mediafile']['type'][$key];
            $dot = explode('.', $file_name);
            $file_ext = strtolower(end($dot));
            if (count($_FILES["mediafile"]["name"]) <= 10) {
                if (($file_size < (50 * 1024 * 1024)) && ($file_size >= 0)) {
                    $new_file_name = "../uploads/file" . date("YmdhisA") . $token . "." . $file_ext;
                    move_uploaded_file($file_tmp, $new_file_name);
                    $file = str_replace("../", "", $new_file_name);
                        array_push($files, $file);
                } else {
                    $error = 1;
                    alert("Sorry, your file is too large", [], "../app/schedule");
                }
            } else {
                $error = 1;
                alert("Sorry, you exceeded the 10 files upload limit", [], "../app/schedule");
            }
        }
    } else {
        $error = 1;
        alert("Sorry, An error occured while uploading your file", [], "../app/schedule");
    }
    if ($error == 0) {
        $mediafiles = json_encode($files);
        $insertpost = "INSERT INTO posts (userid, title, platforms, postdate, media, post) VALUES ('$userid', '$title', '$platform', '$postdate', '$mediafiles', '$post')";
        if (mysqli_query($con, $insertpost)) {
            alert(ucwords($title)." was scheduled successfully", [], "../app/schedule");
        } else {
            alert("An error occured when scheduling post", [], "../app/schedule");
        }
    } else {
        alert("An error occured when scheduling post", [], "../app/schedule");
    }
} else {
    alert("An error occured connecting to server", [], "../app/schedule");
}
?>