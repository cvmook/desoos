<?php 
include("model/Sponsor.php");
include("model/Image.php"); 
$Image = new Image(); 
$Sponsor = new Sponsor();
?>

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
          <p class="second-segment-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin massa arcu, suscipit sit amet sollicitudin pulvinar, ultrices vitae lorem. Suspendisse aliquet tincidunt sem sed mollis. Donec purus massa, dignissim varius eleifend sit amet, ullamcorper in libero. Quisque leo elit, lobortis sit amet porttitor at, vestibulum facilisis leo. Vestibulum mattis</p>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- ******** THIRD SEGMENT ******** -->


<ul class="card-list">
<h1 class="second-segment-h1">WAT IS ONS VERHAAL</h1>
	<li class="card" id="card-color-1">
		<a class="card-image" id="card-color-1" href="#" target="_blank" data-image-full="img/3537552-removebg-preview.png">
			<img src="img/3537552-removebg-preview.png" alt="Activiteiten" />
		</a>
		<a class="card-description" href="#" target="_blank">
			<h4>Steef van de Gruiter</h4>
		</a>
	</li>
	
	<li class="card" id="card-color-2">
		<a class="card-image" id="card-color-2" href="#" target="_blank" data-image-full="img/3537341-removebg-preview.png">
			<img src="img/3537341-removebg-preview.png" alt="Over Ons" />
		</a>
		<a class="card-description" href="#" target="_blank">
			<h4>Elise Marcusse</h4>
		</a>
	</li>
	
	<li class="card" id="card-color-3">
		<a class="card-image" id="card-color-3" href="#" target="_blank" data-image-full="img/3537552-removebg-preview.png">
			<img src="img/3537552-removebg-preview.png" alt="Activiteiten" />
		</a>
		<a class="card-description" href="#" target="_blank">
			<h4>Linda van Gilst-Rijn</h4>
		</a>
	</li>

    <li class="card" id="card-color-4">
		<a class="card-image" id="card-color-4" href="#" target="_blank" data-image-full="img/3537552-removebg-preview.png">
			<img src="img/3537552-removebg-preview.png" alt="Activiteiten" />
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
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="img/3537552-removebg-preview.png" class="d-block w-100" alt="Slider img">
          </div>
          <div class="carousel-item">
            <img src="img/3537552-removebg-preview.png" class="d-block w-100" alt="Slider img">
          </div>
          <div class="carousel-item">
            <img src="img/3537341-removebg-preview.png" class="d-block w-100" alt="Slider img">
          </div>
        </div>
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
    <h1 id="sixth-segment-h1">Wordt een vriend</h1>
    <p class="sixth-segment-p">en mis niks...</p>
    <button class="sixth-segment-button"><p class="button-text">Neem contact op</p></button>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
    Placeholder for img
    </div>

  </div>
</div>

<!-- ******** END OF PAGE ITEMS ******** -->
<?php include("public/assets/footer.php");?>
</body>
</html>
