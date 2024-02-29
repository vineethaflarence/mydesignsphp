<?php
$apiUrl = 'https://ims-dev.iiit.ac.in/research_apis.php?typ=getEvents';

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

$items_per_page_previous = 4;
$total_pages_previous = ceil(count($previousEvents) / $items_per_page_previous);
$current_page_previous = isset($_GET['paged']) ? max(1, min($total_pages_previous, (int)$_GET['paged'])) : 1;

$offset_previous = ($current_page_previous - 1) * $items_per_page_previous;
$paged_previous_events = array_slice($previousEvents, $offset_previous, $items_per_page_previous);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add your CSS and JS links here -->

    <title>Student Template</title>

    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
    <div class="container">
        <!-- Previous Talks -->
        <div class="row">
            <div class="col-12 m-3 mb-3">
                <h2>Previous Talks</h2>
                <br>

                <?php
                if (!empty($paged_previous_events)) {
                    // Display previous events
                    echo '<div class="row">';
                    foreach ($paged_previous_events as $index => $event):
                ?>
                    <div class="card col-lg-3 col-md-6 col-sm-12" style="flex: 0 0 calc(25% - 10px); max-width: calc(25% - 10px); margin: 5px; background-color: #ECECEC;">
                        <a href="#" data-toggle="modal" data-target="#eventModalprevious<?= $index ?>">
                            <img src="<?= !empty($event['photo']) ? $event['photo'] : 'https://i.postimg.cc/cJw84dHd/45e21f7d9539e30d1d7388bac53c6813.jpg' ?>" class="img-fluid" width="300" height="200" alt="Event Image">
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
                    <div class="modal fade" id="eventModalprevious<?= $index ?>">
                        <div class="modal-dialog" role="document">
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
                                            <p style="color:#0d264a; font-weight: bold;"><?= $event['speaker'] ?></p>
                                        </div>
                                        <div style="display: flex; align-items: center; margin-top: 10px;">
                                            <img src="https://cdn-icons-png.flaticon.com/128/10691/10691802.png" style="width: 15px; height: 15px; margin-right: 5px;">
                                            <p class="date-content text-md-center" style="margin-right: 10px;"><?= $event['displaydate'] ?></p>
                                            <span style="font-size: 12px; font-weight: bold;"><?= $event['venue'] ?></span>
                                            <span style="font-size: 12px; font-weight: bold;">&nbsp;at&nbsp; <?= $event['time'] ?></span>
                                        </div>
                                        
                                        <?php if (!empty($event['bio'])): ?>
                                            <span><p class="description1" style="font-weight:400; font-size:15px;"><b>Bio: &nbsp;</b><?= $event['bio'] ?></p></span>
                                        <?php endif; ?>

                                        <?php if (!empty($event['description'])): ?>
                                            <span><p class="description1" style="font-weight:400; font-size:15px;"><b>Description: &nbsp;</b><?= $event['description'] ?></p></span>
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

                <!-- Previous Talks Pagination -->
                <div id="previous-pagination-container" class="text-center">
                    <?php
                    echo '<div class="previous-pagination-container">';
                    // Output pagination links manually
                    for ($i = 1; $i <= $total_pages_previous; $i++) {
                        echo '<a class="page-numbers ' . ($i == $current_page_previous ? 'current' : '') . '" href="?paged=' . $i . '">' . $i . '</a>';
                    }
                    echo '</div>';
                    ?>
                </div>
                
                <?php } else {
                    // No previous events message
                    echo '<p>No previous events.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
