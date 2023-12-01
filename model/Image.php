<?php

/* ******** INCLUDE NECESSARY FILES ******** */
include_once("Database.php");

/* ******** INITIALIZE IMAGE CLASS ******** */ 
class Image {

    private $imageId;
    private $imageName;
    private $imageAlt;

    protected $db;

/* ******** DBCONNECT ******** */
    private function DbConnect() {
        // Create a new Database object
        $this->db = new Database();
        
        // Retrieve the Database object reference
        $this->db = $this->db->retObj();
        
        // Return the Database object for use in the calling code
        return $this->db;
    }

/* ******** SETTERS & GETTERS ******** */

    /* ******** SETTERS ******** */

        /* SET IMAGE ID */
            public function setImageId($imageId) {
                $this->imageId = $imageId;
            }

        /* SET IMAGE NAME */
        public function setImageName($imageName) {
            $this->imageName = $imageName;
        }

        /* SET IMAGE ALT */
        public function setImageAlt($imageAlt) {
            $this->imageAlt = $imageAlt;
        }


    /* ******** GETTERS ******** */

        /* GET IMAGE ID */
        public function getImageId() {
            return $this->imageId;
        }

        /* GET IMAGE NAME */
        public function getImageName() {
            return $this->imageName;
        }

        /* GET IMAGE ALR */
        public function getImageAlt() {
            return $this->imageAlt;
        }
    

/* ******** GET POST VALUES ******** */

    public function getPostValues() {

        // Define an array specifying the filters for each POST parameter
        $post_check_array = array(
            'update'        => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'delete'        => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'imagename'     => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'imagealt'      => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'image_id'      => array('filter' => FILTER_VALIDATE_INT),
            'location_id'   => array('filter' => FILTER_VALIDATE_INT)
        );
    
        // Use filter_input_array to apply the defined filters to the POST data
        $inputs = filter_input_array(INPUT_POST, $post_check_array);
    
        // Return the sanitized and validated input values
        return $inputs;
    }

/* ******** GET GET VALUES ******** */

    public function getGetValues() {

        // Define an array specifying the filters for each GET parameter
        $get_check_array = array(
            'action'   => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'image_id' => array('filter' => FILTER_VALIDATE_INT),
        );
    
        // Use filter_input_array to apply the defined filters to the GET data
        $inputs = filter_input_array(INPUT_GET, $get_check_array);
    
        // Return the sanitized and validated input values
        return $inputs;
    }

/* ******** HANDLES GET ACTION ******** */

    public function handleGetAction($get_array) {

        // Initialize the action variable
        $action = '';
    
        // Switch statement to handle different actions based on the 'action' parameter
        switch ($get_array['action']) {
            case 'update':
                // Set action to 'update' if 'image_id' is provided
                if (!is_null($get_array['image_id'])) {
                    $action = $get_array['action'];
                }
                break;
    
            case 'delete':
                // Delete current id if provided and set action to 'delete'
                if (!is_null($get_array['image_id'])) {
                    $this->deleteImage($get_array);
                }
                $action = 'delete';
                break;
    
            default:
                // Handle unexpected actions
                break;
        }
    
        // Return the determined action based on the input variables
        return $action;
    }

/* ******** GET IMAGE ID ******** */
    // Function to retrieve imageid from 'images' table based on criteria.
    private function getImageId1($criteria) {
        $connection = $this->DbConnect();
    
        // Use a SELECT query to retrieve the fileid based on the criteria.
        $query = "SELECT image_id FROM `images` WHERE imagename = ?";
        
        // Prepare the statement
        $stmt = mysqli_prepare($connection, $query);
    
        // Bind parameters
        mysqli_stmt_bind_param($stmt, 's', $criteria);
    
        // Execute the statement
        mysqli_stmt_execute($stmt);
    
        // Bind the result
        mysqli_stmt_bind_result($stmt, $imageId);
    
        // Fetch the result
        mysqli_stmt_fetch($stmt);
    
        // Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($connection);
    
        return $imageId;
    }

/* ******** GET IMAGE LIST ******** */

