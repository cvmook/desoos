<?php 
// Including User and Event model files
include("../model/User.php");
include("../model/Event.php");

// Creating instances of User and Event classes
$User = new User();
$Event = new Event();

// Getting values from $_POST
$post_array = $Event->getPostValues();

// Starting the session and getting user session information
session_start();
$User->getSession();

// Handling user logout if 'q' is set in the query string
if (isset($_GET['q'])) {
    $User->userLogout();
    header("location:../login.php");
}
?>

<!-- HTML head section -->
<head>
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
    <section style="padding: 100px 0 100px 0;">
        <div id="container" class="container">
            <a href="events.php" class="btn btn-primary back-button2">
                << Terug</a>

            <?php
            // Handling form submission
            if (isset($_POST['submit'])) {
                $add = $Event->addEvent($post_array);
                if ($add) {
                    // Registration Success
                    echo "<script>alert('Activiteit is succesvol aangemaakt!');</script>";
                    header("location:events.php");
                } else {
                    // Registration Failed
                    echo "<div class='alert alert-danger text-center' >
                            <strong>Aanmaken mislukt</strong>, probeer opnieuw of neem contact op.
                        </div>";
                }
            }
            ?>

            <div class="row">
                <div class="col-md-12">
                    <h1 id="registreren-titel">Activiteit Toevoegen</h1>

                    <!-- Registration form -->
                    <form action="" method="post" name="reg" class="needs-validation">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="eventname" class="login-label">Naam <span class='red'>*</span></label>
                                <input type="text" class="form-control login-input" name="eventname" placeholder="" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="eventlocation" class="login-label">Locatie <span class='red'>*</span></label>
                                <input type="text" class="form-control login-input" name="eventlocation" placeholder="" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="eventage" class="login-label">Leeftijd<span class='red'>*</span></label>
                                <input type="number" class="form-control login-input" name="eventage" placeholder="" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="eventdate" class="login-label">Datum<span class='red'>*</span></label>
                                <input type="datetime-local" class="form-control login-input" name="eventdate" placeholder="" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="eventdescription" class="login-label">Beschrijving<span class='red'>*</span></label>
                                <textarea class="form-control login-input" name="eventdescription" placeholder="" required></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="eventlimit" class="login-label">Limiet<span class='red'>*</span></label>
                                <input type="number" class="form-control login-input" name="eventlimit" placeholder="" required>
                            </div>
                        </div>

                        <!-- Form submission buttons -->
                        <input class="btn btn-primary registreren-button" type="submit" name="submit" value="Toevoegen" onclick="return(submitreg());">
                        <a href="events.php" class="btn btn-primary registreren-button">Overzicht</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
