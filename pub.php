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
        .dropdowns {
            margin: 20px;
        }

        .heading {
            font-weight: bold;
        }

        .list {
            color: #0f1333;
            font-weight: bold;
            font-size:15px;
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

        // Extract unique authors
        $authors = [];
        foreach ($data as $yearData) {
            foreach ($yearData as $publication) {
                if (isset($publication['Authors'])) {
                    $authors = array_merge($authors, explode(',', $publication['Authors']));
                }
            }
        }
        $authors = array_unique(array_map('trim', $authors));
        sort($authors);

        // Set initial values for year and author selection
        $selectedYear = isset($_GET['year']) ? $_GET['year'] : '';
        $selectedAuthor = isset($_GET['author']) ? $_GET['author'] : '';

        // Display the form with dropdowns and submit button
        echo '<div class="container">';
        echo '<form method="get">';
        // Display year dropdown search
        echo '<select name="year" id="year" style="padding:5px;">';
        echo '<option value="" ' . ($selectedYear === '' ? 'selected' : '') . '>Select Year</option>';
        foreach ($years as $year) {
            $selected = ($selectedYear == $year) ? 'selected' : '';
            echo '<option value="' . $year . '" ' . $selected . '>' . $year . '</option>';
        }
        echo '</select>';

        // Display authors dropdown search
        
        echo '<input type="text" name="author" id="author" placeholder="Enter Author" style="padding:5px;margin:20px;" value="' . $selectedAuthor . '">';
        
        

        // Submit button
        echo '<button type="submit" class="btn btn-default">Submit</button>';
        echo '</form>';
        echo '</div>';

        // Display publications based on selected year and/or author
        $recordsFound = false;
        if ($selectedYear && $selectedAuthor) {
            // If both year and author are selected, filter publications based on both criteria
            foreach ($years as $year) {
                if (isset($data[$year])) {
                    foreach ($data[$year] as $publication) {
                        if ($publication['Year'] == $selectedYear && strpos($publication['Authors'], $selectedAuthor) !== false) {
                            if (!$recordsFound) {
                                echo '<div class="container">';
                                $recordsFound = true;
                            }
                            echo '<h2 class="heading">' . $year . '</h2>';
                            echo '<ol>';
                            echo '<li>';
                            echo '<p class="list">' . $publication['Title'] . '</p>';
                            echo '<p>' . $publication['Type'] . '</p>';
                            echo '<p>' . $publication['Authors'] . '</p>';
                            echo '</li>';
                            echo '</ol>';
                        }
                    }
                }
            }
        } elseif ($selectedYear) {
            // If only the year is selected, filter publications for that specific year
            foreach ($years as $year) {
                if (isset($data[$year])) {
                    if ($year == $selectedYear) {
                        if (!$recordsFound) {
                            echo '<div class="container">';
                            $recordsFound = true;
                        }
                        echo '<h2 class="heading">' . $year . '</h2>';
                        foreach ($data[$year] as $publication) {
                            echo '<ol>';
                            echo '<li>';
                            echo '<p class="list">' . $publication['Title'] . '</p>';
                            echo '<p>' . $publication['Type'] . '</p>';
                            echo '<p>' . $publication['Authors'] . '</p>';
                            echo '</li>';
                            echo '</ol>';
                           
                        }
                    }
                }
            }
        } elseif ($selectedAuthor) {
            // If only the author is selected, filter publications by that author across all years
            echo '<div class="container">';
            echo '<h2 class="heading">Publications by ' . $selectedAuthor . '</h2>';
            foreach ($years as $year) {
                if (isset($data[$year])) {
                    foreach ($data[$year] as $publication) {
                        if (strpos($publication['Authors'], $selectedAuthor) !== false) {
                            if (!$recordsFound) {
                                echo '<div class="container">';
                                $recordsFound = true;
                            }
                            echo '<h3>' . $year . '</h3>';
                            echo '<ol>';
                            echo '<li>';
                            echo '<p class="list">' . $publication['Title'] . '</p>';
                            echo '<p>' . $publication['Type'] . '</p>';
                            echo '<p>' . $publication['Authors'] . '</p>';
                            echo '</li>';
                            echo '</ol>';
                        }
                    }
                }
            }
            echo '</div>';
        } else {
            // If no filter is applied, display all publications year-wise
            foreach ($years as $year) {
                if (isset($data[$year])) {
                    if (!$recordsFound) {
                        echo '<div class="container">';
                        $recordsFound = true;
                    }
                    echo '<h2 class="heading">' . $year . '</h2>';
                    foreach ($data[$year] as $publication) {
                        echo '<p class="list">' . $publication['Title'] . '</p>';
                        echo '<p>' . $publication['Type'] . '</p>';
                        echo '<p>' . $publication['Authors'] . '</p>';
                    }
                }
            }
        }

        // Display error message if no records are found
        if (!$recordsFound) {
            echo '<div class="container">';
            echo '<p>No records found.</p>';
            echo '</div>';
        }
    } else {
        echo '<p>Error fetching publications from API</p>';
    }
    ?>

</body>

</html>