    public function getImageList(){

        // Initialize an empty array to store the Image objects
        $return_array = array();

        // SQL query to select all images from the 'images' table, ordered by image_id
        $query = "SELECT * FROM `images` ORDER BY image_id";

        // Perform the database query
        $result = $this->DbConnect()->query($query);

        // Close the database connection
        $this->DbConnect()->close();

        // Loop through each database result and create Image objects
        foreach ($result as $idx => $array){

            // Create a new Image object
            $Image = new Image();

            // Set properties of the Image object based on the database
            $Image->setImageId($array['image_id']);
            $Image->setImageName($array['imagename']);
            $Image->setImageAlt($array['imagealt']);

            // Add the Image object to the return array
            $return_array[] = $Image;
        }

        // Return the array of Image objects
        return $return_array;
    }

/* ******** GET IMAGE TABLE ******** */

    public function getImageTable() {
        // PAGINATION
            if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
                $page_no = $_GET['page_no'];
            } else {
                // if first page page number is 1
                $page_no = 1;
            }
                // total records shown per page adjust as needed
                $total_records_per_page = 5;
                // if page has less then 6 -1 page = page 1
                $offset = ($page_no - 1) * $total_records_per_page;
                // go back to previous page = -1 to page number
                $previous_page = $page_no - 1;
                // go to next page = +1 to page number
                $next_page = $page_no + 1;
                
    
        // Get total records for pagination calculation
        $sql_count = "SELECT COUNT(DISTINCT images.image_id) AS total_records FROM `images`
                      LEFT JOIN `images-locaties` ON images.image_id = `images-locaties`.image_id
                      LEFT JOIN `locations` ON `images-locaties`.location_id = locations.location_id";
        
            $result_count = $this->DbConnect()->query($sql_count);
            $total_records = mysqli_fetch_array($result_count);
            $total_records = $total_records['total_records'];
            $total_no_of_pages = ceil($total_records / $total_records_per_page);

        // Get paginated records
            $sql = "SELECT * FROM `images`
            LEFT JOIN `images-locaties` ON images.image_id = `images-locaties`.image_id
            LEFT JOIN `locations` ON `images-locaties`.location_id = locations.location_id
            LIMIT $offset, $total_records_per_page";
        
            $result = $this->DbConnect()->query($sql);
    
        // Display records
            while ($row = mysqli_fetch_assoc($result)) {
                // Extract data from the database result
                    $imageId = $row['image_id'];
                    $imageName = $row['imagename'];
                    $imageAlt = $row['imagealt'];
                    $locationName = $row['locationname'];

                // Define links for update and delete actions based on the server environment
                    if ($_SERVER['HTTP_HOST'] == 'localhost:8888') {
                        $upd_link = 'http://localhost:8888/desoos/admin/library.php' . '?action=update&image_id=' . $imageId;
                        $del_link = 'http://localhost:8888/desoos/admin/library.php' . '?action=delete&image_id=' . $imageId;
                    } else if ($_SERVER['HTTP_HOST'] == 'localhost') {
                        $upd_link = 'http://localhost/desoos/admin/library.php' . '?action=update&image_id=' . $imageId;
                        $del_link = 'http://localhost/desoos/admin/library.php' . '?action=delete&image_id=' . $imageId;
                    } else {
                        $upd_link = '' . '?action=update&image_id=' . $imageId;
                        $del_link = '' . '?action=delete&image_id=' . $imageId;
                    }

                // Display HTML table rows with image data, edit, and delete links
                    echo '
                    <tbody>
                        <tr>
                            <td><img src="../img/gallery/'. $imageName .'" class="table-image"></td>
                            <td>' . $locationName .   '</td>
                            <td>' . $imageAlt .     '</td>
                            <td>
                                <a href="' . $upd_link . '">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="color:#232323;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a><br>
                                <a onclick="javascript: return confirm(\'weet u zeker dat u deze foto wilt verwijderen?\')" href="'.$del_link.'" ><svg xmlns="http://www.w3.org/2000/svg" style="color:#232323;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></td>
                            </td>
                        </tr>
                    </tbody>
                    ';
        }
    
