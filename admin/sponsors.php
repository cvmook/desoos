<?php
include("../model/User.php"); 
include("../model/Sponsor.php"); 
$User = new User();
$Sponsor = new Sponsor();
$sponsorList = $Sponsor->getSponsorList();
$get_array = $Sponsor->getGetValues();
$post_array = $Sponsor->getPostValues();
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
        $action = $Sponsor->handleGetAction($get_array);
    }
}

if (isset($post_array['update'])) {
    // Check the add form:
    $update = FALSE;
    // Save event type
    list($upload,$target_file) = $Sponsor->sponsorlogoUploadUpdate($post_array);
    
    if (isset($target_file[1])) {
        $target_file = $target_file[1];
    }
    else {
        $target_file = '';
    }

    $result = $Sponsor->updateSponsor($post_array,$target_file);
    if ($result) {
        // Save was succesfull
        $update = TRUE;
        echo "<script>alert('Sponsor is bijgewerkt!')</script>";
    } else {
        // Indicate error
        $update = FALSE;
    }
}
?>
<head>
    <title>Sponsoren ~ De Soos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/reset.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="backend-body">
    <div id="gebruikers">
        <h1 class="backend-title">Sponsoren Pagina</h1>
        <a href="dashboard.php" class="btn btn-primary back-button"><< Terug</a>
        <div class="table-responsive backend-table">
            <table class="table collapse-table">
                <thead>
                    <th scope="col">Naam</th>
                    <th scope="col">Logo</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Rank</th>
                    <th scope="col">Donatie</th>
                    <th scope="col">Beschrijving</th>
                    <th scope="col">Actie</th>
                </thead>

            <?php 
            foreach($sponsorList as $obj){ 
                if (($action == 'update') && ($obj->getId() == $get_array['sponsor_id'])) { 
                ?>
                    <br>
                    <div id="update-form">
                        <form method="POST" action="<?= $base_url; ?>" enctype="multipart/form-data">
                            <input type="hidden" name="sponsor_id" value="<?= $obj->getId(); ?>" />
                            <label for="username">Naam</label><br>
                            <input type="text" placeholder="Naam" value="<?= $obj->getSponsorName(); ?>"  class="form-control" name="sponsorname" required><br>
                            <label for="fileToUpload">Logo <span class='red'>*</span></label><br>
                            <input type="file" name="fileToUpload" accept=".jpg,.jpeg,.png,.heic,.raw,.svg" 
                            onchange="document.getElementById('sponsorlogo').value = this.value.split('\\').pop().split('/').pop()">
                            <input type="hidden" class="form-control" readonly id="sponsorlogo" name="sponsorlogo" value=""><br><br>
                            <label for="sponsorremail">Email</label><br>
                            <input type="text" placeholder="E-mail" value="<?= $obj->getSponsorEmail(); ?>"  class="form-control" name="sponsoremail" required><br>
                            <?php $Sponsor->getRanksDropdown(); ?><br>
                            <label for="sponsordonation">Donatie</label><br>
                            <input type="number" step="any" value="<?= $obj->getSponsorDonation(); ?>"  class="form-control" id="sponsordonation" name="sponsordonation" required><br>
                            <label for="sponsordescription">Beschrijving</label><br>
                            <input type="text" placeholder="Beschrijving" value="<?= $obj->getSponsorDescription(); ?>"  class="form-control" name="sponsordescription" required><br>
                            <input type="hidden" value="<?= $obj->getId(); ?>" name="sponsor_id">
                            <input type="submit" class="btn btn-primary" name="update" value="Update">
                        </form>
                    </div>
                <?php 
                } 
                else {
                }
            } 
            $Sponsor->getSponsorsTable();
                ?>
            </table>
        </div>
    </div>
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