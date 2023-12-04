<?php
require_once("session.php");
require_once("sql.php");
require_once("misc.php");
$selectposts = "SELECT * FROM posts WHERE status='Pending'";
$queryposts = mysqli_query($con, $selectposts);
while ($getposts = mysqli_fetch_assoc($queryposts)) {
    if ((($getposts["media"] == "[]") || ($getposts["media"] == "")) && (($getposts["post"] == "") || ($getposts["post"] == " "))) {
        $deletepost = "DELETE FROM posts WHERE id='".$getposts["id"]."'";
        mysqli_query($con, $deletepost);
    } else {
        $currentDateTime = date('Y-m-d H:i:s');
        if ($currentDateTime > $getposts["postdate"]) {
            $postid = $getposts["id"];
            
            $postData = [
                'post' => trim(str_replace("'", '\'', $getposts["post"])),
                'platforms' => stringToArray($getposts["platforms"]),
            ];

            if (!empty($getposts["media"])) {
                $postmedia = $getposts["media"];
                $postData['mediaUrls'] = "http://localhost/laxi/".array2string($postmedia);
            }

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

            echo "You have ".count($errors)." error(s) and ".count($success)." success(es)<br>";
            echo "<pre>Error:";
            foreach ($errors as $key => $value) {
                echo "<br>".$errors[$key].": ".$value;
            }
            echo "<br>Success:";
            foreach ($success as $key => $value) {
                echo "<br>".$success[$key].": ".$value;
                hideposted($con, $postid);
            }
            echo "</pre>";

            $error = "response_message: " . json_encode($data) . ", postdata: " . json_encode($postData);
            $insertlog = "INSERT INTO logs (errorlog) VALUES ('$error')";
            mysqli_query($con, $insertlog);
            curl_close($ch);

        }
    }
}
?>