        // Pagination controls
        echo "<div id='totalpages'>";
        echo "<strong>Pagina " . $page_no . " van " . $total_no_of_pages . "</strong><br>";
    
        if ($page_no > 1) {
            echo "<a id='previous' href='?page_no=$previous_page'>Previous</a>";
        }
    
        if ($page_no < $total_no_of_pages) {
            echo "<a id='next' href='?page_no=$next_page'>Next</a>";
        }
    
        echo "</div>";
    
        $this->DbConnect()->close();
    }

/* ******** ADD IMAGE ******** */

    public function addImage($input_array, $target_file) {
        try {
            // redfine values for insert
            if($target_file == $input_array['imagename']){
            }else{
                $input_array['imagename'] = $target_file;
            }
            $imageName = $input_array['imagename'];
            $imageAlt = $input_array['imagealt'];

            // SQL query to insert a new record into the 'images' table
            $query = "INSERT INTO `images` (imagename, imagealt) VALUES ('$imageName', '$imageAlt')";

            // Perform the database query
            $this->DbConnect()->query($query);

            // Close the database connection
            $this->DbConnect()->close();
        } catch (Exception $exc) {
            // Handle exceptions and return false on failure
            return false;
        }

        // Return true on successful insertion
        return true;
    }
    
/* ******** UPLOAD IMAGE TO FOLDER ******** */

    public function imageUpload()
    {
        // Define the upload directory and target directory
        $upload_dir = '../img';
        $target_dir = $upload_dir . '/gallery/';
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        // Initialize variables
        $uploadOk = 0;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the image file is a valid image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000000) {
            $uploadOk = 2;
        }

        // Check if the file already exists and resolve by renaming
        if (file_exists($target_file)) {
            $fileName = $_FILES["fileToUpload"]["name"];
            $file = explode('.', $fileName);

            // if file has already been uploaded before ads random numbers between 1 to 10000
            $rand = rand(1, 10000);
            // adds the random number after the filename
            $fileName = $file[0] . $rand . '.' . $file[1];

            $target_file = $target_dir . $fileName;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "svg") {
            // needs to be the same as the amount of file formats above
            $uploadOk = 4;
        }

        // Check if $uploadOk is set to 0 by an error
        if (in_array($uploadOk, array(0, 2, 3, 4))) {
            // Handle the error (You may want to log or display an error message)
        } else {
            // Try to move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                // File uploaded successfully
            } else {
                // Error moving the file
            }
        }

        // Extract the target file path relative to the target directory
        $target_file = explode($target_dir, $target_file);

        // Return an array with the upload status code and the target file path
        return array($uploadOk, $target_file);
    }

/* ******** UPDATE IMAGE ******** */

    public function updateImage($input_array) {
        try {
            // Define the fields that need to be updated
            $array_fields = array('image_id', 'imagename', 'imagealt');
            $data_array = array();

            // Validate and extract data from the input array
            foreach ($array_fields as $field) {
                if (!isset($input_array[$field])) {
                    throw new Exception($field . " must be filled for update.");
                }
                $data_array[] = $input_array[$field];
            }

            // Extract image data from the input array
            $imageId = $input_array['image_id'];
            $imageName = $input_array['imagename'];
            $imageAlt = $input_array['imagealt'];

            // SQL query to update the 'images' table with new image data
            $sql = "UPDATE `images` SET `imagename`='$imageName',`imagealt`='$imageAlt' WHERE `image_id`='$imageId'";

            // Perform the database query
            $this->DbConnect()->query($sql);

            // Close the database connection
            $this->DbConnect()->close();
        } catch (Exception $e) {
            // Handle exceptions and return false on failure
            echo $e->getMessage();
            return false;
        }

        // Return true on successful update
        return true;
    }

