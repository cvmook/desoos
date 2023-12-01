<?php
include("../model/User.php"); 
$User = new User();
$Userlist = $User->GetAllUsers();
$get_array = $User->getGetValues();
$post_array = $User->getPostValues();
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
        $action = $User->handleGetAction($get_array);
    }
}

if (isset($post_array['update'])) {
    $update = FALSE;
    $result = $User->update($post_array) . $User->updateJunctionTable($post_array);
    if($result) {
        $update = TRUE;
        echo "<script>alert('Gebruiker is bijgewerkt!')</script>";
        echo '<script>window.location.href="'.$base_url.'"</script>';
    } else {
        $update = FALSE; 
    }
}
?>
<head>
    <title>Gebruikers ~ De Soos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/reset.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="backend-body">
    <div id="gebruikers">
        <h1 class="backend-title">Gebruikers Pagina</h1>
        <a href="dashboard.php" class="btn btn-primary back-button"><< Terug</a>
        <a href="user-add.php" class="btn btn-primary add-button">Gebruiker Toevoegen</a>
        <div class="table-responsive backend-table">
            <table class="table collapse-table">
                <thead>
                    <th scope="col">Gebruikersnaam</th>
                    <th scope="col">Naam</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Actie</th>
                </thead>

            <?php 
            foreach($Userlist as $obj){ 
            
                if (($action == 'update') && ($obj->getUserId() == $get_array['id'])) { 
                ?>
                    <br>
                    <div id="update-form">
                        <form method="POST" action="<?= $base_url; ?>" >
                            <input type="hidden" name="id" value="<?= $obj->getUserId(); ?>" />
                            <label for="username">Gebruikersnaam</label><br>
                            <input type="text" placeholder="Gebruikersnaam" value="<?= $obj->getUserUsername(); ?>"  class="form-control" name="username"><br>
                            <label for="name">Naam</label><br>
                            <input type="text" placeholder="Naam" value="<?= $obj->getUserName(); ?>"  class="form-control" name="name"><br>
                            <label for="role">Rol</label>
                            <?php $User->getRolesDropdown(); ?><br>
                            <input type="hidden" class="form-control" readonly id="passwordCheck" name="passwordCheck" value="<?=$obj->GetUserPassword(); ?>"required>
                            <input type="hidden" value="<?= $obj->getUserId(); ?>" name="id">
                            <input type="submit" class="btn btn-primary" name="update" value="Update">
                        </form>
                    </div>
                <?php 
                } 
                else {
                }
            }
            $User->getUsersTable();
                ?>
            </table>
        </div>
    </div>
</body>
</html>