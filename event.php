<?php

$apiUrl = 'https://ims.iiit.ac.in/research_apis.php?typ=getEvents';

$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL session and fetch the JSON string
$json_string = curl_exec($ch);

$data = json_decode($json_string, true);

if ($data === null) {
    die('Error decoding JSON');
}

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
            margin: 10px;
            margin-left: 10px;
            margin-right: 10px;
            position: relative;
            overflow: hidden;
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
            height: 150px; /* Set a fixed height for the description */
            overflow: hidden;
        }

        .read-more {
            color: #0d264a;
            cursor: pointer;
            display: none; /* Initially hide "Read more" */
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
                        echo '
                        <div class="card col-lg-3 col-md-4 col-sm-6" style="background-color:#ECECEC; height:450px;">
                            <a href="' . $event['url'] . '">
                                <img src="' . $event['photo'] . '" class="img-fluid" alt="Event Image">
                            </a>
                            <div>
                                <p class="date-content text-md-center">' . $month . '<br>' . $day . '</p>
                            </div>
                            <div class="info-content ">
                                <p style="font-size:16px; font-weight:bold; color:#0d264a">' . $event['title'] . '</p>
                                <p class="content" style="font-weight:400; font-size:13px; overflow: hidden; text-overflow: ellipsis; height: 60px;">' . $event['description'] . '</p>
                                <p class="read-more">Read more</p>
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
                        echo '
                        <div class="card col-lg-3 col-md-4 col-sm-6" style="background-color:#ECECEC;height:450px;">
                            <a href="' . $event['url'] . '">
                                <img src="' . $event['photo'] . '" class="img-fluid" alt="Event Image">
                            </a>
                            <div>
                                <p class="date-content text-md-center">' . $month . '<br>' . $day . '</p>
                            </div>
                            <div class="info-content ">
                                <p style="font-size:16px; font-weight:bold; color:#0d264a">' . $event['title'] . '</p>
                                <p class="content" style="font-weight:400; font-size:13px; overflow: hidden; text-overflow: ellipsis; height: 60px;">' . $event['description'] . '</p>
                                <p class="read-more">Read more</p>
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
      
<script>
   
    $(document).ready(function () {
    $('.info-content').each(function () {
        var $title = $(this).find('p[style="font-size:13px;font-weight:400;  color:#0d264a; margin-right: 10px;"]');
        var $description = $(this).find('p.content');

        var titleText = $title.text();
        var descriptionText = $description.text();

        // Log the length of the description text to the console
        console.log('Description Text Length:', descriptionText.length);

        var maxLength = 60;

        if (descriptionText.length > maxLength) {
            var shortText = descriptionText.substring(0, maxLength) + '...';
            $description.text(shortText);

            var readMoreLink = $('<p class="read-more" style="color: #0d264a; cursor: pointer;">Read more</p>');
            readMoreLink.click(function (e) {
                e.preventDefault();
                $description.text(descriptionText);
                $(this).hide();
            });

            $(this).append(readMoreLink);
        }
    });
});
</script>





</body>
</html>