/* ******** IMAGE UPLOAD UPDATE ******** */

    public function imageUploadUpdate($input_array) {
        $upload_dir         =  '../img';
        $target_dir         = $upload_dir. '/gallery/';
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $imageName        = $input_array['imagename'];

        // Check if image file is a actual image or fake image
        if($imageName  != ''){
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {

                    $uploadOk = 1;
                } else {

                    $uploadOk = 0;
                }
            }

            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000000) {
                $uploadOk = 2;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "svg") {
                // needs to be the same as the amount of file formats above
                $uploadOk = 4;
            }

            // Check if the file already exists and resolve by renaming
            if (file_exists($target_file)) {
                $fileName = $_FILES["fileToUpload"]["name"];
                $file = explode('.', $fileName);

                // if file has already been uploaded before ads random numbers between 1 to 10000
                $rand = rand(1, 10000);
                // adds the random number after the filename
                $fileName = $file[0] . $rand . '.' . $file[1];

                $target_file = $target_dir . $fileName;
            }

            // Check if $uploadOk is set to 0 by an error
            if (in_array($uploadOk, array(0, 2, 4))) {

                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                } else {
                }
            }
            $target_file = explode( $target_dir,$target_file);
            return array($uploadOk, $target_file);
        }
    }

/* ******** DELETE IMAGE ******** */

    public function deleteImage($input_array){
        try {
            $imageId = $input_array['image_id'];
            // Check input id
            if (!isset($input_array['image_id'])) {
                throw new Exception("Missing mandatory fields");
            }
    
            // Get the image filename from the database
            $query = "SELECT imagename FROM images WHERE image_id = $imageId";
            $result = $this->DbConnect()->query($query);
            $row = $result->fetch_assoc();
            $imageName = $row['imagename'];
    
            // Delete the image from the database
            $sql = "DELETE FROM `images` WHERE image_id = ?";
            $stmt = $this->DbConnect()->prepare($sql);
            $stmt->bind_param("i", $imageId);
            $stmt->execute();
            $this->DbConnect()->close();
    
            // Delete the image from directory
            $upload_dir = '../img';
            $target_dir = $upload_dir . '/gallery/';
            $image_path = $target_dir . $imageName;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            
        } catch (Exception $exc) {
            echo $exc->getMessage();
            return FALSE;
        }
        return TRUE;
    }

/* ******** IMAGES-LOCATION JUNCTION TABLE ******** */

    /* ******** INSERT IMAGES-LOCATION JUNCTION TABLE ******** */
        public function insertJunctiontable() {
            $connection = $this->DbConnect();
        
            // Check if the POST variables are set and not empty.
            if (isset($_POST['location_id']) && !empty($_POST['location_id'])) {
                // Retrieve the appropriate  from the 'images' table based on some criteria.
                $imageId = $this->getImageId1($_POST['imagename']);
                $locationId = $_POST['location_id'];
                if ($imageId !== null) {
                    // Use prepared statement to insert data into the junction table.
                    $sql = "INSERT INTO `images-locaties` (image_id, location_id) VALUES (?, ?)";
        
                    // Prepare the statement
                    $stmt = mysqli_prepare($connection, $sql);
        
                    // Bind parameters
                    mysqli_stmt_bind_param($stmt, 'ii', $imageId, $locationId);
        
                    // Execute the statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Insertion was successful

                    } else {
                        // Insertion failed
                        echo "Insertion failed: " . mysqli_error($connection);
                    }

                    // Close the statement and connection
                    mysqli_stmt_close($stmt);
                } else {
                    echo "Invalid criteria or file not found in 'images' table.";
                }
        
                // Close the database connection
                mysqli_close($connection);
            } else {
                echo "locatieid must be set and not empty.";
            }
        }

    /* ******** UPDATE IMAGES-LOCATION JUNCTION TABLE ******** */

        public function updateJunctionTable($input_array) {
            try {
                // Extract image ID and location ID from the input array
                $imageId = $input_array['image_id'];
                $locationId = $input_array['location_id'];
        
                // Construct the SQL query to update the junction table
                $sql = "UPDATE `images-locaties` SET `image_id`='$imageId' WHERE `location_id`='$locationId'";
                
                // Perform the query using the database connection
                $this->DbConnect()->query($sql);
                
                // Close the database connection
                $this->DbConnect()->close();
            } catch (Exception $e) {
                // Handle any exceptions that may occur during the process
                echo $e->getMessage();
                return FALSE;
            }
        
            // Return TRUE to indicate successful update
            return TRUE;
        }

