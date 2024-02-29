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
                <h2>Researchers</h2>
                <br>
               <div class="row">
               <?php
foreach ($upcomingEvents as $event) {
   

    // Check if photo is null or empty
    $photoSrc = (!empty($event['photo']) ? htmlspecialchars($event['photo']) : 'https://research.iiit.ac.in/wp-content/uploads/2024/01/images-1-150x150-1.png');

    echo '
    <div class="card col-lg-3 col-md-6 col-sm-12" style="flex: 0 0 calc(25% - 10px); max-width: calc(25% - 10px); margin: 5px; background-color: #ECECEC;">
        <a href="' . $event['link'] . '">
            <div style="height: 200px; overflow: hidden;"> <!-- Set a fixed height for the image container -->
                <img src="' . $photoSrc . '" class="img-fluid" alt="Event Image" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </a>
        <div>
        <p>'.$event['name'].'</p>
        <div class="line"></div>
        <p style="font-size:16px; font-weight:bold; color:#0d264a">' . $event['Research Center'] . '</p>
        </div>
      </div>';
}
?>

    
</div>

              
            </div>
        </div>

    </div>
</body>
</html>
