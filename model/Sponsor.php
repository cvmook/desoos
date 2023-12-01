<?php
include_once("Database.php");

class Sponsor {
    // declares attributes
    private $sponsorId;
    private $sponsorName;
    private $sponsorLogo;
    private $sponsorEmail;
    private $sponsorRank;
    private $sponsorDonation;
    private $sponsorDescription;
    protected $db;

/* ******** DATABASE CONNECTION FUNCTION ******** */
    private function DbConnect() {
        $this->db = new Database();
        $this->db = $this->db->retObj();
        return $this->db;
    }

/* ******** GETTERS EN SETTERS ******** */

    public function setId($sponsorId) {
        $this->sponsorId = $sponsorId;
    }
    public function setSponsorName($sponsorName) {
        $this->sponsorName = $sponsorName;
    }
    public function setSponsorLogo($sponsorLogo) {
        $this->sponsorLogo = $sponsorLogo;
    }
    public function setSponsorEmail($sponsorEmail) {
        $this->sponsorEmail = $sponsorEmail;
    }
    public function setSponsorRank($sponsorRank) {
        $this->sponsorRank = $sponsorRank;
    }
    public function setSponsorDonation($sponsorDonation) {
        $this->sponsorDonation = $sponsorDonation;
    }
    public function setSponsorDescription($sponsorDescription) {
        $this->sponsorDescription = $sponsorDescription;
    }

    public function getId() {
        return $this->sponsorId;
    }
    public function getSponsorName() {
        return $this->sponsorName;
    }
    public function getSponsorLogo() {
        return $this->sponsorLogo;
    }
    public function getSponsorEmail() {
        return $this->sponsorEmail;
    }
    public function getSponsorRank() {
        return $this->sponsorRank;
    }
    public function getSponsorDonation() {
        return $this->sponsorDonation;
    }
    public function getSponsorDescription() {
        return $this->sponsorDescription;
    }

/* ******** Get post values ******** */
    public function getPostValues() {
        $post_check_array = array (
            'update'        => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'delete'        => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'sponsorname'      => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'sponsorlogo'      => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'sponsoremail' => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'sponsorrank'          => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'sponsordonation'         => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'sponsordescription'        => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'sponsor_id'            => array('filter' => FILTER_VALIDATE_INT)
        );
        $inputs = filter_input_array( INPUT_POST, $post_check_array );
        return $inputs;
    }

/* ******** Get Get values ******** */
    public function getGetValues() {
        $get_check_array = array(
            'action' => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),  
            'sponsor_id' => array('filter' => FILTER_VALIDATE_INT), 
        );
        $inputs = filter_input_array(INPUT_GET, $get_check_array);
        return $inputs;
    }

/* ******** Handles get action ******** */
    public function handleGetAction($get_array) {
        $action = '';
        switch ($get_array['action']) {
            case 'update':
            if (!is_null($get_array['sponsor_id'])) {
                $action = $get_array['action'];
            }
            break;
            case 'delete':
            // Delete current id if provided
            if (!is_null($get_array['sponsor_id'])) {
                $this->deleteSponsor($get_array);
            }
            $action = 'delete';
            break;

            default:
            // Oops
            break;
        }
        // return set action to input variables
        return $action;
    }

/* ******** GETS SPONSOR LIST FROM DB ******** */
    public function getSponsorList(){
        $return_array = array();
        $query = "SELECT * FROM `sponsors` ORDER BY sponsor_id";
        $result = $this->DbConnect()->query($query);
        $this->DbConnect()->close();
        // For all database results:
        foreach ($result as $idx => $array){
            // Nieuw object
            $Sponsor = new Sponsor();
            // Set info
            $Sponsor->setId($array['sponsor_id']);
            $Sponsor->setSponsorName($array['sponsorname']);
            $Sponsor->setSponsorLogo($array['sponsorlogo']);
            $Sponsor->setSponsorEmail($array['sponsoremail']);
            $Sponsor->setSponsorRank($array['sponsorrank']);
            $Sponsor->setSponsorDonation($array['sponsordonation']);
            $Sponsor->setSponsorDescription($array['sponsordescription']);

            // Add new object to return array.
            $return_array[] = $Sponsor;
        }
        return $return_array;
    }

/* ******** GETS CAROUSEL WITH SPONSOR DB INFORMATION ******** */
    public function getSponsorCarousel() {
        $query = "SELECT sponsorlogo FROM `sponsors` ORDER BY sponsor_id";
        $result = $this->DbConnect()->query($query);
        echo '<div class="carousel-inner">';
        $teller = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            // Output the indicator and carousel item for each sponsor
            echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="' . $teller . '"';

            // Set the active class for the first item
            if ($teller == 0) {
                echo ' class="active"';
            }

            echo ' aria-label="Slide ' . ($teller + 1) . '"></button>';

            echo '<div class="carousel-item';

            // Set the active class for the first item
            if ($teller == 0) {
                echo ' active';
            }

            echo '">';
            
            echo '<img src="img/sponsors/' . $row['sponsorlogo'] . '" class="d-block w-100" style="height: 465px;" alt="Slider img">';
            echo '</div>'; // Close the individual carousel item

            $teller++;
        }

        echo '</div>'; // Close the carousel-inner div
        $this->DbConnect()->close();
    }

