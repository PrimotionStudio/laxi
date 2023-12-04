<?php
function clean($data) {
    return stripslashes(htmlspecialchars(trim($data), ENT_QUOTES | ENT_HTML5));
}
function unclean($data) {
    return htmlspecialchars_decode(stripslashes($data));
}

function alert($alert_msg="", $form_data=[], $location="") {
    if ($alert_msg != "") {
        $_SESSION["alert"] = $alert_msg;
    }
    if ($form_data != []) {
        $_SESSION["form_data"] = $form_data;
    }
    if ($location != "") {
        header("location: $location");
    }
}
function querytable($connection, $table, $searchby, $searchbyvalue, $searchquery) {
    $selecttable = "SELECT * FROM $table WHERE $searchby='$searchbyvalue'";
    $querytable = mysqli_query($connection, $selecttable);
    if (mysqli_num_rows($querytable) == 1) {
        $value = mysqli_fetch_assoc($querytable)[$searchquery];
    } else {
        $value = "Cannot Find $table";
    }
    return $value;
}

function encrypt($data) {
    $encryptionKey = openssl_random_pseudo_bytes(32);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
    $cipherText = openssl_encrypt($data, 'AES-256-CBC', $encryptionKey, OPENSSL_RAW_DATA, $iv);
    $encryptedData = $iv . $cipherText;
    return base64_encode($encryptedData);
}
function decrypt($encryptedData) {
    $encryptionKey = openssl_random_pseudo_bytes(32);
    $encryptedData = base64_decode($encryptedData);
    $ivLength = openssl_cipher_iv_length('AES-256-CBC');
    $iv = substr($encryptedData, 0, $ivLength);
    $cipherText = substr($encryptedData, $ivLength);
    $decryptedData = openssl_decrypt($cipherText, 'AES-256-CBC', $encryptionKey, OPENSSL_RAW_DATA, $iv);
    return $decryptedData;
}
function countreply($postid, $con) {
    $selectposts = "SELECT * FROM posts WHERE reply='$postid'";
    $queryposts = mysqli_query($con, $selectposts);
    if (mysqli_num_rows($queryposts) > 1) {
        $reply = "Replies";
    } else {
        $reply = "Reply";
    }
    return mysqli_num_rows($queryposts) ." ". $reply;
}
function convertphone($phoneNumber) {
    $phoneNumber = preg_replace('/\D/', '', $phoneNumber);
    $phoneNumber = ltrim($phoneNumber, '0');
    $phoneNumber = '234' . $phoneNumber;
    $phoneNumber = ltrim($phoneNumber, '+');
    $formattedNumber = '+' . $phoneNumber;
    return $formattedNumber;
}
function truncatetext($text) {
    $maxLength = 20;
    if (strlen($text) > $maxLength) {
        $truncatedText = substr($text, 0, $maxLength) . '...';
    } else {
        $truncatedText = $text;
    }
    return $truncatedText;
}
function arrayToString($inputArray) {
    return implode('_', $inputArray);
}
function stringToArray($inputString) {
    return explode('_', $inputString);
}
function array2string($inputString) {
    return str_replace(['["', '"]'], '', $inputString);
}
function string2array($inputString) {
    $inputString = trim($inputString, "[]");
    $paragraphs = explode('","', $inputString);
    $result = [];
    foreach ($paragraphs as $paragraph) {
        $result[] = trim($paragraph, '"');
    }
    return $result;
}
function getTimeDistance($inputDate) {
    $timeDistance = 0;
    if ($inputDate) {
        $currentTime = time();
        $inputTime = strtotime($inputDate);
        
        $timeDifference = abs($inputTime - $currentTime);
        
        $weeks = floor($timeDifference / (7 * 24 * 3600));
        $days = floor(($timeDifference % (7 * 24 * 3600)) / (24 * 3600));
        $hours = floor(($timeDifference % (24 * 3600)) / 3600);
        $minutes = floor(($timeDifference % 3600) / 60);
        
        $timeDistance = '';
        
        if ($weeks > 0) {
            $timeDistance .= $weeks . ($weeks == 1 ? ' week' : ' weeks');
        } elseif ($days > 0) {
            $timeDistance .= $days . ($days == 1 ? ' day' : ' days');
        } elseif ($hours > 0) {
            $timeDistance .= $hours . ($hours == 1 ? ' hour' : ' hours');
        } elseif ($minutes > 0) {
            $timeDistance .= $minutes . ($minutes == 1 ? ' minute' : ' minutes');
        } else {
            $timeDistance = 'just now';
        }
        
    }
    
    return $timeDistance;
}

function formatDateTime($inputDateTime, $formatType) {
    $timestamp = strtotime($inputDateTime);
    $currentTimestamp = time();
    
    if ($formatType === 'date') {
        $formattedDate = date('M d, Y', $timestamp);

        if (date('Y-m-d', $timestamp) === date('Y-m-d', $currentTimestamp)) {
            $formattedDate = 'TODAY<br>' . $formattedDate;
        } elseif (date('Y-m-d', $timestamp) === date('Y-m-d', strtotime('+1 day', $currentTimestamp))) {
            $formattedDate = 'TOMORROW<br>' . $formattedDate;
        } else {
        }

        return $formattedDate;
    } elseif ($formatType === 'time') {
        return date('h:i', $timestamp)."<span>".date('a', $timestamp)."</span>";
    } else {
        return 'Invalid format type';
    }
}

function post($con, $post, $platforms, $media = "[]") {
    $apiKey = 'ZTNB3ES-DGZ4YT8-JBENPT1-9G42S35';
    $apiEndpoint = 'https://app.ayrshare.com/api/post';
    if (($media == "[]") || ($media == "")) {
        $_SESSION["alert"] = "Media Not Detected";
        $postData = [
            'post' => trim(str_replace("'", '\'', $post)),
            'platforms' => $platforms,
        ];
    } else {
        $postData = [
            'post' => trim(str_replace("'", '\'', $post)),
            'platforms' => $platforms,
            'mediaUrls' => $media,
        ];
    }
    $jsonData = json_encode($postData);
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
    if (curl_errno($ch) || ($data["status"] != "success")) {
        $error = $data["message"]." Curl Error:".curl_errno($ch)." postdata: ".arrayToString($postData['mediaUrls']);
        $insertlog = "INSERT INTO logs (errorlog) VALUES ('$error')";
        mysqli_query($con, $insertlog);
        curl_close($ch);
        return "Failed";
    } else {
        curl_close($ch);
        return "Success";
    }
}
function hideposted($con, $postid) {
    $updatepost = "UPDATE posts SET status='Posted' WHERE id='$postid'";
    mysqli_query($con, $updatepost);
}
?>