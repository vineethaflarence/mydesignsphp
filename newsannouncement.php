<?php
/*
Template Name: test
*/
?>

<?php
$current_url = esc_url(home_url(add_query_arg(array(), $wp->request)));
?>
<?php get_header() ?>
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
        .container {
            margin-top: 20px;
        }

        .heading {
            font-weight: bold;
            font-size: 25px;
            margin: 20px;
        }

        .list {
            color: #0f1333;
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 0;
            /* Remove bottom margin */
        }

        /* Remove default list styling */
        ol,
        p {
            padding-left: 0;
            margin-bottom: 0;
        }

        ol li {
            margin-bottom: 20px;
            /* Add space between list items */
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination .page-numbers {
            padding: 5px 10px;
            margin: 0 5px;
            border: 1px solid #0d264a;
            border-radius: 5px;
            text-decoration: none;
            color: #0d264a;
        }

        .pagination .page-numbers.current {
            background-color: #0d264a;
            color: #fff;
        }

        .pagination .page-numbers:hover {
            background-color: #0d264a;
			color:white;
        }

        .search-form {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            padding: 5px;
            margin-right: 10px;
        }

        .search-form button {
            padding: 5px 10px;
        }
		.type{
		color:grey;
		font-weight:bold;
		font-size:12px;
		}
		.author{
		font-size:15px;
		font-size:400;
		}
		/* Style for the collapsible abstract box */
.collapse {
    display: none;
    transition: height 0.3s ease;
}

.collapse.show {
    display: block;
}

.card {
    background-color: #f8f9fa; /* Background color for the card */
    border: 1px solid #dee2e6; /* Border color */
    border-radius: 0.25rem; /* Border radius */
    padding: 10px; /* Padding inside the card */
    margin-top: 10px; /* Margin from the top */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Box shadow */
}

.card-body {
    padding: 15px; /* Padding inside the card body */
}

.abstract-link , .bibtext-link {
    color: #007bff; /* Link color */
    text-decoration: none; /* Remove underline */
    cursor: pointer; /* Show pointer cursor on hover */
	font-weight:500;font-size:13px;
}

.abstract-link:hover {
    text-decoration: underline; /* Underline on hover */
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

        // Extract unique years and sort them in descending order
        $years = array_keys($data);
        rsort($years);
       // print_r($years);

        // Set initial values for search query and pagination
        $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
        $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
        $posts_per_page = 50;
        $offset = ($paged - 1) * $posts_per_page;
        $total_items = 0;
        foreach ($years as $year) {
            $total_items += count($data[$year]);
        }
        $total_pages = ceil($total_items / $posts_per_page);

        // Display the form with search input and submit button
        echo '<div class="container">';
        echo '<form class="search-form" method="get" action="' . $current_url . '">';

        // Display search input
        echo '<input type="text" name="search" id="search" placeholder="Enter Search Query" value="' . $searchQuery . '">';

        // Submit button
        echo '<button type="submit" class="btn btn-default">Search</button>';
        echo '</form>';
        echo '</div>';

        // Display the publications list based on search query
        echo '<div class="container">';
        $foundRecords = false; // Flag to check if any records are found

        // Show publications matching the search query
        if (!empty($searchQuery)) {
            echo '<ol>';
            $prevYear = ''; // Variable to keep track of the previous year
            $count = 0;
            foreach ($years as $year) {
                foreach ($data[$year] as $publication) {
                    // Check if search query matches title, authors, year, or type
                    if (stripos($publication['Title'], $searchQuery) !== false ||
                        stripos($publication['Authors'], $searchQuery) !== false ||
                        stripos($year, $searchQuery) !== false ||
                        stripos($publication['Type'], $searchQuery) !== false) {
                        $foundRecords = true;
                        // Display year heading only if it's different from the previous year
                        if ($year !== $prevYear && $count <= ($offset + $posts_per_page)) {
                            $prevYear = $year;
                            echo '<h2 class="heading">' . $year . '</h2>';
                        }
                        $count++;
                        // Display only if within the current page's range
                        if ($count > $offset && $count <= ($offset + $posts_per_page)) {
                            echo '<li>';
                        echo '<p style="font-size:14px;">' . $publication['Authors'] . '</p>';
                        echo '<p class="list">' . $publication['Title'] . '</p>'; // Modified line
                        echo '<p style="font-size:14px;">' . $publication['Type'] .  ' - <i>' . $year . '</i></p>';
                        echo '[<a href="#" style="font-weight:500;font-weight:10px;" class="abstract-link" data-target="#abstract_' . $count . '">abstract</a>] &nbsp;';
                        echo '[<a href="#" class="bibtext-link" data-target="#bibtext_' . $count . '">bibtext</a>]';
                        echo '<div class="collapse" id="abstract_' . $count . '">';
                        echo '<div class="card card-body" style="font-size:14px;">';
                        echo $publication['Abstract']; // Assuming you have the 'Abstract' key in your publication data
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="collapse" id="bibtext_' . $count . '">';
                        echo '<div class="card card-body" style="font-size:14px;">';
                        echo $publication['Bibtext']; // Assuming you have the 'Bibtext' key in your publication data
                        echo '</div>';
                        echo '</div>';
                        echo '</li>';
                        }
                    }
                }
            }
            echo '</ol>';
        } else {
            // No search query, display all publications grouped by year
            echo '<ol>';
            $count = 0;
		
            foreach ($years as $year) {
                if ($count > $offset-1 && $count <= ($offset-1 + $posts_per_page)) {
                     	echo '<h2 class="heading">' . $year . '</h2>';// Displaying the year
                }
                foreach ($data[$year] as $publication) {
                    $foundRecords = true;
                    $count++;
                    // Display only if within the current page's range
                    if ($count > $offset && $count <= ($offset + $posts_per_page)) {
                        echo '<li>';
                        echo '<p style="font-size:14px;">' . $publication['Authors'] . '</p>';
                        echo '<p class="list">' . $publication['Title'] . '</p>'; // Modified line
                        echo '<p style="font-size:14px;">' . $publication['Type'] .  ' - <i>' . $year . '</i></p>';
                        echo '[<a href="#"  class="abstract-link" data-target="#abstract_' . $count . '">abstract</a>] &nbsp;';
                        echo '[<a href="#" class="bibtext-link" data-target="#bibtext_' . $count . '">bibtext</a>]';
                        echo '<div class="collapse" id="abstract_' . $count . '">';
                        echo '<div class="card card-body" style="font-size:14px;">';
                        echo $publication['Abstract']; // Assuming you have the 'Abstract' key in your publication data
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="collapse" id="bibtext_' . $count . '">';
                        echo '<div class="card card-body" style="font-size:14px;">';
                        echo $publication['Bibtext']; // Assuming you have the 'Bibtext' key in your publication data
                        echo '</div>';
                        echo '</div>';
                        echo '</li>';
                    }
                }
            }
            echo '</ol>';
        }

        if (!$foundRecords) {
            echo '<p>No records found.</p>';
        }
        echo '</div>';

        // Pagination
        echo '<div class="pagination">';
        $big = 999999999; // need an unlikely integer

        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, $paged),
            'total' => $total_pages,
        ));
        echo '</div>';
    } else {
        echo '<p>Error fetching publications from API</p>';
    }
    ?>

    <script>
        // Clear search input value after displaying the results
        $(document).ready(function() {
            $('#search').val('');
        });
		 // Add event listener to the abstract links to toggle collapse
        // Add event listener to the abstract links to toggle collapse
        document.addEventListener("DOMContentLoaded", function() {
            const abstractLinks = document.querySelectorAll('.abstract-link');
            const bibtextLinks = document.querySelectorAll('.bibtext-link');

            function toggleCollapse(targetId) {
                const target = document.querySelector(targetId);
                if (target) {
                    const allCollapse = document.querySelectorAll('.collapse');
                    allCollapse.forEach(function(collapse) {
                        if (collapse.id !== targetId.slice(1)) {
                            collapse.classList.remove('show');
                        }
                    });
                    target.classList.toggle('show');
                }
            }

            abstractLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    toggleCollapse(targetId);
                });
            });

            bibtextLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    toggleCollapse(targetId);
                });
            });
        });
    </script>
	
</body>

</html>
<?php get_footer() ?>