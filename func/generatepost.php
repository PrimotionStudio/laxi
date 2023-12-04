<?php
require_once("session.php");
require_once("sql.php");
require_once("misc.php");
require_once("validate_login.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $topic = ucwords(clean($_POST["topic"]));
    $platform = arrayToString(($_POST["platform"]));
    $heading = (clean($_POST["heading"]));
    $number = (clean($_POST["number"]));
    
    if (($number > 3) && ($number < 33)) {
        $postarray = [];

        while (count($postarray) < $number) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://paragraph-generator.p.rapidapi.com/paragraph-generator?topic=".urlencode($topic)."&section_heading=".urlencode($heading),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: paragraph-generator.p.rapidapi.com",
                    "X-RapidAPI-Key: 55c7eabb03msh62e6099093e65bfp1a8a59jsn169a58da121a"
                ],
            ]);
            $response = string2array(curl_exec($curl));
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                alert("Cannot generate post at this moment.<br>Try again later", [], "../app/schedule");
                break;
            } else {
                $postarray = array_merge($postarray, $response);
            }
        }

        // Ensure the postarray has the desired number of values
        if (count($postarray) > $number) {
            $postarray = array_slice($postarray, 0, $number);
        }

		// Array of active times
		$activeTimes = [
			"Mon" => ["10:00", "15:30", "20:45"],
			"Tue" => ["11:15", "16:00", "21:20"],
			"Wed" => ["09:30", "14:45", "19:15"],
			"Thu" => ["08:45", "13:30", "18:00"],
			"Fri" => ["10:30", "15:00", "20:30"],
			"Sat" => ["11:00", "16:15", "21:45"],
			"Sun" => ["09:15", "14:00", "19:30"]
		];
		$dayNames = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
		// Get the current day of the week (0=Sun, 1=Mon, etc.)
		$currentDayOfWeek = date("w");

		// Initialize a counter for tracking iterations
		$iteration = 1;

		foreach ($postarray as $post) {
			if (($post != "") || ($post != " ")) {
			    $post = str_replace("'", "\'", $post);
				// Calculate the day index for the active times array
				$dayIndex = ($currentDayOfWeek + $iteration) % 7; // Modulus ensures it wraps around
				// Choose a random time from the active times for the chosen day
				$randomTimeIndex = array_rand($activeTimes[$dayNames[$dayIndex]]);
				$randomTime = $activeTimes[$dayNames[$dayIndex]][$randomTimeIndex];
				
				// Calculate the date for the post based on the iteration
				$postdate = date("Y-m-d", strtotime("+$iteration days"))." ".$randomTime;

				$insertpost = "INSERT INTO posts (userid, title, platforms, postdate, post) VALUES ('$userid', '$topic', '$platform', '$postdate', '$post')";
				mysqli_query($con, $insertpost);
				// Increment the iteration counter
				$iteration++;
			}

		}

        alert(ucwords($topic)." posts were scheduled successfully", [], "../app/schedule");
    } else {
        alert("Number of posts must be between 4 and 32", [], "../app/schedule");
    }
} else {
    alert("An error occurred connecting to the server", [], "../app/schedule");
}
?>
