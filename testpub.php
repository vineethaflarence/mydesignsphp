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
    <style>
        .container{
            margin-top:20px;
        }
        .dropdowns {
            margin: 20px;
        }

        .heading {
            font-weight: bold;
            font-size:25px;
            margin:20px;
        }

        .list {
            color: #0f1333;
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 0; /* Remove bottom margin */
        }
        
        /* Remove default list styling */
        ol, p {
            padding-left: 0;
            margin-bottom: 0;
        }
        
        ol li {
            margin-bottom: 20px; /* Add space between list items */
        }
    </style>
</head>

<body>
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
        // Extract unique years
        $years = array_keys($data);

        // Sort the years in descending order
        rsort($years);

        // Set initial values for year and author selection
        $selectedYear = isset($_GET['year']) ? $_GET['year'] : '';
        $selectedAuthor = isset($_GET['author']) ? $_GET['author'] : '';

        // Display the form with dropdowns and submit button
        echo '<div class="container">';
        echo '<form method="get">';
        // Display year dropdown search
        echo '<select name="year" id="year" style="padding:5px;margin-right:10px;">';
        echo '<option value="" ' . ($selectedYear === '' ? 'selected' : '') . '>Select Year</option>';
        foreach ($years as $year) {
            $selected = ($selectedYear == $year) ? 'selected' : '';
            echo '<option value="' . $year . '" ' . $selected . '>' . $year . '</option>';
        }
        echo '</select>';

        // Display authors input search
        echo '<input type="text" name="author" id="author" placeholder="Enter Author" style="padding:5px;margin-right:10px;" value="' . $selectedAuthor . '">';

        // Submit button
        echo '<button type="submit" class="btn btn-default">Search</button>';
        echo '</form>';
        echo '</div>';

        // Display the publications list-wise based on filter conditions
        echo '<div class="container">';
        $foundRecords = false; // Flag to check if any records are found
        if ($selectedYear && $selectedAuthor) {
            // Show publications for the selected year and author
            if (isset($data[$selectedYear])) {
                echo '<h2 class="heading">' . $selectedYear . '</h2>'; // Displaying the selected year
                echo '<ol>';
                foreach ($data[$selectedYear] as $publication) {
                    if (strpos($publication['Authors'], $selectedAuthor) !== false) {
                        $foundRecords = true;
                        echo '<li>';
                        echo '<p class="list">' . $publication['Title'] . ' - ' . $selectedYear . '</p>'; // Concatenating title and year
                        echo '<p>' . $publication['Type'] . '</p>';
                        echo '<p>' . $publication['Authors'] . '</p>';
                        echo '</li>';
                    }
                }
                echo '</ol>';
            }
        } elseif ($selectedYear) {
            // Show publications for the selected year
            if (isset($data[$selectedYear])) {
                echo '<h2 class="heading">' . $selectedYear . '</h2>'; // Displaying the selected year
                echo '<ol>';
                foreach ($data[$selectedYear] as $publication) {
                    $foundRecords = true;
                    echo '<li>';
                    echo '<p class="list">' . $publication['Title'] . '</p>';
                    echo '<p>' . $publication['Type'] . '</p>';
                    echo '<p>' . $publication['Authors'] . '</p>';
                    echo '</li>';
                }
                echo '</ol>';
            }
        } elseif ($selectedAuthor) {
            // Show publications for the selected author
            echo '<h2 class="heading">Publications by ' . $selectedAuthor . '</h2>'; // Displaying the selected author
            echo '<ol>';
            foreach ($data as $year => $publications) {
                foreach ($publications as $publication) {
                    if (strpos($publication['Authors'], $selectedAuthor) !== false) {
                        $foundRecords = true;
                        echo '<li>';
                        echo '<p class="list">' . $publication['Title'] . ' - ' . $year . '</p>'; // Concatenating title and year
                        echo '<p>' . $publication['Type'] . '</p>';
                        echo '<p>' . $publication['Authors'] . '</p>';
                        echo '</li>';
                    }
                }
            }
            echo '</ol>';
        } else {
            // No filter applied, show all publications
            foreach ($data as $year => $publications) {
                echo '<h2 class="heading">' . $year . '</h2>'; // Displaying the year for each section
                echo '<ol>';
                foreach ($publications as $publication) {
                    $foundRecords = true;
                    echo '<li>';
                    echo '<p class="list">' . $publication['Title'] . '</p>';
                    echo '<p>' . $publication['Type'] . '</p>';
                    echo '<p>' . $publication['Authors'] . '</p>';
                    echo '</li>';
                }
                echo '</ol>';
            }
        }
        if (!$foundRecords) {
            echo '<p>No records found.</p>';
        }
        echo '</div>';
    } else {
        echo '<p>Error fetching publications from API</p>';
    }
    ?>

    <script>
        // Clear selected values for year and author after displaying the results
        $(document).ready(function() {
            $('#year').val('');
            $('#author').val('');
        });
    </script>
</body>

</html>
