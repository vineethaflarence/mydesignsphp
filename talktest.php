<?php
/*
Template Name: Talks
*/
?>

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

$items_per_page_upcoming = 4;
$total_pages_upcoming = ceil(count($upcomingEvents) / $items_per_page_upcoming);
$current_page_upcoming = max(1, get_query_var('paged'));

$offset_upcoming = ($current_page_upcoming - 1) * $items_per_page_upcoming;
$paged_upcoming_events = array_slice($upcomingEvents, $offset_upcoming, $items_per_page_upcoming);

$items_per_page_previous = 4;
$total_pages_previous = ceil(count($previousEvents) / $items_per_page_previous);
$current_page_previous = max(1, get_query_var('paged'));

$offset_previous = ($current_page_previous - 1) * $items_per_page_previous;
$paged_previous_events = array_slice($previousEvents, $offset_previous, $items_per_page_previous);
?>

<?php get_header(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
	<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <title>Student Template</title>

    
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

		.card-body{
	
position:relative;
bottom:15%;
		}

        .info-content {
            padding: 10px;
            position: relative;
            top: -50px;
            width: 100%;
            box-sizing: border-box;
        }
		.time{
		font-size: 12px; font-weight: bold;postion:sticky;
		}
		

        .info-content p {
            margin: 0;
        }

        .read-more {
            color: #0d264a;
            cursor: pointer;
            display: none;
        }
        .custom-modal-dialog {
        max-width: 800px; /* Adjust the width as needed */
    }

        @media (max-width: 767px) {
            .date-content {
                position: relative;
                text-align: center;
            }
        }
.pagination-container {
         text-align: center;
         margin-top: 20px;
      }

      /* Style for Upcoming Events Pagination */
      .upcoming-pagination-container {
         display: inline-block;
      }

      .upcoming-pagination-container .page-numbers {
         display: inline-block;
         margin: 0 5px;
         padding: 8px 12px;
         border: 1px solid #0d264a;
         border-radius: 4px;
         text-decoration: none;
         color: #0d264a;
      }

      .upcoming-pagination-container .page-numbers.current {
         background-color: #0d264a;
         color: #fff;
         border: 1px solid #0d264a;
      }

      /* Style for Previous Events Pagination */
      .previous-pagination-container {
         display: inline-block;
      }

      .previous-pagination-container .page-numbers {
         display: inline-block;
         margin: 0 5px;
         padding: 8px 12px;
         border: 1px solid #0d264a;
         border-radius: 4px;
         text-decoration: none;
         color: #0d264a;
      }

      .previous-pagination-container .page-numbers.current {
         background-color: #0d264a;
         color: #fff;
         border: 1px solid #0d264a;
      }
    </style>
</head>
<body>
    <div class="container">
	<!--Upcoming Event!-->
	       <div class="row">
            <div class="col-12 m-3 mb-3">
                <h2>Upcoming Talks</h2>
                <br>
				
    <?php 
if (!empty($paged_upcoming_events)) {
            // Display upcoming events
			
            echo '<div class="row">';
            foreach ($paged_upcoming_events as $index => $event):
			$dateComponents = explode('-', $event['displaydate']);
    [$month, $day] = $dateComponents;

        ?>
		<div class="card " style=" border:1px solid grey;background-color:#ECECEC;width:16rem;height:25rem;">
    <a href="#" data-toggle="modal" data-target="#eventModalupcoming<?= $index ?>">
		<!-- Set a fixed height for the image container -->
		<img src="<?= !empty($event['photo']) ? $event['photo'] : 'https://i.postimg.cc/9fBD7KdL/Talks.jpg' ?>" class="card-img-top " style="height:200px;" alt="Card image cap" >
    </a>
<div class="card-body">
 <h5 style="font-size:14px; font-weight:bold; color:#0d264a;margin-top:50px;"><?= $event['title'] ?>-<p style="color:#0d264a;font-weight:500;font-size:12px;"><?= $event['speaker'] ?></p></h5>
<div style="display: flex;">
                        <img src="https://i.postimg.cc/6pPX109B/icons8-calendar-48.png" style="width: 30px; height: 20px;margin-right:5px;">
                        <p class="date-content1 text-md-center" style="font-size:11px;font-weight:bold;"><?= $event['displaydate'] ?> &nbsp; <?= $event['venue'] ?></p>
                    </div>   
<!--<p class="time"><?= $event['time'] ?></p>		!-->			
    <?php 
        $description = $event['description'];
        $maxLength = 100; // Maximum length of the description before truncation
        if (strlen($description) > $maxLength) {
            $shortDescription = substr($description, 0, $maxLength) . '...';
        } else {
            $shortDescription = $description;
        }
    ?>
    
    <p style="font-weight:500; font-size:12px;"><?= $shortDescription ?></p>
    
    <?php if (strlen($description) > $maxLength): ?>
        <p class="read-more">Read more</p>
    <?php endif; ?>
</div>
</div>


        <!-- Modal -->
        <div class="modal fade" id="eventModalupcoming<?= $index ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalupcoming<?= $index ?>"><?= $event['title'] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="info-content">
                    <div style="align-items: center; margin-top: 50px;">
                        <p style="color:#0d264a; font-weight: bold;"><?= $event['speaker'] ?></p>
                    </div>
                    <div style="display: flex;">
                        <img src="https://i.postimg.cc/9fBD7KdL/Talks.jpg" style="width: 30px; height: 20px;margin-right:5px;">
                        
						<br><p class="date-content1 text-md-center" style="font-size:12px;font-weight:bold;"><?= $event['displaydate'] ?> &nbsp; <?= $event['venue'] ?>&nbsp; <?= $event['time'] ?></p>
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

                    <img src="<?= !empty($event['photo']) ? $event['photo'] : 'https://i.postimg.cc/5ydnZDBz/talk-2.png' ?>" class="img-fluid" width="200" height="200" alt="Event Image">
                </div>
            </div>
        </div>
    </div>
</div>

    <?php endforeach; 
echo '</div>';
//Pagination for Upcoming Events
            echo '<div class="pagination-container upcoming-pagination-container">';
            echo paginate_links(array(
                'total' => $total_pages_upcoming,
                'current' => $current_page_upcoming,
            ));
            echo '</div>';
        } else {
            // No upcoming events message
            echo '<p>No upcoming events.</p>';
        }
        ?>
		
				<?php wp_reset_postdata(); ?>
</div>
</div>
<!--Previous Event!-->
        <div class="row">
            <div class="col-12 m-3 mb-3">
                <h2>Previous Talks</h2>
                <br>
                
				<?php
				
if (!empty($paged_previous_events)) {
   // Display previous events
            echo '<div class="row">';
                foreach ($paged_previous_events as $index => $event):
				$dateComponents = explode('-', $event['displaydate']);
    [$month, $day] = $dateComponents;

         ?>
		<div class="card " style="background-color:#ECECEC;width:16rem;height:25rem;border:1px solid grey;">
    <a href="#" data-toggle="modal" data-target="#eventModalprevious<?= $index ?>">
		<!-- Set a fixed height for the image container -->
		<img src="<?= !empty($event['photo']) ? $event['photo'] : 'https://i.postimg.cc/9fBD7KdL/Talks.jpg' ?>" class="card-img-top " style="height:200px;" alt="Card image cap" >
    </a>
<div class="card-body">
 <h5 style="font-size:14px; font-weight:bold; color:#0d264a;margin-top:50px;"><?= $event['title'] ?>-<p style="color:#0d264a;font-weight:500;font-size:12px;"><?= $event['speaker'] ?></p></h5>
<div style="display: flex;">
                        <img src="https://i.postimg.cc/6pPX109B/icons8-calendar-48.png" style="width: 30px; height: 20px;margin-right:5px;">
                        <p class="date-content1 text-md-center" style="font-size:11px;font-weight:bold;"><?= $event['displaydate'] ?> &nbsp; <?= $event['venue'] ?></p>
                    </div>   
<!--<p class="time"><?= $event['time'] ?></p>		!-->			
    <?php 
        $description = $event['description'];
        $maxLength = 100; // Maximum length of the description before truncation
        if (strlen($description) > $maxLength) {
            $shortDescription = substr($description, 0, $maxLength) . '...';
        } else {
            $shortDescription = $description;
        }
    ?>
    
    <p style="font-weight:500; font-size:12px;"><?= $shortDescription ?></p>
    
    <?php if (strlen($description) > $maxLength): ?>
        <p class="read-more">Read more</p>
    <?php endif; ?>
</div>
</div>


        <!-- Modal -->
        <div class="modal fade" id="eventModalprevious<?= $index ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalprevious<?= $index ?>"><?= $event['title'] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="info-content">
                    <div style="align-items: center; margin-top: 50px;">
                        <p style="color:#0d264a;"><b>Startdate: </b><?= $event['startdate'] ?> &nbsp; - &nbsp; <b>Enddate: </b><?= $event['enddate'] ?></p> 
					
                    </div>
                     <div style="display: flex;">
                        <img src="https://i.postimg.cc/3JwL0M8K/icons8-calendar-48.png" style="width: 30px; height: 20px;margin-right:5px;">
                        
						<br><p class="date-content1 text-md-center" style="font-size:12px;font-weight:bold;"><?= $event['displaydate'] ?> &nbsp; <?= $event['venue'] ?>&nbsp; <?= $event['time'] ?></p>
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

                   <img src="<?= !empty($event['photo']) ? $event['photo'] : 'https://i.postimg.cc/9fBD7KdL/Talks.jpg' ?>" class="img-fluid" alt="Event Image">
                </div>
            </div>
        </div>
    </div>
</div>

    
    <?php endforeach; 
echo '</div>';
//Pagination for Previous Events
            echo '<div class="pagination-container previous-pagination-container">';
echo paginate_links(array(
    'total' => $total_pages_previous,
    'current' => $current_page_previous,
));
echo '</div>';
        } else {
            // No previous events message
            echo '<p>No upcoming events.</p>';
        }
        ?>
		
				<?php wp_reset_postdata(); ?>
</div>
</div>
               
            
    
</div>
</div>
   <script>
   var myModal = new bootstrap.Modal(document.getElementById('eventModalprevious'));
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
function paginate_upcoming_events(items_per_page_upcoming, current_page_upcoming, total_records_upcoming, total_pages_upcoming) {
    var pagination = '';
    if (total_pages_upcoming > 0 && total_pages_upcoming != 1 && current_page_upcoming <= total_pages_upcoming) {
        pagination += '<ul class="pagination">';

        var right_links = current_page_upcoming + 3;
        var previous = current_page_upcoming - 3;
        var next = current_page_upcoming + 1;

        if (current_page_upcoming > 1) {
            var previous_link = (previous == 0) ? 1 : previous;
            pagination += '<li class="first"><a href="#" data-page="1" title="First">«</a></li>';
            pagination += '<li><a href="#" data-page="' + previous_link + '" title="Previous"><</a></li>';
            for (var i = (current_page_upcoming - 2); i < current_page_upcoming; i++) {
                if (i > 0) {
                    pagination += '<li><a href="#" data-page="' + i + '" title="Page' + i + '">' + i + '</a></li>';
                }
            }
        }

        if (current_page_upcoming == 1) {
            pagination += '<li class="first active">' + current_page_upcoming + '</li>';
        } else if (current_page_upcoming == total_pages_upcoming) {
            pagination += '<li class="last active">' + current_page_upcoming + '</li>';
        } else {
            pagination += '<li class="active">' + current_page_upcoming + '</li>';
        }

        for (var i = current_page_upcoming + 1; i < right_links; i++) {
            if (i <= total_pages_upcoming) {
                pagination += '<li><a href="#" data-page="' + i + '" title="Page ' + i + '">' + i + '</a></li>';
            }
        }

        if (current_page_upcoming < total_pages_upcoming) {
            var next_link = (i > total_pages_upcoming) ? total_pages_upcoming : i;
            pagination += '<li><a href="#" data-page="' + next_link + '" title="Next">></a></li>';
            pagination += '<li class="last"><a href="#" data-page="' + total_pages_upcoming + '" title="Last">»</a></li>';
        }

        pagination += '</ul>';
    }
    return pagination;
}

function paginate_previous_events(items_per_page_previous, current_page_previous, total_records_previous, total_pages_previous) {
    var pagination = '';
    if (total_pages_previous > 0 && total_pages_previous != 1 && current_page_previous <= total_pages_previous) {
        pagination += '<ul class="pagination">';

        var right_links = current_page_previous + 3;
        var previous = current_page_previous - 3;
        var next = current_page_previous + 1;

        if (current_page_previous > 1) {
            var previous_link = (previous == 0) ? 1 : previous;
            pagination += '<li class="first"><a href="#" data-page="1" title="First">«</a></li>';
            pagination += '<li><a href="#" data-page="' + previous_link + '" title="Previous"><</a></li>';
            for (var i = (current_page_previous - 2); i < current_page_previous; i++) {
                if (i > 0) {
                    pagination += '<li><a href="#" data-page="' + i + '" title="Page' + i + '">' + i + '</a></li>';
                }
            }
        }

        if (current_page_previous == 1) {
            pagination += '<li class="first active">' + current_page_previous + '</li>';
        } else if (current_page_previous == total_pages_previous) {
            pagination += '<li class="last active">' + current_page_previous + '</li>';
        } else {
            pagination += '<li class="active">' + current_page_previous + '</li>';
        }

        for (var i = current_page_previous + 1; i < right_links; i++) {
            if (i <= total_pages_previous) {
                pagination += '<li><a href="#" data-page="' + i + '" title="Page ' + i + '">' + i + '</a></li>';
            }
        }

        if (current_page_previous < total_pages_previous) {
            var next_link = (i > total_pages_previous) ? total_pages_previous : i;
            pagination += '<li><a href="#" data-page="' + next_link + '" title="Next">></a></li>';
            pagination += '<li class="last"><a href="#" data-page="' + total_pages_previous + '" title="Last">»</a></li>';
        }

        pagination += '</ul>';
    }
    return pagination;
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
<?php get_footer(); ?>
