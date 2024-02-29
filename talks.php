<?php
$apiUrl = 'https://ims.iiit.ac.in/research_apis.php?typ=getTalks';

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$json_string = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
    exit; // Terminate the script if cURL error occurs
}

curl_close($ch);

$data = json_decode($json_string, true);

if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    echo 'Error decoding JSON: ' . json_last_error_msg();
    exit; // Terminate the script if JSON decoding error occurs
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
$paged = isset($_GET['page']) ? intval($_GET['page']) : 1;

$totalUpcomingEvents = count($upcomingEvents);
$totalPreviousEvents = count($previousEvents);

$upcomingEvents = array_slice($upcomingEvents, ($paged - 1) * $perPage, $perPage);
$previousEvents = array_slice($previousEvents, ($paged - 1) * $perPage, $perPage);

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
            font-size: 12px;
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
    <?php foreach ($upcomingEvents as $event): ?>
        <div class="card col-lg-3 col-md-6 col-sm-12" style="height:auto;">
            <a href="#" data-toggle="modal" data-target="#eventModalupcoming">
                <img src="<?= isset($event['photo']) ? $event['photo'] : 'https://i.postimg.cc/cJw84dHd/45e21f7d9539e30d1d7388bac53c6813.jpg' ?>" class="img-fluid" alt="Event Image">
            </a>

            <div class="info-content">
                <div style="align-items: center;margin-top:50px;">
                    <p style="font-size:16px; font-weight:bold; color:#0d264a; margin-right: 10px;"><?= $event['title'] ?></p>
                    <p style="color:#0d264a;font-weight:500;"><?= $event['speaker'] ?></p>
                </div>
                <div style="display: flex; align-items: center; margin-top: 10px;">
                    <img src="https://cdn-icons-png.flaticon.com/128/10691/10691802.png" style="width: 15px; height: 15px; margin-right: 5px;">
                    <p class="date-content text-md-center" style="margin-right: 10px;font-size:13px;"><?= $event['displaydate'] ?></p>
                    <span style="font-size: 13px;font-weight: bold;"><?= $event['venue'] ?></span>
                </div>
                <p class="description" style="font-weight:500; font-size:15px; overflow: hidden; text-overflow: ellipsis; height: 60px;"><?= $event['description'] ?></p>
                <p class="read-more">Read more</p>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="eventModalupcoming<?= $index ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel"><?= $event['title'] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="info-content">
                    <div style="align-items: center; margin-top: 50px;">
                        <p style="color:#0d264a; font-weight: bold;"><?= $event['speaker'] ?></p>
                    </div>
                    <div style="display: flex; align-items: center; margin-top: 10px;">
                        <img src="https://cdn-icons-png.flaticon.com/128/10691/10691802.png" style="width: 15px; height: 15px; margin-right: 5px;">
                        <p class="date-content text-md-center" style="margin-right: 10px;"><?= $event['displaydate'] ?></p>
                        <span style="font-size: 12px; font-weight: bold;"><?= $event['venue'] ?></span>
                        <span style="font-size: 12px; font-weight: bold;">&nbsp;at&nbsp; <?= $event['time'] ?></span>
                    </div>
                    
                    <?php if (!empty($event['bio'])): ?>
                       <span> <p class="description1" style="font-weight:400; font-size:15px;"><b>Bio: &nbsp;</b><?= $event['bio'] ?></p></span>
                    <?php endif; ?>

                    <?php if (!empty($event['description'])): ?>
                    
                        <span><p class="description1" style="font-weight:400; font-size:15px;"><b>Description: &nbsp;</b><?= $event['description'] ?></p></span>
                    <?php endif; ?>

                    <?php if (!empty($event['abstract'])): ?>
                   
                       <span> <p class="description1" style="font-weight:400; font-size:15px;"><b>Abstract: &nbsp;</b><?= $event['abstract'] ?></p></span>
                    <?php endif; ?>

                    <img src="<?= $event['photo'] ?>" class="img-fluid" alt="Event Image">
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
                            if ($paged > 0) {
                                echo '<li class="page-item"><a class="page-link" style="margin:10px;background-color:white;color:#0d264a;" href="?page=' . ($paged - 1) . '">Previous</a></li>';
                            }

                            // Numbered Pages
                            for ($i = 1; $i <= ceil($totalUpcomingEvents / $perPage); $i++) {
                                echo '<li class="page-item ' . ($i == $paged ? 'active' : '') . '"><a class="page-link" style="margin:10px;background-color:white;color:#0d264a;" href="?page=' . $i . '">' . $i . '</a></li>';
                            }

                            // Next Page Link
                            if ($paged < ceil($totalUpcomingEvents / $perPage)) {
                                echo '<li class="page-item"><a class="page-link" style="margin:10px;background-color:white;color:#0d264a;" href="?page=' . ($paged + 1) . '">Next</a></li>';
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
                <div class="card col-lg-3 col-md-6 col-sm-12" style="flex: 0 0 calc(25% - 10px); max-width: calc(25% - 10px); margin: 5px; background-color: #ECECEC;">
    <a href="#" data-toggle="modal" data-target="#eventModalprevious<?= $index ?>">
        <div style="height: 200px; overflow: hidden;"> <!-- Set a fixed height for the image container -->
            <?php if (isset($event['photo']) && !empty($event['photo'])) : ?>
                <img src="<?= $event['photo'] ?>" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" alt="Event Image">
            <?php else : ?>
                <img src="https://i.postimg.cc/cJw84dHd/45e21f7d9539e30d1d7388bac53c6813.jpg" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" alt="Default Image">
            <?php endif; ?>
        </div>
    </a>
<br><br>
    <div class="info-content">
        <div style="align-items: center; margin-top: 10px;"> <!-- Adjusted margin-top -->
            <p style="font-size: 16px; font-weight: bold; color: #0d264a; margin-right: 10px;"><?= $event['title'] ?></p>
            <p style="color: #0d264a; font-weight: 500;"><?= $event['speaker'] ?></p>
        </div>
        <div style="display: flex; align-items: center; margin-top: 5px;"> <!-- Adjusted margin-top -->
            <img src="https://cdn-icons-png.flaticon.com/128/10691/10691802.png" style="width: 15px; height: 15px; margin-right: 5px;">
            <p class="date-content text-md-center" style="margin-right: 10px; font-size: 13px;"><?= $event['displaydate'] ?></p>
            <span style="font-size: 13px; font-weight: bold;"><?= $event['venue'] ?></span>
        </div>
        <p class="description" style="font-weight: 500; font-size: 15px; overflow: hidden; text-overflow: ellipsis; height: 60px;"><?= $event['description'] ?></p>
        <p class="read-more">Read more</p>
    </div>
</div>

                <div class="modal fade" id="eventModalprevious<?= $index ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel"><?= $event['title'] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="info-content">
                    <div style="align-items: center; margin-top: 50px;">
                        <p style="color:#0d264a; font-weight: bold;"><?= $event['speaker'] ?></p>
                    </div>
                    <div style="display: flex; align-items: center; margin-top: 10px;">
                        <img src="https://cdn-icons-png.flaticon.com/128/10691/10691802.png" style="width: 15px; height: 15px; margin-right: 5px;">
                        <p class="date-content text-md-center" style="margin-right: 10px;"><?= $event['displaydate'] ?></p>
                        <span style="font-size: 12px; font-weight: bold;"><?= $event['venue'] ?></span>
                        <span style="font-size: 12px; font-weight: bold;">&nbsp;at&nbsp; <?= $event['time'] ?></span>
                    </div>
                    
                    <?php if (!empty($event['bio'])): ?>
                        <span><p class="description1" style="font-weight:400; font-size:15px;"><b>Bio:</b> &nbsp;<?= $event['bio'] ?></p></span>
                    <?php endif; ?>

                    <?php if (!empty($event['description'])): ?>
                       <span> <p class="description1" style="font-weight:400; font-size:15px;"><b>Description:</b> &nbsp;<?= $event['description'] ?></p></span>
                    <?php endif; ?>

                    <?php if (!empty($event['abstract'])): ?>
                        <span><p class="description1" style="font-weight:400; font-size:15px;"><b>Abstract: &nbsp;</b><?= $event['abstract'] ?></p></span>
                    <?php endif; ?>

                    <img src="<?= $event['photo'] ?>" class="img-fluid" alt="Event Image">
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
        <ul class="pagination" style="margin: 10px; background-color: white; color: #0d264a; font-weight: bold;">
            <?php
            // Previous Page Link
            if ($paged > 1) {
                echo '<li class="page-item"><a class="page-link" style="margin: 10px; background-color: white; color: #0d264a;" href="?page=' . ($paged - 1) . '">Previous</a></li>';
            }

            // Numbered Pages
            for ($i = 1; $i <= ceil($totalPreviousEvents / $perPage); $i++) {
                echo '<li class="page-item ' . ($i == $paged ? 'active' : '') . '"><a class="page-link" style="margin: 10px; background-color: white; color: #0d264a;" href="?page=' . $i . '">' . $i . '</a></li>';
            }

            // Next Page Link
            if ($paged < ceil($totalPreviousEvents / $perPage)) {
                echo '<li class="page-item"><a class="page-link" style="margin: 10px; background-color: white; color: #0d264a;" href="?page=' . ($paged + 1) . '">Next</a></li>';
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
        //updateModalContent(title, imageUrl, date, venue, bio);
    });
});
function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
{
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination">';
        
        $right_links    = $current_page + 3; 
        $previous       = $current_page - 3; //previous link 
        $next           = $current_page + 1; //next link
        $first_link     = true; //boolean var to decide our first link
        
        if($current_page > 1){
			$previous_link = ($previous==0)?1:$previous;
            $pagination .= '<li class="first"><a href="#" data-page="1" title="First">«</a></li>'; //first link
            $pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous"><</a></li>'; //previous link
                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
                    }
                }   
            $first_link = false; //set first link to false
        }
        
        if($first_link){ //if current active page is first link
            $pagination .= '<li class="first active">'.$current_page.'</li>';
        }elseif($current_page == $total_pages){ //if it's the last active link
            $pagination .= '<li class="last active">'.$current_page.'</li>';
        }else{ //regular current link
            $pagination .= '<li class="active">'.$current_page.'</li>';
        }
                
        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){ 
				$next_link = ($i > $total_pages)? $total_pages : $i;
                $pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">></a></li>'; //next link
                $pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">»</a></li>'; //last link
        }
        
        $pagination .= '</ul>'; 
    }
    return $pagination; //return pagination links
}
// Function to update modal content when an image is clicked
// function updateModalContent(title, imageUrl, date, venue, bio) {
//     // Set the modal title
//     $('#eventModalLabel').text(title);

//     // Set the modal image source
//     $('#modalImage').attr('src', imageUrl);

//     // Set the modal date and venue
//     $('.date-content').text(date);
//     $('.venue-content').text(venue);

//     // Set the modal bio
//     $('.bio-content').text(bio);

//     // Show the modal
//     $('#eventModal1').modal('show');
// }

    </script>
</body>
</html>