<?php
/*
* Template Name: page Event
*/
$apiUrl = 'https://ims-dev.iiit.ac.in/research_apis.php?typ=getEvents';
$response = wp_remote_get($apiUrl);

if (is_array($response) && !is_wp_error($response)) {
    $api_data = json_decode(wp_remote_retrieve_body($response), true);

    if (!empty($api_data)) {
        echo '<div class="container"><div class="row">';

        foreach ($api_data as $event) {
            echo '<div class="card col-lg-3 col-md-3 col-sm-12"> 
                    <img src="' . $event['photo'] . '" style="width:100%;height:200px;" class="img-fluid" alt="Event Image">
                    <div class="date-content btn btn-primary " style="width:50px;height:50px;text-align:center;font-size: 12px;font-weight:bold;margin-bottom:-30px;">
                        <p>' . $event['startdate'] . '</p>
                        <p>' . $event['enddate'] . '</p>
                    </div>
                    <div class="info-content">
                        <p style="font-size:20px;font-weight:bold">' . $event['title'] . '</p>
                        <p>' . $event['description'] . '</p>
                    </div>
                  </div>';
        }

        echo '</div></div>';
    } else {
        echo 'No events found.';
    }
} else {
    echo 'Error fetching API data.';
}

