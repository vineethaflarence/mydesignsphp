<?php
$apiUrl = 'https://ims-dev.iiit.ac.in/research_apis.php?typ=getTalks';

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$json_string = curl_exec($ch);
$data = json_decode($json_string, true);

if ($data === null) {
    die('Error decoding JSON');
}

$currentDate = date('Y-m-d');
$currentYear = date('Y');
$upcomingEvents = [];
$previousEvents = [];

foreach ($data as $event) {
    $eventStartDate = $event['startdate'];
    $eventEndDate = $event['enddate'];

    if ($eventEndDate >= $currentDate) {
        if ($eventStartDate >= $currentDate && date('Y', strtotime($eventStartDate)) == $currentYear) {
            $upcomingEvents[] = $event;
        } else {
            $previousEvents[] = $event;
        }
    } else {
        $previousEvents[] = $event;
    }
}

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
    position: relative;
    overflow: hidden;
    height: auto;
    border: none; /* Remove the border */
    border-radius: 0; /* Remove border-radius if not needed */
    box-shadow: none; /* Remove box-shadow if not needed */
}

        img {
            width: 100%;
            display: block; 
        }

        .date-content {
            color: black;
            font-size: 15px;
            font-weight: bold;
            padding: 5px;
            border-radius: 5px;
        }

        .info-content {
            padding: 10px;
            position: relative;
            top: -50px;
            width: 100%;
            box-sizing: border-box;
        }

        .info-content p {
            margin: 0;
        }

        .read-more {
            color: #0d264a;
            cursor: pointer;
            display: none;
        }
        .modal-dialog
{
    width: 100%;
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
                <h2>Upcoming Talks</h2>
                <br>
                <div class="row">
    <?php foreach ($upcomingEvents as $index => $event): ?>
        <div class="card col-lg-3 col-md-6 col-sm-12" style="height:auto;">
            <a href="#" data-toggle="modal" data-target="#eventModal<?= $index ?>">
                <img src="<?= isset($event['photo']) && $event['photo'] !== '' ? $event['photo'] : 'https://i.postimg.cc/fbXjGPgp/45e21f7d9539e30d1d7388bac53c6813.jpg' ?>" class="img-fluid" alt="Event Image">
            </a>

            <div class="info-content">
                <div style="align-items: center; margin-top: 50px;">
                    <p style="font-size:16px; font-weight:bold; color:#0d264a; margin-right: 10px;"><?= $event['title'] ?></p>
                    <p style="color:#0d264a;"><?= $event['speaker'] ?></p>
                </div>
                <div style="display: flex; align-items: center; margin-top: 10px;">
                    <img src="https://cdn-icons-png.flaticon.com/128/10691/10691802.png" style="width: 15px; height: 15px; margin-right: 5px;">
                    <p class="date-content text-md-center" style="margin-right: 10px;"><?= $event['displaydate'] ?></p>
                    <span style="font-size: 15px;font-weight: bold;"><?= $event['venue'] ?></span>
                </div>
                <p class="description" style="font-weight:500; font-size:15px; overflow: hidden; text-overflow: ellipsis; height: 60px;"><?= $event['description'] ?></p>
                <p class="read-more">Read more</p>
            </div>
        </div>
        <div class="modal fade" id="eventModal<?= $index ?>">
    <!-- Modal Content -->
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel"><?= $event['title'] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="info-content">
                    <div style="align-items: center;margin-top:50px;">
                        <p style="color:#0d264a;font-weight:bold;"><?= $event['speaker'] ?></p>
                    </div>
                    <div style="display: flex; align-items: center; margin-top: 10px;">
                        <img src="https://cdn-icons-png.flaticon.com/128/10691/10691802.png" style="width: 15px; height: 15px; margin-right: 5px;">
                        <p class="date-content text-md-center" style="margin-right: 10px;"><?= $event['displaydate'] ?></p>
                        <span style="font-size: 15px;font-weight: bold;"><?= $event['venue'] ?></span>
                        <?php if (isset($event['time']) && $event['time'] !== ''): ?>
                            <span style="font-size: 15px;font-weight: bold;">&nbsp;at&nbsp; <?= $event['time'] ?></span>
                        <?php endif; ?>
                         
                    </div>
                    <p style="font-weight:bold;"> <?= $event['startdate'] ?> - <span><?= $event['enddate'] ?></span> </p>
                    <?php if (isset($event['bio']) && $event['bio'] !== ''): ?>
                        <p><b>Bio of the Speaker: </b><?= $event['bio'] ?></p>
                    <?php endif; ?>
                    <?php if (isset($event['abstract']) && $event['abstract'] !== ''): ?>
                        <p><b>Abstract: </b><?= $event['abstract'] ?></p> 
                    <?php endif; ?>
                    <?php if (isset($event['description']) && $event['description'] !== ''): ?>
                        <p><b>Description: </b><?= $event['description'] ?></p>
                    <?php endif; ?>
<br>
                    <img src="<?= isset($event['photo']) && $event['photo'] !== '' ? $event['photo'] : 'https://i.postimg.cc/fbXjGPgp/45e21f7d9539e30d1d7388bac53c6813.jpg' ?>" class="img-fluid custom-modal-photo" alt="Event Image">
                </div>
            </div>
        </div>
    </div>
</div>

    <?php endforeach; ?>
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
            for ($i = 1; $i <= ceil($totalUpcomingEvents / $perPage); $i++) {
                echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" style="margin:10px;background-color:white;color:#0d264a;" href="?page=' . $i . '">' . $i . '</a></li>';
            }

            // Next Page Link
            if ($page < ceil($totalUpcomingEvents / $perPage)) {
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
                <h2>Previous Talks</h2>
                <br>
            <div class="row">
           <?php foreach ($previousEvents as $index => $event): ?>
        <div class="card col-lg-3 col-md-6 col-sm-12" style="height:auto;">
            <a href="#" data-toggle="modal" data-target="#eventModalprev<?= $index ?>">
            <img src="<?= isset($event['photo']) && $event['photo'] !== '' ? $event['photo'] : 'https://i.postimg.cc/fbXjGPgp/45e21f7d9539e30d1d7388bac53c6813.jpg' ?>" class="img-fluid" alt="Event Image">
            </a>

            <div class="info-content">
                <div style="align-items: center; margin-top: 50px;">
                    <p style="font-size:16px; font-weight:bold; color:#0d264a; margin-right: 10px;"><?= $event['title'] ?></p>
                    <p style="color:#0d264a;"><?= $event['speaker'] ?></p>
                </div>
                <div style="display: flex; align-items: center; margin-top: 10px;">
                    <img src="https://cdn-icons-png.flaticon.com/128/10691/10691802.png" style="width: 15px; height: 15px; margin-right: 5px;">
                    <p class="date-content text-md-center" style="margin-right: 10px;"><?= $event['displaydate'] ?></p>
                    <span style="font-size: 15px;font-weight: bold;"><?= $event['venue'] ?></span>
                </div>
                <p class="description" style="font-weight:500; font-size:15px; overflow: hidden; text-overflow: ellipsis; height: 60px;"><?= $event['description'] ?></p>
                <p class="read-more">Read more</p>
            </div>
        </div>
<div class="modal fade" id="eventModalprev<?= $index ?>">
    <!-- Modal Content -->
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel"><?= $event['title'] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="info-content">
                    <div style="align-items: center;margin-top:50px;">
                        <p style="color:#0d264a;font-weight:bold;"><?= $event['speaker'] ?></p>
                    </div>
                    <div style="display: flex; align-items: center; margin-top: 10px;">
                        <img src="https://cdn-icons-png.flaticon.com/128/10691/10691802.png" style="width: 15px; height: 15px; margin-right: 5px;">
                        <p class="date-content text-md-center" style="margin-right: 10px;"><?= $event['displaydate'] ?></p>
                        <span style="font-size: 15px;font-weight: bold;"><?= $event['venue'] ?></span>
                        <?php if (isset($event['time']) && $event['time'] !== ''): ?>
                            <span style="font-size: 15px;font-weight: bold;">&nbsp;at&nbsp; <?= $event['time'] ?></span>
                        <?php endif; ?>
                        <br>
                        
                    </div>
                    <p style="font-weight:bold;"> <?= $event['startdate'] ?> - <span><?= $event['enddate'] ?></span> </p>
                    <?php if (isset($event['bio']) && $event['bio'] !== ''): ?>
                        <p><b>Bio of the Speaker: </b><?= $event['bio'] ?></p>
                    <?php endif; ?>
                    
                    <?php if (isset($event['abstract']) && $event['abstract'] !== ''): ?>
                        <p><b>Abstract: </b><?= $event['abstract'] ?></p>
                    <?php endif; ?>
                
                    <?php if (isset($event['description']) && $event['description'] !== ''): ?>
                        <p><b>Description: </b><?= $event['description'] ?></p>
                    <?php endif; ?>
<br>
                    <img src="<?= isset($event['photo']) && $event['photo'] !== '' ? $event['photo'] : 'https://i.postimg.cc/fbXjGPgp/45e21f7d9539e30d1d7388bac53c6813.jpg' ?>" class="img-fluid custom-modal-photo" alt="Event Image">
                </div>
            </div>
        </div>
    </div>
</div>
    <?php endforeach; ?>
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
    $('.info-content .description').each(function () {
        var $description = $(this);
        var maxLength = 50; // Set your desired max length for description

        if ($description.text().length > maxLength) {
            var shortText = $description.text().substring(0, maxLength) + '...';
            $description.text(shortText);

            var readMoreLink = $('<p class="read-more">Read more</p>');
            readMoreLink.click(function () {
                $description.text($description.data('full-text')); // Use data attribute to store the full text
                $(this).hide();
            });

            $description.data('full-text', $description.text()); // Store the full text
            $description.after(readMoreLink);
        }
    });

    // Attach click event to images
    $('.event-image').click(function () {
        // Get the data attributes from the clicked image
        var title = $(this).data('title');
        var imageUrl = $(this).data('image');
        var date = $(this).data('date');
        var venue = $(this).data('venue');
        var bio = $(this).data('bio');

        // Update modal content with the clicked image data
        updateModalContent(title, imageUrl, date, venue, bio);
    });
});

// Function to update modal content when an image is clicked
function updateModalContent(title, imageUrl, date, venue, bio) {
    // Set the modal title
    $('#eventModalLabel').text(title);

    // Set the modal image source
    $('#modalImage').attr('src', imageUrl);

    // Set the modal date and venue
    $('.date-content').text(date);
    $('.venue-content').text(venue);

    // Set the modal bio
    $('.bio-content').text(bio);

    // Show the modal
    $('#eventModal1').modal('show');
}

    </script>
                </body>
                </html>