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
    <title>Publications List</title>
    <link href="https://fonts.googleapis.com/css2?family=Aleo:wght@900&display=swap" rel="stylesheet">
    <!-- Include your preferred CSS framework or styles for list here -->
    <style>
        .publications-list {
            margin: 20px;
        }

        .publication-item {
            margin-bottom: 10px;
            padding: 10px;
        }

        .publication-title {
            font-weight: bold;
            font-size: 18px;
            color: black;
        }

        .publication-details {
            margin-top: 5px;
            color: black;
        }

        .filter-form {
            margin-bottom: 20px;
        }

        .filter-form select {
            margin-right: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="filter-form">
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

   }?>   <form action="" method="get">
                <?php
                // Extract unique years from the API response
                $years = $data ? array_unique(array_keys($data)) : [];

                // Sort the years in descending order
                rsort($years);
                ?>
                <label for="year">Filter by Year:</label>
                <select name="year" id="year">
                    <option value="">All Years</option>
                    <?php
                    foreach ($years as $filterYear) {
                        echo '<option value="' . $filterYear . '">' . $filterYear . '</option>';
                    }
                    ?>
                </select>

                <?php
                // Collect all authors from the data
                $allAuthors = [];
                foreach ($years as $year) {
                    if (isset($data[$year])) {
                        foreach ($data[$year] as $publication) {
                            if (isset($publication['Authors'])) {
                                $authors = explode(", ", $publication['Authors']);
                                $allAuthors = array_merge($allAuthors, $authors);
                            }
                        }
                    }
                }
                $uniqueAuthors = array_unique($allAuthors);
                sort($uniqueAuthors);
                ?>
                <label for="author">Filter by Author:</label>
                <select name="author" id="author">
                    <option value="">All Authors</option>
                    <?php
                    foreach ($uniqueAuthors as $filterAuthor) {
                        echo '<option value="' . $filterAuthor . '">' . $filterAuthor . '</option>';
                    }
                    ?>
                </select>

                <button type="submit">Apply Filters</button>
            </form>
        </div>

        <div class="publications-list">
            <?php

            if ($data && is_array($data)) {
                foreach ($years as $year) {
                    echo '<div class="publication-item">';
                    echo '<h2 class="publication-title">' . $year . '</h2>';

                    // Check if data for the year exists
                    if (isset($data[$year]) && is_array($data[$year]) && count($data[$year]) > 0) {
                        echo '<ol>';
                        foreach ($data[$year] as $publication) {
                            echo '<li>';
                            echo '<p><strong>Title:</strong> ' . $publication['Title'] . '</p>';
                            echo '<p><strong>Type:</strong> ' . $publication['Type'] . '</p>';
                            // Add more details as needed
                            echo '<p><strong>Authors:</strong> ' . $publication['Authors'] . '</p>';
                            echo '</li>';
                        }
                        echo '</ol>';
                    } else {
                        echo '<p>No publications available for ' . $year . '</p>';
                    }

                    echo '</div>';
                }
            } else {
                echo "Invalid or missing data in the API response.";
            }
            ?>
        </div>
    </div>

</body>

</html>
