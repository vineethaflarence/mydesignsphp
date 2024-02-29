<?php

$apiUrl = 'https://ims.iiit.ac.in/research_apis.php?typ=getEvents';
//print_r($apiUrl);

$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Execute cURL session and fetch the JSON string
$json_string = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
    exit; // Terminate the script if cURL error occurs
}

curl_close($ch);

$data = json_decode($json_string, true);

if ($data === null) {
    echo 'Error decoding JSON';
    exit; // Terminate the script if JSON decoding error occurs
}

// The rest of your code remains unchanged...


// Filter events based on start and end dates
$currentDate = date('Y-m-d');
$currentYear = date('Y');
$upcomingEvents = [];
$previousEvents = [];

foreach ($data as $event) {
    $eventStartDate = $event['startdate'];
    $eventEndDate = $event['enddate'];

    // Check if the event's start date and end date are above the current date and current year
    if ($eventStartDate >= $currentDate && $eventEndDate >= $currentDate && date('Y', strtotime($eventStartDate)) == $currentYear) {
        $upcomingEvents[] = $event;
    } else {
        $previousEvents[] = $event;
    }
}

// Pagination
$perPage = 4;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$totalUpcomingEvents = count($upcomingEvents);
$totalPreviousEvents = count($previousEvents);

$upcomingEvents = array_slice($upcomingEvents, ($page - 1) * $perPage, $perPage);
$previousEvents = array_slice($previousEvents, ($page - 1) * $perPage, $perPage);

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

    <title>Student Template</title>

    <link href="https://fonts.googleapis.com/css?family=Cinzel+Decorative&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        .card {
          margin:10px;
            margin-left: 10px;
            margin-right: 10px;
            position: relative;
            overflow: hidden;
            height: auto;
        }

        img {
            width: 100%;
            display: block; 
        }

        .date-content {
            background-color: #0d264a;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            width: 50px;
            height: 50px;
            padding: 5px;
            border-radius: 5px;
            position: relative; 
            bottom: 50%; 
            left: 20px; 
            box-sizing: border-box;
        }

        .info-content {
            padding: 10px;
            position: relative;
            top: -50px;
            width: 100%;
            box-sizing: border-box;
        }

        @media (max-width: 767px) {
            .date-content {
                position: relative;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Upcoming Events -->
        <div class="row">
            <div class="col-12 m-3 mb-3">
                <h2>Upcoming Events</h2>
                <br>
               <div class="row">
               <?php
foreach ($upcomingEvents as $event) {
    $dateComponents = explode('-', $event['displaydate']);
    [$month, $day] = $dateComponents;

    // Check if photo is null or empty
    $photoSrc = (!empty($event['photo']) ? htmlspecialchars($event['photo']) : 'https://i.postimg.cc/qRHbHJQP/thumbnail-events.jpg');

    echo '
    <div class="card col-lg-3 col-md-6 col-sm-12" style="background-color:#ECECEC; height:auto;">
        <a href="' . $event['url'] . '">
            <div style="height: 200px; overflow: hidden;"> <!-- Set a fixed height for the image container -->
                <img src="' . $photoSrc . '" class="img-fluid" alt="Event Image" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </a>
        <div>
            <p class="date-content text-md-center">' . $month . '<br>' . $day . '</p>
        </div>
        <div class="info-content">
            <p style="font-size:16px; font-weight:bold; color:#0d264a">' . $event['title'] . '</p>
            <p style="font-weight:500; font-size:15px;">' . $event['description'] . '</p>
        </div>
    </div>';
}

?>    
</div>

<!-- Pagination for Upcoming Events -->
                <div class="row">
                    <div class="col-12">
                        <ul class="pagination" style="margin:10px;background-color:white;color:#0d264a;font-weight:bold;">
                            <?php
                            // Previous Page Link
                            if ($page > 0) {
                                echo '<li class="page-item"><a class="page-link" style="margin:10px;background-color:white;color:#0d264a;" href="?page=' . ($page - 1) . '">Previous</a></li>';
                            }

                            // Numbered Pages
                            for ($i = 1; $i <= ceil($totalPreviousEvents / $perPage); $i++) {
                                echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" style="margin:10px;background-color:white;color:#0d264a;" href="?page=' . $i . '">' . $i . '</a></li>';
                            }

                            // Next Page Link
                            if ($page < ceil($totalPreviousEvents / $perPage)) {
                                echo '<li class="page-item"><a class="page-link" style="margin:10px;background-color:white;color:#0d264a;" href="?page=' . ($page + 1) . '">Next</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Previous Events -->
        <div class="row">
    <div class="col-12 m-3 mb-3">
        <h2>Previous Events</h2>
        <br>
        <div class="row">
            <?php
            foreach ($previousEvents as $event) {
                $dateComponents = explode('-', $event['displaydate']);
                [$month, $day] = $dateComponents;

                // Check if photo is null or empty
                $photoSrc = (!empty($event['photo']) ? htmlspecialchars($event['photo']) : 'https://i.postimg.cc/qRHbHJQP/thumbnail-events.jpg');

                echo '
                <div class="card col-lg-3 col-md-6 col-sm-12" style="flex: 0 0 calc(25% - 10px); max-width: calc(25% - 10px); margin: 5px; background-color: #ECECEC;">
              
                    <a href="' . $event['url'] . '">
                        <div style="height: 150px; overflow: hidden;">
                            <img src="' . $photoSrc . '" class="img-fluid" alt="Event Image" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </a>
                    <div>
                        <p class="date-content text-md-center">' . $month . '<br>' . $day . '</p>
                    </div>
                    <div class="info-content">
                        <p style="font-size:16px; font-weight:bold; color:#0d264a">' . $event['title'] . '</p>
                        <p style="font-weight:500; font-size:15px;">' . $event['description'] . '</p>
                    </div>
                </div>';
            }
            ?>
        </div>
        <!-- Pagination for Previous Events -->
        <div class="row">
            <div class="col-12">
                <ul class="pagination" style="margin:10px;background-color:white;color:#0d264a;font-weight:bold;">
                    <?php
                    // Previous Page Link
                    if ($page > 0) {
                        echo '<li class="page-item"><a class="page-link" style="margin:10px;background-color:white;color:#0d264a;" href="?page=' . ($page - 1) . '">Previous</a></li>';
                    }

                    // Numbered Pages
                    for ($i = 1; $i <= ceil($totalPreviousEvents / $perPage); $i++) {
                        echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" style="margin:10px;background-color:white;color:#0d264a;" href="?page=' . $i . '">' . $i . '</a></li>';
                    }

                    // Next Page Link
                    if ($page < ceil($totalPreviousEvents / $perPage)) {
                        echo '<li class="page-item"><a class="page-link" style="margin:10px;background-color:white;color:#0d264a;" href="?page=' . ($page + 1) . '">Next</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

    </div>
</body>
</html>