/* ******** GET LOCATION DROPDOWN ******** */
    public function GetLocatieDropdown() {
        // Construct the SQL query to select all locations from the 'locations' table
        $sql = "SELECT * FROM `locations`";
        
        // Execute the query using the database connection
        $result = mysqli_query($this->DbConnect(), $sql);

        // Echo the <select> tag with an assigned id and name
        echo '<br/><select id="locatiedropdown" class="form-control" name="location_id">';

        // Iterate through each row in the result set
        while ($row = mysqli_fetch_array($result)) {
            // Echo an <option> tag for each location, using location_id as the value and locationname as the display text
            echo '<option id="locatiedropdown-content" value="' . $row['location_id'] . '">' . $row['locationname'] . '</option>';
        }

        // Echo the </select> tag
        echo '</select>';
    }


/* ******** Get IMAGE GRID ******** */
    public function getImageGrid() {
        // PAGINATION
            if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
                $page_no = $_GET['page_no'];
            } else {
                // if first page page number is 1
                $page_no = 1;
            }
                // total records shown per page adjust as needed
                $total_records_per_page = 12;
                // if page has less then 6 -1 page = page 1
                $offset = ($page_no - 1) * $total_records_per_page;
                // go back to previous page = -1 to page number
                $previous_page = $page_no - 1;
                // go to next page = +1 to page number
                $next_page = $page_no + 1;
        

        // Get total records
        $sql_count = "SELECT COUNT(DISTINCT images.image_id) AS total_records FROM `images`
                      LEFT JOIN `images-locaties` ON images.image_id = `images-locaties`.image_id
                      LEFT JOIN `locations` ON `images-locaties`.location_id = locations.location_id
                      WHERE locations.location_id = 4";

        $result_count = $this->DbConnect()->query($sql_count);
        $total_records = mysqli_fetch_array($result_count);
        $total_records = $total_records['total_records'];
        $total_no_of_pages = ceil($total_records / $total_records_per_page);

        // Get paginated records
        $sql = "SELECT images.image_id, images.imagename, images.imagealt, locations.locationname
                FROM `images`
                LEFT JOIN `images-locaties` ON images.image_id = `images-locaties`.image_id
                LEFT JOIN `locations` ON `images-locaties`.location_id = locations.location_id WHERE locations.location_id = 4
                LIMIT $offset, $total_records_per_page";
        $result = $this->DbConnect()->query($sql);

        // Display records
        while ($row = mysqli_fetch_array($result)) {


            echo "<div class='col-lg-4 col-md-6 col-sm-12 mb-4 text-center' id='col4'>"; // Added text-center for centering
            echo "<div class='fotoalbum-item'>";
            echo "<img class='fotoalbum-image img-fluid' src='img/gallery/" . $row['imagename'] . "' alt='Image'> <br>";
            echo "<div class='prinfo'>";
            echo "<div class='prtitle'>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            
            
        }

        // Pagination controls
        echo "<div id='totalpages'>";
        echo "<strong>Pagina " . $page_no . " van " . $total_no_of_pages . "</strong><br>";
        
        if ($page_no > 1) {
            echo "<a id='previous' href='?page_no=$previous_page'>Previous</a>";
        }

        if ($page_no < $total_no_of_pages) {
            echo "<a id='next' href='?page_no=$next_page'>Next</a>";
        }

        echo "</div>";

        $this->DbConnect()->close();
    }

