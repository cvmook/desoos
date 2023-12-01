<?php
// Including necessary model files
include("../model/User.php");
include("../model/Event.php");
include("../model/Registration.php");

// Creating instances of User, Event, and Registration classes
$User = new User();
$Event = new Event();
$Registration = new Registration();

// Getting all events and values from $_GET and $_POST
$EventList = $Event->getAllEvents();
$get_array = $Registration->getGetValues();
$post_array = $Event->getPostValues();
$registration_post_array = $Registration->getPostValues();

// Getting the base URL of the current file
$base_url = basename(__FILE__);

// Getting the event ID from the query string
$eventId = $_GET['event_id'];

// Starting the session and getting user session information
session_start();
$User->getSession();

// Handling user logout if 'q' is set in the query string
if (isset($_GET['q'])) {
    $User->userLogout();
    header("location:../login.php");
}

// Initializing action variable
$action = FALSE;

// Checking if there are any values in $_GET array and handling the action accordingly
if (!empty($get_array)) {
    if (isset($get_array['action'])) {
        $action = $Registration->handleGetAction($get_array);
    }
}
?>

<!-- HTML head section -->

<head>
    <title>Aanmeldingen ~ De Soos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Including CSS stylesheets -->
    <link rel="stylesheet" href="css/reset.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>

<!-- HTML body section -->

<body class="backend-body">
    <div id="gebruikers">
        <!-- Page title and navigation button -->
        <h1 class="backend-title">Aanmeldingen Pagina</h1>
        <a href="events.php" class="btn btn-primary back-button">
            << Terug</a>

                <!-- Form for exporting PDF -->
                <form class="export-button" target="_blank" action="export_pdf.php" method="POST">
                    <input type="text" name="eventId" value="<?= $eventId ?>" hidden>
                    <input type="submit" name="submit" value="Deelnemers lijst exporteren" class="btn btn-primary">
                </form>

                <!-- Table for displaying registrations -->
                <div class="table-responsive backend-table">
                    <table class="table collapse-table">
                        <thead>
                            <th scope="col">Naam</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Telefoonnummer</th>
                            <th scope="col">Actie</th>
                        </thead>

                        <?php
                        // Generating and displaying registrations table
                        $Registration->getRegistrationsTable();
                        ?>
                    </table>
                </div>
    </div>
</body>

</html>