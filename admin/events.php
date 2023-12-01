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
$get_array = $Event->getGetValues();
$post_array = $Event->getPostValues();
$base_url = basename(__FILE__);

// Starting the session and getting user session information
session_start();
$User->getSession();

// Handling user logout if 'q' is set in the query string
if (isset($_GET['q'])) {
    $User->userLogout();
    header("location:../login.php");
}

// Initializing action variable for handling actions based on $_GET values
$action = FALSE;
if (!empty($get_array)) {
    if (isset($get_array['action'])) {
        $action = $Event->handleGetAction($get_array);
    }
}

// Handling event update logic if 'update' action is set in $_POST
if (isset($post_array['update'])) {
    $update = FALSE;
    $result = $Event->updateEvent($post_array) /*. $Event->updateEventJunctionTable($post_array)*/;
    if ($result) {
        $update = TRUE;
        echo "<h1 style='color: white;'>gebruiker is geupdate</h1>";
        echo '<script>window.location.href="' . $base_url . '"</script>';
    } else {
        $update = FALSE;
    }
}
?>
<!-- HTML head section -->

<head>
    <title>Activiteiten ~ De Soos</title>
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
        <!-- Page title and navigation buttons -->
        <h1 class="backend-title">Activiteiten Pagina</h1>
        <a href="dashboard.php" class="btn btn-primary back-button">
            << Terug</a>
                <a href="event-add.php" class="btn btn-primary add-button">Activiteit Toevoegen</a>

                <!-- Table displaying event information -->
                <div class="table-responsive backend-table">
                    <table class="table collapse-table">
                        <thead>
                            <!-- Table headers -->
                            <th scope="col">Naam</th>
                            <th scope="col">Aanmeldingen</th>
                            <th scope="col">Locatie</th>
                            <th scope="col">Leeftijd</th>
                            <th scope="col">Datum</th>
                            <th scope="col">Beschrijving</th>
                            <th scope="col">Limiet</th>
                            <th scope="col">Actie</th>
                        </thead>

                        <?php
                        foreach ($EventList as $obj) {
                            // Check if the 'update' action is set and the event ID matches
                            if (($action == 'update') && ($obj->getId() == $get_array['event_id'])) {
                        ?>
                                <!-- Display update form for the selected event -->
                                <br>
                                <div id="update-form">
                                    <form method="POST" action="<?= $base_url; ?>">
                                        <input type="hidden" name="id" value="<?= $obj->getId(); ?>" />
                                        <label for="eventname">Naam</label><br>
                                        <input type="text" placeholder="Naam" value="<?= $obj->getName(); ?>" class="form-control" name="eventname"><br>
                                        <label for="eventlocation">Locatie</label><br>
                                        <input type="text" placeholder="Locatie" value="<?= $obj->getLocation(); ?>" class="form-control" name="eventlocation"><br>
                                        <label for="eventage">Leeftijd</label><br>
                                        <input type="number" placeholder="Leeftijd" value="<?= $obj->getAge(); ?>" class="form-control" name="eventage"><br>
                                        <label for="eventdate">Datum</label><br>
                                        <input type="datetime-local" placeholder="Datum" value="<?= $obj->getDate(); ?>" class="form-control" name="eventdate"><br>
                                        <label for="eventdescription">Beschrijving</label><br>
                                        <textarea placeholder="Beschrijving" class="form-control " name="eventdescription"><?= $obj->getDescription(); ?></textarea><br>
                                        <label for="eventlimit">Limiet</label><br>
                                        <input type="number" placeholder="Limiet" value="<?= $obj->getLimit(); ?>" class="form-control" name="eventlimit"><br>
                                        <input type="hidden" value="<?= $obj->getId(); ?>" name="event_id">
                                        <input type="submit" class="btn btn-primary" name="update" value="Update">
                                    </form>
                                </div>
                        <?php
                            }
                            // Add other conditions or actions as needed
                        }
                        // Display the events table
                        $Event->getEventsTable();
                        ?>
                    </table>
                </div>
    </div>
</body>

</html>