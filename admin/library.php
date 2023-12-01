<?php
    // Include necessary files
        include("../model/Image.php"); 
        include("../model/User.php"); 

    // Create instances of User and Image classes
        $User = new User();
        $Image = new Image();

    // Get the list of images
        $imageList = $Image->getImageList();

    // Get values from GET and POST requests
        $get_array = $Image->getGetValues();
        $post_array = $Image->getPostValues();

    // Get the base URL of the current file
        $base_url = basename(__FILE__);

    // Start a session and get user session
        session_start();
        $User->getSession();

    // If 'q' parameter is set in GET, log out the user and redirect to login page
        if (isset($_GET['q'])){
            $User->userLogout();
            header("location:../login.php");
        }

    // Handle GET action
        $action = FALSE;
        if(!empty($get_array)) {
            if(isset($get_array['action'])) {
                $action = $Image->handleGetAction($get_array);
            }
        }

    // Handle POST update action
        if (isset($post_array['update'])) {
            $update = FALSE;
            $result = $Image->updateImage($post_array) . $Image->updateJunctionTable($post_array) . $Image->imageUploadUpdate($post_array);
            if($result) {
                $update = TRUE;
                echo "<h1 style='color: white;'>gebruiker is geupdate</h1>";
                echo '<script>window.location.href="'.$base_url.'"</script>';
            } else {
                $update = FALSE; 
            }
        }
?>
<html>
    <head>
        <!-- Title of the page -->
            <title>Media Bibliotheek ~ De Soos</title>
        
        <!-- Character set for the document -->
            <meta charset="utf-8">

        <!-- Responsive viewport settings for better mobile experience -->
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Resetting default styles using a custom reset.css file -->
            <link rel="stylesheet" href="css/reset.css">

        <!-- Linking Google Fonts for custom fonts -->
            <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">

        <!-- Bootstrap CSS for styling and layout -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!-- Custom CSS styles for the page -->
            <link rel="stylesheet" href="../css/style.css">
    </head>


    <body class="backend-body">
    <!-- Container for the media page content -->
    <div id="gebruikers">
        <!-- Heading for the media page -->
        <h1 class="backend-title">Media Pagina</h1>

        <!-- Back button linking to the dashboard -->
        <a href="dashboard.php" class="btn btn-primary back-button"><< Terug</a>

        <!-- Button to add a new image -->
        <a href="image-add.php" class="btn btn-primary add-button">Afbeelding Toevoegen</a>

        <!-- Responsive table container -->
        <div class="table-responsive backend-table">
            <!-- Table to display images and their details -->
            <table class="table collapse-table">
                <thead>
                    <!-- Table header columns -->
                    <th scope="col">Afbeelding</th>
                    <th scope="col">Locatie</th>
                    <th scope="col">Afbeelding Alt</th>
                    <th scope="col">Actie</th>
                </thead>

                <?php 
                foreach($imageList as $obj){ 
                ?>
                    <?php 
                    if (($action == 'update') && ($obj->getImageId() == $get_array['image_id'])) { 
                    ?>
                        <!-- Form for updating an image -->
                        <div id="update-form">
                            <form method="POST" action="<?= $base_url; ?>" enctype="multipart/form-data">
                                <!-- Hidden input for the image ID -->
                                <input type="hidden" name="image_id" value="<?= $obj->getImageId(); ?>" />

                                <!-- File input for selecting a new image -->
                                <label for="fileToUpload">Foto </label><br>
                                <input type="file" name="fileToUpload" accept=".jpg,.jpeg,.png,.heic,.raw,.svg" 
                                    onchange="document.getElementById('imagename').value = this.value.split('\\').pop().split('/').pop()">
                                <!-- Hidden input to store the image name -->
                                <input type="hidden" class="form-control" readonly id="imagename" name="imagename" value=""><br><br>

                                <!-- Input for entering the image description -->
                                <label for="imagealt">Beschrijving</label><br>
                                <input type="text" placeholder="imagealt" value="<?= $obj->getImageAlt(); ?>"  class="form-control" name="imagealt" required><br>

                                <!-- Hidden input to store the image ID -->
                                <input type="hidden" value="<?= $obj->getImageId(); ?>" name="image_id">

                                <!-- Submit button for updating the image -->
                                <input type="submit" class="btn btn-primary" name="update" value="Wijzigen">
                            </form>
                        </div>
                    <?php 
                    } 
                    else {
                    }
                } 
                // Display the image table using the getImageTable method
                $Image->getImageTable();
                ?>
            </table>
        </div>
    </div>
</body>
</html>