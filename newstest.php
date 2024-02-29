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
        
      
        .box-2{
            border:1px solid lightgray;
        }
       
.conten {
    max-height: 100%; /* Set the maximum height for the content */
    overflow-y: hide; /* Enable vertical scrolling if the content overflows */
    font-size: 14px; /* Adjust the font size as needed */
    line-height: 1.5; /* Set the line height for better readability */
    white-space: nowrap;
    width: 100%; /* Ensure the content fills the available width */



}
</style>
</head>

<body>
<div class="container">
    <div class="row">
        <!-- News -->
        <div class="col-md-8" style="border:1px solid grey;">

<h1 style="    background-color:#E6C08F;
            font-weight:bold;
            font-size:20px;
            padding:5px;
            text-align: center;
            width:100%;">News</h1>
<br>
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
        echo '<a href="' . $url . '" target="_blank"><img src="' . $image . '" class="img-fluid" alt="Image"></a>';
        echo '</div>';
        echo '<div class="col-md-7">';
        echo '<p class="paper_info">' . $item['paper'] . '</p>';
        echo '<p style="font-size:15px;font-weight:bold;margin:5px;">' . $item['title'] . '</p>';
        echo '<p class="content">' . $description . '</p>';
        echo '<p style="color:grey;
margin:5px;
            font-size:12px;
            font-weight:bold;">' . $item['time'] . '</p>';
        echo '</div>';
        echo '</div>';
                }
            } else {
                echo '<p>No news available.</p>';
            }
            ?>
        </div>
        <!-- Announcements -->
        <div class="col-md-4" style="border:1px solid grey;">
        <div class="heading-container">
            <h1 style="font-weight: bold; font-size: 20px; padding: 5px; text-align: center; background-color: #E6C08F; margin-bottom: 0;">Announcements</h1>
        </div>
        <div class="announcement-container">
            <ul  style=" padding: 10px;">
                <a href="#" style="font-weight: bold; font-size: 15px; color: black;">
                    <li>International Institute of Information Technology Hyderabad</li>
                </a>
                <a href="#" style="font-weight: bold; font-size: 15px; color: black;">
                    <li>International Institute of Information Technology Hyderabad</li>
                </a>
                <a href="#" style="font-weight: bold; font-size: 15px; color: black;">
                    <li>International Institute of Information Technology Hyderabad</li>
                </a>
            </ul>
        </div>
        </div>
    </div>
</div>

</body>

</html>