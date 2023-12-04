<?php
require_once("session.php");
require_once("sql.php");
require_once("misc.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postid = clean($_POST["post"]);
    if (($postid != "") && strstr($postid,"post")) {
        $postid = str_ireplace("post", "", $postid);
        $selectposts = "SELECT * FROM posts WHERE id='$postid'";
        $queryposts = mysqli_query($con, $selectposts);
        if (mysqli_num_rows($queryposts) == 1) {
            $getposts = mysqli_fetch_assoc($queryposts);

            // Call Post Operation

            // If Posts Was successful

            $deletepost = "DELETE FROM posts WHERE id='$postid'";
            mysqli_query($con, $deletepost);
            echo "Deleted";
        } else {
            echo "An error occured connecting to server";
        }
    } else {
        echo "An error occured connecting to server";
    }
} else {
    echo "An error occured connecting to server";
}
?>