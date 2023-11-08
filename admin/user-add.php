<?php 
include("../model/User.php"); 
$User = new User();
$post_array = $User->getPostValues();
session_start();
// $User->getSession();

// if (isset($_GET['q'])){
//     $User->userLogout();
//     header("location:../login.php");
// }
?>

<!-- ******** REGISTREREN ******** -->
<body id="body">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item">
            <a class="page-link" href="dashboard.php" aria-label="Previous">
                <span aria-hidden="true">&laquo; Terug</span>
            </a>
            </li>
        </ul>
    </nav>
    <section  style="padding: 100px 0 100px 0;">
        <div id="container" class="container">

            <?php
            if (isset($_POST['submit'])){
                $register = $User->addUser($post_array);
                if ($register) {
                    // Registration Success
                    echo "<script>alert('Registration is succesfull');</script>";
                    header("location:gebruikers.php");
                } else {
                    // Registration Failed
                    echo "<div class='alert alert-danger text-center' >
                            <strong>Registratie mislukt</strong>, deze e-mail bestaat al.
                        </div>";
                }
            }
            ?>

        <div class="row">
            <div class="col-md-12">
                <h1 id="registreren-titel">Gebruiker Toevoegen</h1>
                <form action="" method="post" name="reg" class="needs-validation">
                    <div class="form-row row">
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
                            <label for="rol_id" class="login-label">Rol <span class='red'>*</span></label>
                            <input type="number" class="form-control login-input" name="rol_id"
                            placeholder="" required>
                        </div>
                    </div>
                    <input class="btn btn-primary registreren-button" type="submit" name="submit" value="Registreren" onclick="return(submitreg());">
                    <a href="gebruikers.php" class="btn btn-primary registreren-button">Overzicht</a>
                </form>
            </div>
        </div>
    </section>
</body>
</html>