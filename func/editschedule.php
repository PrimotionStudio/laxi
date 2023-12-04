<?php
require_once("session.php");
require_once("sql.php");
require_once("misc.php");
require_once("validate_login.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = querytable($con, "posts", "id", $_POST["postid"], "userid");
    if (isset($_POST["postid"]) && strpos($result, "Cannot Find") === false) {
        if ($result == $userid) {
            $title = ucwords(clean($_POST["title"]));
            $platform = arrayToString($_POST["platform"]);
            $postdate = clean($_POST["postdate"]);
            $post = clean($_POST["post"]);
            $updatepost = "UPDATE posts SET userid='$userid', title='$title', platforms='$platform', postdate='$postdate', post='$post' WHERE id='".$_POST["postid"]."'";
            if (mysqli_query($con, $updatepost)) {
                alert(ucwords($title)." was scheduled successfully", [], "../app/schedule");
            } else {
                alert("An error occured when scheduling post", [], "../app/schedule");
            }
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