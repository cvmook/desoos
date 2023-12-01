<?php
include("model/User.php");

$User = new User();

/* ******** SESSION ******** */

    session_start();
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
        if ($User->getSession()){
        header("location:admin/dashboard.php");
        }
    }

/* ******** SUBMIT ******** */
    $post_array = $User->getPostValues();
    $error = '';
    if (isset($_POST['submit'])) {
        extract($_POST);
        $login = $User->checkLogin($post_array);
        if ($login) {
            // Registration Success
            $userListByUsername = $User->getUserListByUsername($post_array);
            foreach($userListByUsername as $ul){
                if($ul->getUserToken() !== null){
                    header("location:admin/dashboard.php");
                }
            }
        } else {
            // Registration Failed
            $error =    "<div class='alert alert-danger text-center' >
                            <strong>Inloggen mislukt</strong>, de gebruikersnaam/email en wachtwoord komen niet overeen of bestaan niet.
                        </div>";
        }
    }

?>

<!-- ******** START HTML ********  -->

    <!DOCTYPE html>
    <html>

<!-- ******** HEAD ********  -->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="css/reset.css">
        <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>

<!-- ******** BODY ********  -->
    <body id="body">

<!-- ******** LOGIN SECTION ********  -->
    <section id="login" style="padding: 50px 0 100px 0;">
        <div id="container" class="container">
            <?= $error;?>
            <div class="row">
                <div class="col-md-12" id="login">
                    <form action="" method="post" name="login" class="needs-validation">
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
                        <input class="btn btn-primary" type="submit" name="submit" value="Inloggen" id="login-button"
                            onclick="return(submitlogin());">
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </section>

<!-- ******** END OF PAGE ********  -->
    </body>