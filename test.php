<?php

// Get your Ayrshare API key from the Ayrshare dashboard.
$api_key = "ZTNB3ES-DGZ4YT8-JBENPT1-9G42S35";

// The social network that you want to post to.
$social_network = "facebook";

// The text of the post.
$post_text = "This is a test post with ayrshare.";

// The image or video URL (optional).
$image_url = "";

// The URL of the post (optional).
$post_url = "";

// The schedule time (optional).
$schedule_time = "";

// Create the cURL request.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.ayrshare.com/api/post");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    "api_key" => $api_key,
    "social_network" => $social_network,
    "post_text" => $post_text,
    "image_url" => $image_url,
    "post_url" => $post_url,
    "schedule_time" => $schedule_time,
]);

// Execute the cURL request.
$result = curl_exec($ch);

// Check the result.
if ($result === false) {
    echo "Error: " . curl_error($ch);
} else {
    // The post was successful.
    echo "Posted successfully.";
}

// Close the cURL session.
curl_close($ch);

?>
