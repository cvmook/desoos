
<!-- ******** NECESSARY INCLUDES ******** -->
    <?php
    // Include necessary files
            include("model/Image.php"); 
            

        // Create instances of User and Image classes
            $Image = new Image();

        // Get the current script name
            $currentPage = basename($_SERVER['SCRIPT_NAME']);

        // Get the list of images
            $imageList = $Image->getImageList();

        // Get values from GET and POST requests
            $get_array = $Image->getGetValues();
            $post_array = $Image->getPostValues();

        // Get the base URL of the current file
            $base_url = basename(__FILE__);
    ?>

<!-- ******** HEADER INCLUDE ******** -->

    <?php include("public/assets/header.php");?>

<!-- ******** FIRST SEGMENT ******** -->

    <?php include("public/assets/firstsegment.php");?>

<!-- ******** SECOND SEGMENT ******** -->

    <div class="container">
        <div class="row gx-1 justify-content-center">
            <?php 
                $Image->getImageGrid();
            ?>
        </div>
    </div>


<!-- ******** END OF PAGE ITEMS ******** -->
    <?php include("public/assets/footer.php");?>
    </body>
    </html>