/* ******** GETS SLIDER WITH SPONSOR INFORMATION ******** */
    public function getSponsorSlider() {
        $query = "SELECT * FROM `sponsors`";
    
        $result = $this->DbConnect()->query($query);
    
        while ($row = mysqli_fetch_assoc($result)) {
            $sponsorName = $row['sponsorname'];

            // Display or process the data
            echo '- '. $sponsorName;
        }
        echo ' -';
    }

/* ******** GETS SPONSOR IN A TABLE ******** */
    public function getSponsorsTable() {
        $query = "SELECT * FROM `sponsors`";
    
        $result = $this->DbConnect()->query($query);
    
        while ($row = mysqli_fetch_assoc($result)) {
            $sponsorId = $row['sponsor_id'];
            $sponsorName = $row['sponsorname'];
            $sponsorLogo = $row['sponsorlogo'];
            $sponsorEmail = $row['sponsoremail'];
            $sponsorRank = $row['sponsorrank'];
            $sponsorDonation = $row['sponsordonation'];
            $sponsorDescription = $row['sponsordescription'];
    
            if($_SERVER['HTTP_HOST'] == 'localhost:8888'){
                // Define local test values if IOS
                $upd_link = 'http://localhost:8888/desoos/admin/sponsors.php' . '?action=update&sponsor_id=' . $sponsorId;
                $del_link = 'http://localhost:8888/desoos/admin/sponsors.php' . '?action=delete&sponsor_id=' . $sponsorId;
            }
            else if($_SERVER['HTTP_HOST'] == 'localhost'){
                // Define local test values
                $upd_link = 'http://localhost/desoos/admin/sponsors.php' . '?action=update&sponsor_id=' . $sponsorId;
                $del_link = 'http://localhost/desoos/admin/sponsors.php' . '?action=delete&sponsor_id=' . $sponsorId;
            }
            else{
                // Define live values
                $upd_link = '' . '?action=update&sponsor_id=' . $sponsorId;
                $del_link = '' . '?action=delete&sponsor_id=' . $sponsorId;
            }

            // Display or process the data
            echo '
            <tbody>
                    <tr>
                        <td>'. $sponsorName .'</td>
                        <td><img src="../img/sponsors/'. $sponsorLogo .'" class="table-image"></td>
                        <td>'. $sponsorEmail .'</td>
                        <td>'. $sponsorRank .'</td>
                        <td>'. $sponsorDonation .'</td>
                        <td>'. $sponsorDescription .'</td>
                        <td><a href="'.$upd_link.'"><svg xmlns="http://www.w3.org/2000/svg" style="color:#232323;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><br>
                        <a onclick="javascript: return confirm(\'weet u zeker dat u deze sponsor wilt verwijderen?\')" href="'.$del_link.'" ><svg xmlns="http://www.w3.org/2000/svg" style="color:#232323;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></td>
                    </tr>
                </tbody>
            ';
        }
    
        $this->DbConnect()->close();
    }

/* ******** ADDS SPONSOR TO DB ******** */
    public function addSponsor($input_array, $target_file) {
        try {
            // redfine values for insert
            if($target_file == $input_array['sponsorlogo']){
            }else{
                $input_array['sponsorlogo'] = $target_file;
            }
            $sponsorName = $input_array['sponsorname'];
            $sponsorLogo = $input_array['sponsorlogo'];
            $sponsorEmail = $input_array['sponsoremail'];
            $sponsorRank = $input_array['sponsorrank'];
            $sponsorDonation = $input_array['sponsordonation'];
            $sponsorDescription = $input_array['sponsordescription'];

            $query = "INSERT INTO `sponsors` (sponsorname,sponsorlogo, sponsoremail, sponsorrank, sponsordonation, sponsordescription) VALUES ('$sponsorName', '$sponsorLogo', '$sponsorEmail', '$sponsorRank', '$sponsorDonation', '$sponsorDescription')";
            $this->DbConnect()->query($query);
            $this->DbConnect()->close();
        } catch (Exception $exc) {
            return FALSE;
        }
        return TRUE;
    }

