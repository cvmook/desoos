<?php 
include_once("model/Sponsor.php");
include_once("model/Image.php");  
$Sponsor = new Sponsor();
$Image = new Image();
// Get the current script name
$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>


<!-- ******** HEADER ******** -->
  <?php
  include_once("public/assets/header.php");
  ?>

<!-- ******** FIRST SEGMENT ******** -->

  <?php include("public/assets/firstsegment.php");?>

<!-- ******** SECOND SEGMENT ******** -->

  <div class="container">
    <div class="row" id="second-segment-row">
      <div class="col-md-6">
        <p class="second-segment-p">De jeugd Soos</p>
        <h1 class="second-segment-h1">DE LEUKSTE ACTIVITEITEN VINDT JE HIER !</h1>
        <a class="second-segment-href" href="activiteiten.php">lees meer</a>
      </div>
      <div class="col-md-6">
        <div class="vertical"></div>
        <div class="col" id="second-segment-col-2">
          <div id="second-segment-col-inner">
            <p class="second-segment-p">Onze komende activiteiten vind je op de activiteiten pagina en zijn gericht op kinderen en jongeren t/m 16 jaar in en rondom Kamperland.
                                        Word jij onze vriend van de soos? Wij zijn op zoek op naar (jaarlijkse) sponsors die ons willen helpen om leuke activiteiten te organiseren voor kinderen in en rond Kamperland. Het sponsorgeld gebruiken wij voor bingo’s, film middagen, knutselmiddagen, sloopmiddagen , disco’s en nog veel meer!</p>
          </div>
        </div>
      </div>
    </div>
  </div>



<!-- ******** THIRD SEGMENT ******** -->

  <ul class="card-list">
    
    <li class="card" id="card-color-1">
      <a class="card-image" id="card-color-1" href="activiteiten.php" data-image-full="img/3537552-removebg-preview.png">
        <img src="img/3537552-removebg-preview.png" alt="Activiteiten" />
      </a>
      <a class="card-description" href="activiteiten.php">
        <h2>Activiteiten</h2>
      </a>
    </li>
    
    <li class="card" id="card-color-2">
      <a class="card-image" id="card-color-2" href="overons.php" data-image-full="img/3537341-removebg-preview.png">
        <img src="img/3537341-removebg-preview.png" alt="Over Ons" />
      </a>
      <a class="card-description" href="overons.php">
        <h2>Over ons</h2>
      </a>
    </li>
    
    <li class="card" id="card-color-3">
      <a class="card-image" id="card-color-3" href="gallery.php" data-image-full="img/3537552-removebg-preview.png">
        <img src="img/3537552-removebg-preview.png" alt="Activiteiten" />
      </a>
      <a class="card-description" href="gallery.php">
        <h2>Foto Album</h2>
      </a>
    </li>
    
  </ul>

<!-- ******** FOURTH SEGMENT ******** -->

  <div class="scroll-right">
    <p><?php $Sponsor->getSponsorSlider();?></p>
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

        <!-- Hide on tablets and mobiles -->
        <div class="col-lg-4 col-md-6 mb-4 hide-on-tablet-mobile">
            <?php $Image->getImageleft(); ?>
        </div>

        <div class="col-lg-4 col-md-6 mb-4" id="sixth-segment-col">
            <h1 id="sixth-segment-h1" class="text-center">Word een vriend</h1>
            <p class="sixth-segment-p text-center">en mis niks...</p>
            <a href="sponsorworden.php" class="d-flex justify-content-center">
                <button class="sixth-segment-button">
                    <p class="button-text">Aanmelden</p>
                </button>
            </a>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <?php $Image->getImageRight(); ?>
        </div>

    </div>
</div>


<!-- ******** END OF PAGE ITEMS ******** -->
  <?php include("public/assets/footer.php");?>

  <script>
    // Initialize the carousel with auto-slide
    var myCarousel = new bootstrap.Carousel(document.getElementById('carouselExampleIndicators'), {
        interval: 4000, // Set the interval for auto-sliding in milliseconds
        wrap: true // Set to false if you don't want the carousel to wrap around
    });
  </script>
  </body>
  </html>
