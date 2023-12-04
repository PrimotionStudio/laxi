<?php
require_once("session.php");
require_once("sql.php");
require_once("misc.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postid = clean($_POST["postid"]);
    if ($postid != "") {
        $postid = str_ireplace("post", "", $postid);
        $selectposts = "SELECT * FROM posts WHERE id='$postid'";
        $queryposts = mysqli_query($con, $selectposts);
        if (mysqli_num_rows($queryposts) == 1) {
            $getposts = json_encode(mysqli_fetch_assoc($queryposts));

            header("Content-Type: application/json");
            echo $getposts;
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