/* ******** UPLOADS SPONSOR LOGO TO IMG FOLDER ******** */
    public function sponsorLogoUpload() {

        $upload_dir         =  'img';
        $target_dir         = $upload_dir. '/sponsors/';
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
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
        
        // Check if file already exists
        if (file_exists($target_file)) {
            $fileName = $_FILES["fileToUpload"]["name"];
            $file = explode('.',$fileName);

            $rand = rand(1,10000);
            $fileName = $file[0].$rand.'.'.$file[1];

            $target_file = $target_dir . $fileName;
        }
        
        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "svg"
        ) {
            $uploadOk = 4;
        }

        // Check if $uploadOk is set to 0 by an error
        if (in_array($uploadOk, array(0, 2, 3, 4))) {
            var_dump($uploadOk);
            // if everything is ok, try to upload file
        } else {
            
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            } else {
            }
        }
        $target_file = explode( $target_dir,$target_file);
        return array($uploadOk, $target_file);
    }

/* ******** UPDATES SPONSOR IN DB ******** */
    public function updateSponsor($input_array) {
        try {
            $array_fields = array('sponsor_id', 'sponsorname', 'sponsorlogo', 'sponsoremail', 'sponsorrank', 'sponsordonation', 'sponsordescription');
            $data_array = array();

            foreach ($array_fields as $field) {
                if (!isset($input_array[$field])) {
                    throw new Exception($field. " must be filled for update.");
                }
                $data_array[] = $input_array[$field];
            }

            $sponsorId = $input_array['sponsor_id'];
            $sponsorName = $input_array['sponsorname'];
            $sponsorLogo = $input_array['sponsorlogo'];
            $sponsorEmail = $input_array['sponsoremail'];
            $sponsorRank = $input_array['sponsorrank'];
            $sponsorDonation = $input_array['sponsordonation'];
            $sponsorDescription = $input_array['sponsordescription'];
        
            $sql = "UPDATE `sponsors` SET `sponsorname`='$sponsorName',`sponsorlogo`='$sponsorLogo',`sponsoremail`='$sponsorEmail',`sponsorrank`='$sponsorRank',`sponsordonation`='$sponsorDonation',`sponsordescription`='$sponsorDescription' WHERE `sponsor_id`='$sponsorId'";
            $this->DbConnect()->query($sql);
            $this->DbConnect()->close();
        } catch (Exception $e) {
            echo $e->getMessage();
            return FALSE;
        }
        return TRUE;
        
    }

/* ******** UPDATES SPONSOR LOGO IN IMG FOLDER ******** */
    public function sponsorLogoUploadUpdate($input_array) {
        $upload_dir         =  '../img';
        $target_dir         = $upload_dir. '/sponsors/';
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $sponsorLogo        = $input_array['sponsorlogo'];

        // Check if image file is a actual image or fake image
        if($sponsorLogo  != ''){
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
            if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "svg"
            ) {
                $uploadOk = 4;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                $fileName = $_FILES["fileToUpload"]["name"];
                $file = explode('.',$fileName);

                $rand = rand(1,10000);
                $fileName = $file[0].$rand.'.'.$file[1];

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

/* ******** DELETES SPONSOR FROM DB ******** */
    public function deleteSponsor($input_array) {
        try {
            if (!isset($input_array['sponsor_id'])) {
                throw new Exception("Id is not provided");
            }

            $sponsorId = $input_array['sponsor_id'];

            // Get the image filename from the database
            $query = "SELECT sponsorlogo FROM sponsors WHERE sponsor_id = $sponsorId";
            $result = $this->DbConnect()->query($query);
            $row = $result->fetch_assoc();
            $sponsorLogo = $row['sponsorlogo'];
                

            $sql = "DELETE FROM `sponsors` WHERE sponsor_id = ?";
            $stmt = $this->DbConnect()->prepare($sql);
            $stmt->bind_param("i", $sponsorId);
            $stmt->execute();

            // Delete the image from directory
            $upload_dir = '../img';
            $target_dir = $upload_dir . '/sponsors/';
            $image_path = $target_dir . $sponsorLogo;
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            // Refresh the page after successful deletion
            if($_SERVER['HTTP_HOST'] == 'localhost:8888'){
                header("Location: http://localhost:8888/desoos/admin/sponsors.php");
            } if($_SERVER['HTTP_HOST'] == 'localhost'){
                header("Location: http://localhost/desoos/admin/sponsors.php");
            } else { 
                header("Location: sponsors.php");
            }

            exit();
        } catch (Exception $e) {
            error_log("Delete error: <h1 style='color=#fff;'>" . $e->getMessage() . "</h1>");
            return FALSE;
        }
    }

/* ******** GET DROPDOWN WITH RANKS ******** */
    public function getRanksDropdown() {
        echo '
            <label for="sponsorrank">Rank:</label><br/>
            <select name="sponsorrank" id="rank-dropdown" onchange="updateDonationValue()">
            <option id="role-dropdown-content" value="goud" selected>Goud</option>
            <option id="role-dropdown-content" value="zilver">Zilver</option>
            <option id="role-dropdown-content" value="brons">Brons</option>
            </select>
        ';
    } 
}

?>