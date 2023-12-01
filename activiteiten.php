<?php
// ******** INCLUDES ********
  include("model/Image.php");
  include("model/User.php");
  include("model/Event.php");
  include("model/Registration.php");
  include_once("model/Sponsor.php");

  $Sponsor = new Sponsor();
  $Image = new Image();
  $User = new User();
  $Event = new Event();
  $Registration = new Registration();
  // Get the current script name
  $currentPage = basename($_SERVER['SCRIPT_NAME']);

// ******** PHPMAILER ********
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

// ******** HEADER ******** 
  include("public/assets/header.php"); 
  $EventList = $Event->getAllEvents();
  // Get values from the GET and POST requests
  $get_array = $Event->getGetValues();
  $post_array = $Event->getPostValues();
  $registration_post_array = $Registration->getPostValues();
  // Get the base URL of the current file
  $base_url = basename(__FILE__);
  // Variable to store the action status
  $action = FALSE;
  if (!empty($get_array)) {
    if (isset($get_array['action'])) {
      $action = $Event->handleGetAction($get_array);
    }
  }

if (isset($post_array['register'])) {
  $update = FALSE;
  $result = $Registration->addRegistration($registration_post_array) . $Registration->insertRegistrationJunctionTable($post_array);
  if ($result) {
    $register = TRUE;

    $registrationName = htmlspecialchars($_POST['registrationname']);
    $registrationEmail = htmlspecialchars($_POST['registrationemail']);
    $registrationPhone = htmlspecialchars($_POST['registrationphone']);
  
    $Mail->setFrom('info@caspervanmook.nl');
    $Mail->addAddress($registrationEmail);
    $Mail->addBCC("casvmook@gmail.com");
    $Mail->isHTML(true);
    $Mail->Subject = "Bevestiging activiteit aanmelding";
    $body = "Beste heer/mevrouw, <br><br>Bedankt voor uw activiteit aanmelding!<br><br>" .
    "Hieronder vind u een overzicht van de aanmelding:<br><br>" .
    "Naam: " . $registrationName . "<br>" .
    "E-mail: " . $registrationEmail . "<br>" .
    "Telefoonnummer: " . $registrationPhone . "<br><br>" .
    "Met vriendelijke groet,<br>" .
    "Het team van de soos in Kamperland";
    $Mail->Body = $body;
    $Mail->send();
    echo "<div class='alert alert-success' role='alert' style='text-align: center;'>Aanmelding voltooid, er is een bevestigingsmail verstuurd naar het opgegeven e-mailadres!</div>";
  } else {
    $register = FALSE;
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
        <h1 class="class">DE LEUKSTE ACTIVITEITEN VINDT JE HIER !</h1>
      </div>
      <div class="col-md-6">
        <div class="vertical"></div>
        <div class="col" id="second-segment-col-2">
          <div id="second-segment-col-inner">
            <p class="second-segment-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin massa arcu, suscipit sit amet sollicitudin pulvinar, ultrices vitae lorem. Suspendisse aliquet tincidunt sem sed mollis. Donec purus massa, dignissim varius eleifend sit amet, ullamcorper in libero. Quisque leo elit, lobortis sit amet porttitor at, vestibulum facilisis leo. Vestibulum mattis</p>
          </div>
        </div>
      </div>
    </div>
  </div>



<!-- ******** THIRD SEGMENT ******** -->


  <div class="container">

    <h1 class="backend-title">Activiteiten</h1>
    <div class="eventstable">
      <table class="table collapse-table">
        

  <!-- ******** ACTIVITY TABLE FROM BACKEND ******** -->
        <?php
        foreach ($EventList as $obj) {

        ?>

          <?php
          if (($action == 'register') && ($obj->getId() == $get_array['event_id'])) {
          ?>
            <br>
            <div id="update-form">
              <form method="POST" action="<?= $base_url; ?>" id="register">
                <input type="hidden" name="event_id" value="<?= $obj->getId(); ?>" />
                <p>U schrijft zich nu in voor de activiteit: <?= $obj->getName() ?></p>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="registration" class="login-label">Naam <span class='red'>*</span></label>
                    <input type="text" class="form-control login-input" name="registrationname" placeholder="" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="registrationemail" class="login-label">E-mail <span class='red'>*</span></label>
                    <input type="email" class="form-control login-input" name="registrationemail" placeholder="" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="registrationphone" class="login-label">Telefoonnummer<span class='red'>*</span></label>
                    <input type="number" class="form-control login-input" name="registrationphone" placeholder="" required>
                  </div>
                </div>
                <input type="hidden" value="<?= $obj->getId(); ?>" name="event_id">
                <input type="submit" class="btn btn-primary" name="register" value="Aanmelden">
              </form>
            </div>
        <?php
          } else {
          }
        }
        ?>

        <?php
        $Event->getFrontendEventsTable();
        ?>
      </table>
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
    <div class="col-xs-12 col-md-6 mb-4">
      <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
          <?php $Sponsor->getSponsorCarousel(); ?>

          <!-- Carousel controls -->
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
          </button>
      </div>
    </div>
  </div>

  </div>


<!-- ******** SIXTH SEGMENT ******** -->

  <div class="container">
    <div class="row gx-5 justify-content-center">

      <div class="col-lg-4 col-md-12 mb-4">
        Placeholder for img
      </div>

      <div class="col-lg-4 col-md-6 mb-4" id="sixth-segment-col">
        <h1 id="sixth-segment-h1">Word een vriend</h1>
        <p class="sixth-segment-p">en mis niks...</p>
        <a href="sponsorworden.php">
        <button class="sixth-segment-button"><p class="button-text">Neem contact op</p></button>
        </a>
      </div>

      <div class="col-lg-4 col-md-6 mb-4">
        Placeholder for img
      </div>

    </div>
  </div>

<!-- ******** END OF PAGE ITEMS ******** -->
  <?php include("public/assets/footer.php"); ?>
  </body>

  </html>