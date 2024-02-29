<?php

$api_url = "https://ims.iiit.ac.in/research_apis.php?typ=getNews";

// Create a stream context with SSL options
$sslContext = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
]);

// Use the stream context when fetching data
$response = file_get_contents($api_url, false, $sslContext);

$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Francois+One&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Aleo:wght@900&display=swap" rel="stylesheet">
    <!-- Include your preferred CSS framework or styles for accordion here -->
    <!-- Include your preferred CSS framework or styles for accordion here -->
    <style>
        .img-fluid{
            width: 100%;
            height:150px;
        }
        .paper_info{
            color:grey;
            font-size:12px;
            font-weight:bold;
        }
        .content{
            font-size:15px;
            font-weight:500;
        }
        
        .heading{
           /* background-color:#E6C08F;*/
            font-weight:bold;
            font-size:20px;
            padding:5px;
            text-align: center;
            width:100%;
        }
        .box-2{
            border:1px solid lightgray;
        }
        .paper_info,
.conten {
    max-height: 100%; /* Set the maximum height for the content */
    overflow-y: hide; /* Enable vertical scrolling if the content overflows */
    font-size: 14px; /* Adjust the font size as needed */
    line-height: 1.5; /* Set the line height for better readability */
    white-space: nowrap;
    width: 100%; /* Ensure the content fills the available width */


}

/* Add animation keyframes */
@keyframes slideIn {
    0% {
        opacity: 0;
        transform: translateY(100%);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Apply animation to the caption */
.animated-slide {
    animation: slideIn 0.5s ease-in-out;
}

    </style>
</head>

<body>
<div class="container pt-3">
    <div class="row">
        <!-- News -->
        <div class="col-md-8" >
            <h4 style="background-color:#E6C08F;text-align:center;font-weight:bold;  font-family: Francois One;">NEWS</h4>
            <?php
            if ($data && is_array($data)) {
                foreach ($data as $item) {
                    // Display news item
                    // Set a default image if no image is provided
        $image = !empty($item['photo']) ? $item['photo'] : 'https://i.postimg.cc/9MwGHx97/news-default-big.png';
        // Truncate description if it's too long
        $description = strlen($item['description']) > 100 ? substr($item['description'], 0, 100) . "..." : $item['description'];
        // Set the URL for the anchor tag
        $url = !empty($item['url']) ? $item['url'] : $_SERVER['REQUEST_URI'];
        // Display the news item with a clickable image
        echo '<div class="row mb-4">';
        echo '<div class="col-md-5">';
        echo '<a href="' . $url . '"><img src="' . $image . '" class="img-fluid" alt="Image"></a>';
        echo '</div>';
        echo '<div class="col-md-7">';
        echo '<p class="paper_info">' . $item['paper'] . '</p>';
        echo '<p >' . $item['title'] . '</p>';
        echo '<p class="content">' . $description . '</p>';
        echo '<p class="time">' . $item['time'] . '</p>';
        echo '</div>';
        echo '</div>';
                }
            } else {
                echo '<p>No news available.</p>';
            }
            ?>
        </div>
        <!-- Announcements -->
        <div class="col-md-4">
    <h4 style="background-color:#E6C08F;text-align:center;font-weight:bold;font-family: Francois One;">Announcements</h4>
    <?php
    // Fetch announcements from the API
    $api_url_announcements = "https://ims.iiit.ac.in/research_apis.php?typ=getAnnouncements";
    $response_announcements = file_get_contents($api_url_announcements, false, $sslContext);
    $announcements_data = json_decode($response_announcements, true);

    // Check if announcements data is available and is an array
    if ($announcements_data && is_array($announcements_data)) {
        echo '<ul class="list-announcement">';
        // Loop through each announcement
        foreach ($announcements_data as $announcement) {
            // Set the URL for the anchor tag
            $url = !empty($announcement['url']) ? $announcement['url'] : $_SERVER['REQUEST_URI'];
            // Display each announcement as a list item with a clickable link
            echo '<li><a href="' . ($url ?? $_SERVER['REQUEST_URI']) . '" style="font-weight:bold;font-size:15px;color:black">' . $announcement['title'] . '</a></li>';
        }
        echo '</ul>';
    } else {
        // Display a message if no announcements available
        echo '<p>No announcements available.</p>';
    }
    ?>
</div>

    </div>
</div>

</body>

</html>
