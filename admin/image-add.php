<?php 
include("../model/Image.php"); 
include("../model/User.php"); 
$User = new User();
$Image = new Image();
$post_array = $Image->getPostValues();
$get_array = $Image->getGetValues();
session_start();
$User->getSession();

if (isset($_GET['q'])){
    $User->userLogout();
    header("location:../login.php");
}

if (isset($_POST['submit'])){
    // Save link display data
    list($upload,$target_file) = $Image->imageUpload();
    $target_file = $target_file[1];
    if ($upload == 1) {
        $result = $Image->addImage($post_array,$target_file) . $Image->insertJunctiontable();
        $uploadAdd = TRUE;
        if($result){
            $add = TRUE;
            echo '
            <div class="alert alert-success" role="alert" style="text-align : center";>
                Image is succesvol geuploaded
            </div>
            ';
        }else{
            $add = FALSE;
            echo 
            "<div class='alert alert-danger text-center' >
                <strong>Registratie mislukt</strong>, Kon image niet toevoegen.
            </div>";
        }
    } else {
            $add = FALSE;
            $uploadAdd = FALSE;
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

    <!-- ******** IMAGE ADD ******** -->
    <body class="backend-body">
        <section  style="padding: 100px 0 100px 0;">
            <div id="container" class="container">
                <a href="library.php" class="btn btn-primary back-button2"><< Terug</a>
                <div class="row">
                    <div class="col-md-12">
                        <h1 id="registreren-titel">Foto Toevoegen</h1>
                        <form action="" method="post" name="reg" class="needs-validation" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="fileToUpload">Foto <span class='red'>*</span></label><br>
                                    <input type="file" name="fileToUpload" accept=".jpg,.jpeg,.png,.heic,.raw,.svg" 
                                    onchange="document.getElementById('imagename').value = this.value.split('\\').pop().split('/').pop()">
                                    <input type="hidden" class="form-control" readonly id="imagename" name="imagename" value=""><br><br>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="imagealt" class="login-label">Beschrijving <span class='red'>*</span></label>
                                    <input type="text" class="form-control login-input" name="imagealt" placeholder=""
                                    required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="location_id" class="login-label">Locatie <span class='red'>*</span></label>
                                    <?php $Image->GetLocatieDropdown(); ?>
                                </div>
                            </div>
                            <input class="btn btn-primary registreren-button" type="submit" name="submit" value="Toevoegen" onclick="return(submitreg());">
                            <a href="library.php" class="btn btn-primary registreren-button">Overzicht</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>