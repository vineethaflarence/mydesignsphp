<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Cinzel+Decorative&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Publications Accordions</title>
    <link href="https://fonts.googleapis.com/css2?family=Aleo:wght@900&display=swap" rel="stylesheet">
    <!-- Include your preferred CSS framework or styles for accordion here -->
    <style>
        .accordions-year {
            margin: 20px;
        }

        .accordion {
            margin:10px;
            background-color: #daf3f5;
            color: black !important;
            text-decoration: none !important;
            text-align: center;
        }

        .card {
          
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
 
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
            display: flex;
            top:50%;
            align-items: center;
       }
            
        
        .card  .card-header {
            background-color: #daf3f5;
            color: white;
        }
        .card.active .card-header {
            background-color: #f3da66;
            color: white;
        }
      

        
        .table {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }

        .icon {
    position: absolute;
    top: 50%;
    right: 1rem; /* Adjust as needed */
    transform: translateY(-50%);
    font-weight: bold;
    color: black;
    text-align: center;
    font-size: 18px; /* Adjust the font size as needed */
}
        .collapsed .icon {
            transform: rotate(90deg);
        }

        th {
            background-color: #086382;
            color: white;
            
        }
        .table tbody tr:nth-child(even) {
    background-color: #daf3f5;
}
.table tbody tr:nth-child(odd) {
    background-color: white;
}

/* .btn-link:focus, */
.btn-link:active,
.btn-link:hover {
    outline: none;
    border: none;
}
.card-header a {
    font-family: Aleo, serif;
    color: black;
    font-size: 20px;
    font-weight: bold;
    text-decoration: none;
    margin-bottom: 0; /* Remove default margin */
    display: flex;
    align-items: center; /* Center vertically */
    justify-content: center; /* Center horizontally */
}
@media (max-width: 768px) {
    .icon {
        right: 0.5rem; /* Adjust for smaller screens */
        font-size: 14px;
    }
}
    </style>
</head>

<body>

    <div class="container">
        <div class="accordions-year">
            <?php

            $api_url = "https://ims.iiit.ac.in/research_apis.php?typ=getPublications";
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

            if ($data && is_array($data)) {
                // Filter out non-numeric keys (assuming years are represented as numeric)
                $numericYears = array_filter(array_keys($data), 'is_numeric');

                // Sort the years if needed
                rsort($numericYears);

                foreach ($numericYears as $year) {
                    echo '<div class="accordion" id="accordion_' . $year . '">
                            <div class="card" onclick="toggleAccordion(this)">
                                <div class="card-header position-relative" id="heading_' . $year . '">
                                    <h2 class="mb-0">
                                        <a href=""  data-toggle="collapse" style="font-family: Aleo, serif;color:black;font-size:20px;font-weight:bold;text-decoration:none;" data-target="#collapse_' . $year . '" aria-expanded="true" aria-controls="collapse_' . $year . '">
                                            ' . $year . '
                                        </a>
                                        <i class="fa fa-angle-right icon"></i>
                                    </h2>
                                </div>
                                <div id="collapse_' . $year . '" class="collapse" aria-labelledby="heading_' . $year . '" data-parent="#accordion_' . $year . '">
                                    <div class="card-body">';

                    // Check if data for the year exists
                    if (isset($data[$year])) {
                        echo '<table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Authors</th>
                                        <!-- Add more table headers for other fields as needed -->
                                    </tr>
                                </thead>
                                <tbody>';

                        foreach ($data[$year] as $publication) {
                            echo '<tr>';
                            echo '<td>' . $publication['Title'] . '</td>';
                            echo '<td>' . $publication['Type'] . '</td>';
                            //echo '<td>' . $publication['Core'] . '</td>';
                            echo '<td>' . $publication['Authors'] . '</td>';
                            // Add more table cells for other fields as needed
                            echo '</tr>';
                        }

                        echo '</tbody></table>';
                    } else {
                        echo 'No publications available for ' . $year;
                    }

                    echo '</div>
                                </div>
                            </div>
                        </div>';
                }
            } else {
                echo "Invalid or missing data in the API response.";
            }
            ?>
        </div>
    </div>

    <script>
        function toggleAccordion(element) {
            $(element).toggleClass("active");
            $(element).find('.card').toggleClass("active");
        }

        $(document).ready(function () {
            $('.collapse').on('shown.bs.collapse', function () {
                $(this).prev().find('.icon').removeClass('fa-angle-right').addClass('fa-angle-down');
            });

            $('.collapse').on('hidden.bs.collapse', function () {
                $(this).prev().find('.icon').removeClass('fa-angle-down').addClass('fa-angle-right');
            });
        });
    </script>

    <!-- Include your preferred JavaScript framework or scripts for accordion here -->

</body>

</html>