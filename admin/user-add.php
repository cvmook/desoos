<?php 
include("../model/User.php"); 
$User = new User();
$post_array = $User->getPostValues();
session_start();
$User->getSession();

if (isset($_GET['q'])){
    $User->userLogout();
    header("location:../login.php");
}

if (isset($_POST['submit'])){
    $register = $User->addUser($post_array) . $User->insertRolesJunctionTable();
    if ($register) {
        // Registration Success
        echo "<script>alert('Gebruiker succesvol toegevoegd!');</script>";
        header("location:users.php");
    } else {
        // Registration Failed
        echo "<div class='alert alert-danger text-center' >
                <strong>Gebruiker toevoegen mislukt.</strong>
            </div>";
    }
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/reset.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<!-- ******** REGISTREREN ******** -->
<body class="backend-body">
    <section  style="padding: 100px 0 100px 0;">
        <div id="container" class="container">
            <a href="users.php" class="btn btn-primary back-button2"><< Terug</a>
            <div class="row">
                <div class="col-md-12">
                    <h1 id="registreren-titel">Gebruiker Toevoegen</h1>
                    <form action="" method="post" name="reg" class="needs-validation">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="username" class="login-label">Gebruiksernaam <span class='red'>*</span></label>
                                <input type="text" class="form-control login-input" name="username"
                                placeholder="" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password" class="login-label">Wachtwoord <span class='red'>*</span></label>
                                <input type="password" class="form-control login-input" name="password" placeholder=""
                                required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="email" class="login-label">Naam<span class='red'>*</span></label>
                                <input type="text" class="form-control login-input" name="name"
                                placeholder="" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="role_id" class="login-label">Rol <span class='red'>*</span></label>
                                <?php $User->getRolesDropdown(); ?>
                            </div>
                        </div>
                        <input class="btn btn-primary registreren-button" type="submit" name="submit" value="Registreren" onclick="return(submitreg());">
                        <a href="users.php" class="btn btn-primary registreren-button">Overzicht</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>