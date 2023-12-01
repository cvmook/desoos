<?php 
include("model/Sponsor.php");
include("model/Image.php"); 
$Image = new Image(); 
$Sponsor = new Sponsor();
// Get the current script name
$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>

<!-- ******** INCLUDE HEADER ******** -->
  <?php include("public/assets/header.php"); ?>

<!-- ******** FIRST SEGMENT ******** -->

  <?php include("public/assets/firstsegment.php");?>

<!-- ******** SECOND SEGMENT ******** -->

  <div class="container">
    <div class="row" id="second-segment-row">
      <div class="col-md-6">
        <p class="second-segment-p">De jeugd Soos</p>
        <h1 class="second-segment-h1">WAT IS ONS VERHAAL</h1>
      </div>
      <div class="col-md-6">
        <div class="vertical"></div>
        <div class="col" id="second-segment-col-2">
          <div id="second-segment-col-inner">
            <p class="second-segment-p">De soos wordt gerund door een viertal enthousiaste vrijwilligers die de nodige uren spenderen om leuke middag of avondactiviteiten te organiseren. Hierbij moet je denken aan knutselen, een bingo, disco, of het kijken van een film.</p>
          </div>
        </div>
      </div>
    </div>
  </div>



<!-- ******** THIRD SEGMENT ******** -->

  <ul class="card-list">
  <h1 class="second-segment-h1">WAT IS ONS VERHAAL</h1>
    <li class="card" id="card-color-1">
      <a class="card-image" id="card-color-1" href="img/steef.png" target="_blank" data-image-full="img/steef.png">
        <img src="img/steef.png" alt="Steef de Gruiter" />
      </a>
      <a class="card-description" href="#" target="_blank">
        <h4>Steef van de Gruiter</h4>
      </a>
    </li>
    
    <li class="card" id="card-color-2">
      <a class="card-image" id="card-color-2" href="img/elise.png" target="_blank" data-image-full="img/elise.png">
        <img src="img/elise.png" alt="elise marcusse" />
      </a>
      <a class="card-description" href="#" target="_blank">
        <h4>Elise Marcusse</h4>
      </a>
    </li>
    
    <li class="card" id="card-color-3">
      <a class="card-image" id="card-color-3" href="#" target="_blank" data-image-full="img/linda.png">
        <img src="img/linda.png" alt="linda van gilst-rijn" />
      </a>
      <a class="card-description" href="#" target="_blank">
        <h4>Linda van Gilst-Rijn</h4>
      </a>
    </li>

      <li class="card" id="card-color-4">
      <a class="card-image" id="card-color-4" href="#" target="_blank" data-image-full="img/angela.png">
        <img src="img/angela.png" alt="angela verbakel-louwerse" />
      </a>
      <a class="card-description" href="#" target="_blank">
        <h4>Angela Verbakel-Louwerse </h4>
      </a>
    </li>
  </ul>

<!-- ******** FOURTH SEGMENT ******** -->

  <div class="scroll-right">
    <p><?=$Sponsor->getSponsorSlider();?></p>
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
