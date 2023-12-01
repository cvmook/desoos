<?php
require_once '../model/User.php';
$User = new User();

session_start();
$User->getSession();

$roleId = $User->getRoleIdByUserId();

if (isset($_GET['q'])){
    $User->userLogout();
    header("location:../login.php");
}
?>

<head>
    <title>Dashboard ~ De Soos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/reset.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="backend-body">
    <a href="?q=logout"><svg id="logout-icon" xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg></a><br><br><br><br><br>
    <?php 
    if($roleId == 1) { 
    ?>
        <div class="dashboard-items">
            <div class="dashboard-item" onclick="location.href='users.php'">
                <h2 class="dashboard-heading">Gebruikers</h2>
                <p class="dashboard-subtext">Hier kunt u alles vinden over gebruikers.</p>
            </div>
            <div class="col dashboard-item" onclick="location.href='events.php'">
                <h2 class="dashboard-heading">Activiteiten</h2>
                <p class="dashboard-subtext">Hier kunt u activiteiten beheren.</p>
            </div>
            <div class="dashboard-item" onclick="location.href='sponsors.php'">
                <h2 class="dashboard-heading">Sponsoren</h2>
                <p class="dashboard-subtext">Hier kunt u de sponsoren beheren.</p>
            </div>
            <div class="col dashboard-item" onclick="location.href='library.php'">
                <h2 class="dashboard-heading">Media Bibliotheek </h2>
                <p class="dashboard-subtext">Hier kunt u de afbeeldingen beheren.</p>
            </div>
        </div>
    <?php 
    } if($roleId == 2) {
    ?>
        <div class="dashboard-items">
            <div class="col dashboard-item" onclick="location.href='library.php'">
                <h2 class="dashboard-heading">Media Bibliotheek </h2>
                <p class="dashboard-subtext">Hier kunt u de afbeeldingen beheren.</p>
            </div>
        </div>
    <?php 
    } if($roleId == 3) {
    ?>
        <div class="dashboard-items">
            <div class="col dashboard-item" onclick="location.href='events.php'">
                <h2 class="dashboard-heading">Activiteiten</h2>
                <p class="dashboard-subtext">Hier kunt u activiteiten beheren.</p>
            </div>
        </div>
    <?php
    }
    ?>
</body>
</html>