/* ******** IMAGE LOCATIONS ******** */

    /* ******** LOGOLOCATION ******** */

        public function getLogo() {
            // Query to select the image_id associated with the location_id = 3 from the junction table
            $query = "SELECT `image_id` FROM `images-locaties` WHERE `location_id` = 3";

            // Execute the query using the database connection
            $result = mysqli_query($this->DbConnect(), $query);

            // Loop through each row in the result set
            while ($row = mysqli_fetch_array($result)) {
                // Get the image_id from the current row
                $imageId = $row['image_id'];

                // Query to select the imagename associated with the retrieved image_id from the 'images' table
                $queryImg = "SELECT `imagename` FROM `images` WHERE `image_id` = $imageId";

                // Execute the query to get the image details
                $resultImg = mysqli_query($this->DbConnect(), $queryImg);

                // Loop through each row in the result set of the second query
                while ($rowImg = mysqli_fetch_array($resultImg)) {
                    // Output the logo image using the imagename and the specified path
                    echo '<img id="logo" src="img/gallery/' . $rowImg["imagename"] . '" ><br>';
                }
            }

            // Close the database connection
            $this->DbConnect()->close();
        }

    /* ******** IMAGE LOCATION BOTTOM LEFT ******** */

        public function getImageleft() {
            // Query to select the image_id associated with the location_id = 1 from the junction table
            $query = "SELECT `image_id` FROM `images-locaties` WHERE `location_id` = 1";
    
            // Execute the query using the database connection
            $result = mysqli_query($this->DbConnect(), $query);
    
            // Loop through each row in the result set
            while ($row = mysqli_fetch_array($result)) {
                // Get the image_id from the current row
                $imageId = $row['image_id'];
    
                // Query to select the imagename associated with the retrieved image_id from the 'images' table
                $queryImg = "SELECT `imagename` FROM `images` WHERE `image_id` = $imageId";
    
                // Execute the query to get the image details
                $resultImg = mysqli_query($this->DbConnect(), $queryImg);
    
                // Loop through each row in the result set of the second query
                while ($rowImg = mysqli_fetch_array($resultImg)) {
                    // Output the logo image using the imagename and the specified path
                    echo '<img id="image-bottom" src="img/gallery/' . $rowImg["imagename"] . '" ><br>';
                    }
                }
    
                // Close the database connection
                $this->DbConnect()->close();
            }

        /* ******** IMAGE LOCATION BOTTOM LEFT ******** */

            public function getImageRight() {
                // Query to select the image_id associated with the location_id = 2 from the junction table
                $query = "SELECT `image_id` FROM `images-locaties` WHERE `location_id` = 2";
        
                // Execute the query using the database connection
                $result = mysqli_query($this->DbConnect(), $query);
        
                // Loop through each row in the result set
                while ($row = mysqli_fetch_array($result)) {
                    // Get the image_id from the current row
                    $imageId = $row['image_id'];
        
                    // Query to select the imagename associated with the retrieved image_id from the 'images' table
                    $queryImg = "SELECT `imagename` FROM `images` WHERE `image_id` = $imageId";
        
                    // Execute the query to get the image details
                    $resultImg = mysqli_query($this->DbConnect(), $queryImg);
        
                    // Loop through each row in the result set of the second query
                    while ($rowImg = mysqli_fetch_array($resultImg)) {
                        // Output the logo image using the imagename and the specified path
                        echo '<img id="image-bottom" src="img/gallery/' . $rowImg["imagename"] . '" ><br>';
                        }
                    }
        
                    // Close the database connection
                    $this->DbConnect()->close();
                }
    
}
?>