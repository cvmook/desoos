
<!-- ******** NECESSARY INCLUDES AND SUBMIT ******** -->
  <?php 
  include("model/Sponsor.php"); 
  include("model/Image.php"); 
  $Image = new Image(); 
  $Sponsor = new Sponsor();
  // Get the current script name
  $currentPage = basename($_SERVER['SCRIPT_NAME']);
  $post_array = $Sponsor->getPostValues();
  use PHPMailer\PHPmailer\PHPMailer;
  include("phpmailer/src/Exception.php");
  include("phpmailer/src/PHPMailer.php");
  include("phpmailer/src/SMTP.php");
  $Mail = new PHPMailer(true);
  $Mail->isSMTP();
  $Mail->Host = 'smtp.strato.com';
  $Mail->SMTPAuth = true;
  $Mail->Username = 'info@caspervanmook.nl';
  $Mail->Password = 'idhigjdjidhgjjoid';
  $Mail->SMTPSecure = 'ssl';
  $Mail->Port = 465;

  //******** HEADER INCLUDE ******** 
  include("public/assets/header.php");
  /* ******** SUBMIT ******** */

    if (isset($_POST['submit'])){
      // Save link display data
      list($upload,$target_file) = $Sponsor->sponsorLogoUpload();
      $target_file = $target_file[1];
      if ($upload == 1) {
        $result = $Sponsor->addSponsor($post_array,$target_file);
        $uploadAdd = TRUE;
        if($result){
          $add = TRUE;

          $sponsorName = htmlspecialchars($_POST['sponsorname']);
          $sponsorEmail = htmlspecialchars($_POST['sponsoremail']);
          $sponsorRank = htmlspecialchars($_POST['sponsorrank']);
          $sponsorDonation = htmlspecialchars($_POST['sponsordonation']);
          $sponsorDescription = htmlspecialchars($_POST['sponsordescription']);
      
          $Mail->setFrom('info@caspervanmook.nl');
          $Mail->addAddress($sponsorEmail);
          $Mail->addBCC("casvmook@gmail.com");
          $Mail->isHTML(true);
          $Mail->Subject = "Bevestiging Sponsor Aanmelding";
          $body = "Beste heer/mevrouw, <br><br>Bedankt voor uw aanmelding!<br><br>" .
          "Hieronder vind u een overzicht van de aanmelding:<br><br>" .
          "Naam: " . $sponsorName . "<br>" .
          "E-mail: " . $sponsorEmail . "<br>" .
          "Rank: " . $sponsorRank . "<br>" .
          "Donatie: " . $sponsorDonation . "<br>" .
          "Beschrijving: " . $sponsorDescription . "<br><br>" .
          "Wat houdt het zijn van een sponsor in?<br><br>" . 
          "De naam van jouw bedrijf komt op een T-shirt gedrukt te staan. Deze worden gedragen tijdens de activiteiten. Ook delen wij graag de activiteiten op de sociale media waar we ook de sponsors vermelden.
          Naast sociale media vermelden we jullie bijdrage op onze website.
          Naast dit alles houden we jaarlijks een sponsorborrel. Alle sponsors zijn dan welkom voor een hapje en drankje als dank voor de sponsoring.<br><br>".
          "Met vriendelijke groet,<br>" .
          "Het team van de soos in Kamperland";

          $Mail->Body = $body;
          $Mail->send();
          echo "<div class='alert alert-success' role='alert' style='text-align: center;'>Aanmelding voltooid, er is een bevestigingsmail verstuurd naar het opgegeven e-mailadres!</div>";
        }else{
            $add = FALSE;
        }
      } else {
        $add = FALSE;
        $uploadAdd = FALSE;
      }
    }

//******** FIRST SEGMENT ******** 

    include("public/assets/firstsegment.php");
    ?>

<!-- ******** SECOND SEGMENT ******** -->

<div class="container">
    <div class="row" id="second-segment-row">
        <div class="col-md-6">
            <p class="second-segment-p">De jeugd Soos</p>
            <h1 class="second-segment-h1">WAT HOUDT EEN SPONSOR IN.</h1>
            <a class="second-segment-href" href="#">Bekijk het hier.</a>
        </div>
        <div class="col-md-6">
            <div class="vertical"></div>
            <div class="col" id="second-segment-col-2">
                <div id="second-segment-col-inner">
                    <p class="second-segment-p">
                    Wat levert het jou bedrijf op? In de soos gaan we een Wall of Fame creëren. Op deze wand komen alle sponsors te hangen. Jou logo van het bedrijf komt op de Wall of Fame te hangen op een kroon, ster of hart versiert door de kinderen.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>




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
      <div class="col-xs-12 col-md-6 mb-4 float-right">
        <div class="pb-3">
          <h1 class="fifth-segment-h1">Sponser Aanmelden</h1>
        </div>
        <form action="" method="post" name="reg" class="needs-validation" enctype="multipart/form-data">
            <div class="row">
                <div class="pb-3">
                    <label for="sponsorname" class="login-label">Naam <span class='red'>*</span></label>
                    <input type="text" class="form-control login-input" name="sponsorname" placeholder="" required>
                </div>
            </div>
            <div class="row">
                <div class="pb-3">
                    <label for="fileToUpload" class="tabel-label">Logo <span class='red'>*</span></label><br>
                    <input type="file" name="fileToUpload" accept=".jpg,.jpeg,.png,.heic,.raw,.svg" onchange="document.getElementById('sponsorlogo').value = this.value.split('\\').pop().split('/').pop()">
                    <input type="hidden" class="form-control" readonly id="sponsorlogo" name="sponsorlogo" value="">
                </div>
            </div>
            <div class="row">
                <div class="pb-3">
                    <label for="sponsoremail" class="login-label">Email<span class='red'>*</span></label>
                    <input type="email" class="form-control login-input" name="sponsoremail" required>
                </div>
            </div>
            <div class="row">
                <div class="pb-3">
                    <?php $Sponsor->getRanksDropdown(); ?>
                </div>
            </div>
            <div class="row">
                <div class="pb-3">
                    <label for="sponsordonation" class="login-label">Donatie<span class='red'>*</span></label>
                    <input type="number" step="any" value="75.00" class="form-control login-input" name="sponsordonation" id="sponsordonation" required>
                </div>
            </div>
            <div class="row">
                <div class="pb-3">
                    <label for="sponsordescription" class="login-label">Beschrijving<span class='red'>*</span></label>
                    <input type="text" class="form-control login-input" name="sponsordescription" placeholder="" required>
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
