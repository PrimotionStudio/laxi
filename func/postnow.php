<?php
require_once("session.php");
require_once("sql.php");
require_once("misc.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postid = clean($_POST["post"]);
    
    if (($postid != "") && strstr($postid, "post")) {
        $postid = str_ireplace("post", "", $postid);
        $selectposts = "SELECT * FROM posts WHERE id='$postid'";
        $queryposts = mysqli_query($con, $selectposts);

        if (mysqli_num_rows($queryposts) == 1) {
            $getposts = mysqli_fetch_assoc($queryposts);

            $postData = [
                'post' => trim(str_replace("'", '\'', $getposts["post"])),
                'platforms' => stringToArray($getposts["platforms"]),
            ];

            // if (!empty($getposts["media"])) {
            //     $postmedia = $getposts["media"];
            //     $postData['mediaUrls'] = "http://localhost/laxi/".array2string($postmedia);
            // }

            $jsonData = json_encode($postData);

            $apiKey = 'F69HHZP-TQEM5T4-GM8H5Z8-J6C37PQ';
            $apiEndpoint = 'https://app.ayrshare.com/api/post';
            $ch = curl_init($apiEndpoint);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_POST, true);
            
            $response = curl_exec($ch);
            $data = json_decode($response, true);
            // print_r($data);
            
            $errors = [];
            $success = [];
            if ($data["errors"]!= null) {
                foreach ($data["errors"] as $key => $value) {
                    if ($data["errors"][$key]["status"] == "error") {
                        $errors[$data["errors"][$key]["platform"]] = $data["errors"][$key]["message"];
                    }
                }
            }
            if ($data["postIds"]!=null){
                foreach ($data["postIds"] as $key => $value) {
                    if ($data["postIds"][$key]["status"] == "success") {
                        $success[] = $data["postIds"][$key]["platform"];
                    }
                }
            }

            $error_msg = "You have ".count($errors)." error(s) and ".count($success)." success(es)<br>";
            $error_msg .= "<pre>Error:";
            foreach ($errors as $key => $value) {
                $error_msg .= "<br><b>".$errors[$key]."</b>: ".$value;
            }
            $error_msg .= "<br>Success:";
            foreach ($success as $key => $value) {
                $error_msg .= "<br><b>".ucfirst($value)."</b>";
                hideposted($con, $postid);
            }

            $error_msg .= "</pre>";
            $_SESSION["alert"] = $error_msg;

            $error = "response_message: " . json_encode($data) . ", postdata: " . json_encode($postData);
            $insertlog = "INSERT INTO logs (errorlog) VALUES ('$error')";
            mysqli_query($con, $insertlog);
            curl_close($ch);

            echo "Refresh";
        } else {
            echo "An error occurred connecting to the server";
        }
    } else {
        echo "An error occurred connecting to the server";
    }
} else {
    echo "An error occurred connecting to the server";
}
?>