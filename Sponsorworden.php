<?php 
include("model/Sponsor.php"); 
include("model/Image.php"); 
$Image = new Image(); 
$Sponsor = new Sponsor();
$post_array = $Sponsor->getPostValues();

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

<?php include("public/assets/header.php"); ?>

<!-- ******** FIRST SEGMENT ******** -->

<?php include("public/assets/firstsegment.php");?>

<!-- ******** FIFTH SEGMENT ******** -->
<div class="container">  

  <div class="row" id="row-fifth-segment">
    <div class="col-xs-12 col-md-6 mb-4">
      <div class="pb-3">
        <h1 class="second-segment-h1">VRIENDEN VAN DE SOOS</h1>
      </div>
      <div class="row">
        <div class="col" id="gold-rank">
          <img src="img/kroontje.png" class="fifth-segment-icon" alt="icon voor gouden rank">
          <h3 class="fifth-segment-h3">Gouden rank 75,-</h3>
        </div>
      </div>
        <div class="row">
          <div class="col" id="silver-rank">
            <img src="img/sterren.png" class="fifth-segment-icon" alt="icon voor zilveren rank">
            <h3 class="fifth-segment-h3">Zilveren rank 50,-</h3>

          </div>
        </div>
        <div class="row">
          <div class="col" id="bronze-rank">
            <img src="img/hartje.png" class="fifth-segment-icon" alt="icon voor bronzen rank">
            <h3 class="fifth-segment-h3">Bronzen rank 35,-</h3>

          </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 mb-4">
    <h1 class="fifth-segment-h1">Sponser Aanmelden</h1>
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

<!-- ******** END OF PAGE ITEMS ******** -->
<script src="js/rank-donation.js"></script>
<?php include("public/assets/footer.php");?>
</body>
</html>
