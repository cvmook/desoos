<!-- ******** START OF HTML ******** -->
    <!DOCTYPE html>
    <html lang="en">

<!-- ******** HEAD WITH META, AND LINKS ******** -->
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="De soos een organisatie die leuke activiteiten in kamperland organiseerd u kunt op deze website u kinderen voor deze activiteiten inschrijven.">
        <meta name="keywords" content="keywords, for, SEO">
        <meta name="author" content="Yannick Elias, Casper van Mook, Jaimy Zegers">

        <!-- Favicon -->
        <link rel="icon" href="" type="image">
        
        <!-- CSS Stylesheets -->
        <link rel="stylesheet" href="../css/reset.css">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
        
        <!-- CSS and JavaScript libraries (if needed) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        <link rel="stylesheet" href="../css/style.css">
        
    </head>

<!-- ******** START OF BODY ******** -->
    <body>

<!-- ******** Navigation ******** -->
<div class="m-4" id="">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <!-- ******** GET LOGO FUNCTION ******** -->
            <a href="index.php" class="navbar-brand">
                <?php $Image->getLogo(); ?>
            </a>

            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto">
                    <a href="index.php" class="nav-item nav-link <?php if ($currentPage === 'index.php') echo 'active'; ?>">Home</a>
                    <a href="activiteiten.php" class="nav-item nav-link <?php if ($currentPage === 'activiteiten.php') echo 'active'; ?>">Activiteiten</a>
                    <a href="overons.php" class="nav-item nav-link <?php if ($currentPage === 'overons.php') echo 'active'; ?>">Over Ons</a>
                    <a href="gallery.php" class="nav-item nav-link <?php if ($currentPage === 'gallery.php') echo 'active'; ?>">Foto's</a>
                    <a href="sponsorworden.php" class="nav-item nav-button <?php if ($currentPage === 'sponsorworden.php') echo 'active'; ?>">
                        <button class="btn-hover btn btn-primary navbar-btn">Sponsor
                    </a>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</div>
