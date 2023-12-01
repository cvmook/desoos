<?php 
include("../model/User.php"); 
include("../model/Sponsor.php"); 
$User = new User();
$Sponsor = new Sponsor();
$post_array = $Sponsor->getPostValues();
session_start();
$User->getSession();

if (isset($_GET['q'])){
    $User->userLogout();
    header("location:../login.php");
}

if (isset($_POST['submit'])){
    // Save link display data
    list($upload,$target_file) = $Sponsor->sponsorLogoUpload();
    $target_file = $target_file[1];
    if ($upload == 1) {
        $result = $Sponsor->addSponsor($post_array,$target_file);
        $uploadAdd = TRUE;
        if($result){
            $add = TRUE;
            echo '
            <div class="alert alert-success" role="alert">
                U heeft zich succesvol aangemeld, u krijgt een bevestiging in uw mail!
            </div>
            ';
        }else{
            $add = FALSE;
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
<!-- ******** REGISTREREN ******** -->
<body class="backend-body">
    <section  style="padding: 100px 0 100px 0;">
        <div id="container" class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 id="registreren-titel">Sponser Aanmelden</h1>
                    <form action="" method="post" name="reg" class="needs-validation" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="sponsorname" class="login-label">Naam <span class='red'>*</span></label>
                                <input type="text" class="form-control login-input" name="sponsorname"
                                placeholder="" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="fileToUpload" class="tabel-label">Logo <span class='red'>*</span></label><br>
                                <input type="file" name="fileToUpload" accept=".jpg,.jpeg,.png,.heic,.raw,.svg" 
                                onchange="document.getElementById('sponsorlogo').value = this.value.split('\\').pop().split('/').pop()">
                                <input type="hidden" class="form-control" readonly id="sponsorlogo" name="sponsorlogo" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="sponsoremail" class="login-label">Email<span class='red'>*</span></label>
                                <input type="email" class="form-control login-input" name="sponsoremail" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <?php $Sponsor->getRanksDropdown(); ?>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="sponsordonation" class="login-label">Donatie<span class='red'>*</span></label>
                                <input type="number" step="any" value="75.00" class="form-control login-input" name="sponsordonation" id="sponsordonation" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="sponsordescription" class="login-label">Beschrijving<span class='red'>*</span></label>
                                <input type="text" class="form-control login-input" name="sponsordescription"
                                placeholder="" required>
                            </div>
                        </div>
                        <input class="btn btn-primary" type="submit" name="submit" value="Aanmelden">
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        function updateDonationValue() {
            var rankDropdown = document.getElementById("rank-dropdown");
            var donationInput = document.getElementById("sponsordonation");

            // Get the selected value from the dropdown
            var selectedRank = rankDropdown.options[rankDropdown.selectedIndex].value;

            // Update the donation input based on the selected rank
            switch (selectedRank) {
                case "goud":
                    donationInput.value = "75.00"; 
                    break;
                case "zilver":
                    donationInput.value = "50.00"; 
                    break;
                case "brons":
                    donationInput.value = "25.00"; 
                    break;
                default:
                    donationInput.value = "75.00"; 
                    break;
            }
        }
    </script>

</body>
</html>