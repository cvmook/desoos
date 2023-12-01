<?php
include("../model/User.php"); 
include("../model/Event.php"); 
include("../model/Registration.php"); 
$User = new User();
$Event = new Event();
$Registration = new Registration();
$EventList = $Event->getAllEvents();
$get_array = $Event->getGetValues();
$post_array = $Event->getPostValues();
$registration_post_array = $Registration->getPostValues();
$base_url = basename(__FILE__);

session_start();
$User->getSession();

if (isset($_GET['q'])){
    $User->userLogout();
    header("location:../login.php");
}

$action = FALSE;
if(!empty($get_array)) {
    if(isset($get_array['action'])) {
        $action = $Event->handleGetAction($get_array);
    }
}

if (isset($post_array['register'])) {
    $update = FALSE;
    $result = $Registration->addRegistration($registration_post_array) . $Registration->insertRegistrationJunctionTable($post_array);
    if($result) {
        $update = TRUE;
        // echo "<h1 style='color: white;'>gebruiker is geupdate</h1>";
        // echo '<script>window.location.href="'.$base_url.'"</script>';
    } else {
        $update = FALSE; 
    }
}
?>
<head>
    <title>Activiteiten ~ De Soos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/reset.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="backend-body">
    <div id="gebruikers">
        <h1 class="backend-title">FRONTEND Activiteiten Pagina</h1>
        <div class="table-responsive backend-table">
            <table class="table collapse-table">
                <thead>
                    <th scope="col">Naam</th>
                    <th scope="col">Locatie</th>
                    <th scope="col">Leeftijd</th>
                    <th scope="col">Datum</th>
                    <th scope="col">Beschrijving</th>
                    <th scope="col"></th>
                </thead>

            <?php 
            foreach($EventList as $obj){ 
                
                ?>

                <?php 
                if (($action == 'register') && ($obj->getId() == $get_array['event_id'])) { 
                ?>
                    <br>
                    <div id="update-form">
                        <form method="POST" action="<?= $base_url; ?>" >
                            <input type="hidden" name="event_id" value="<?= $obj->getId(); ?>" />
                            <p>U schrijft zich nu in voor de activiteit: <?= $obj->getName() ?></p>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="registration" class="login-label">Naam <span class='red'>*</span></label>
                                    <input type="text" class="form-control login-input" name="registrationname"
                                    placeholder="" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="registrationemail" class="login-label">E-mail <span class='red'>*</span></label>
                                    <input type="email" class="form-control login-input" name="registrationemail"
                                    placeholder="" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="registrationphone" class="login-label">Telefoonnummer<span class='red'>*</span></label>
                                    <input type="number" class="form-control login-input" name="registrationphone"
                                    placeholder="" required>
                                </div>
                            </div>
                            <input type="hidden" value="<?= $obj->getId(); ?>" name="event_id">
                            <input type="submit" class="registreren-button" name="register" value="Aanmelden">
                        </form>
                    </div>
                <?php 
                } 
                else {
                    /*
                ?>
                <tbody>
                    <tr>
                        <td><?= $obj->getUsername(); ?></td>
                        <td><?= $obj->getName(); ?></td>
                        <td><?= $User->getRolesFromJunctionTable(); ?></td>
                        <td><a href="<?= $upd_link; ?>"><svg xmlns="http://www.w3.org/2000/svg" style="color:#232323;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                        <!-- <a href="<?=$del_link; ?>"><svg xmlns="http://www.w3.org/2000/svg" style="color:#232323;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></td> -->
                        <a onClick="javascript: return confirm('Wilt u deze gebruiker definitief verwijderen?');" href='<?=$del_link; ?>'><svg xmlns="http://www.w3.org/2000/svg" style="color:#232323;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                    </tr>
                </tbody>
                <?php 
                */
                }
            } 
            $Event->getFrontendEventsTable();
                ?>
            </table>
        </div>
    </div>
</body>
</html>