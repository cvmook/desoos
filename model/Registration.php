<?php
include_once("Database.php");
include_once("Event.php");


class Registration
{

    private $registrationId;
    private $registrationName;
    private $registrationEmail;
    private $registrationPhone;
    protected $db;

    private function DbConnect()
    {
        $this->db = new Database();
        $this->db = $this->db->retObj();
        return $this->db;
    }

    /* ******** GETTERS EN SETTERS ******** */

    /* ******** SETTERS ******** */
    public function setId($registrationId)
    {
        $this->registrationId = $registrationId;
    }
    public function setName($registrationName)
    {
        $this->registrationName = $registrationName;
    }
    public function setEmail($registraionEmail)
    {
        $this->registrationEmail = $registraionEmail;
    }
    public function setPhone($registrationPhone)
    {
        $this->registrationPhone = $registrationPhone;
    }

    /* ******** GETTERS ******** */
    public function getId()
    {
        return $this->registrationId;
    }
    public function getName()
    {
        return $this->registrationName;
    }
    public function getEmail()
    {
        return $this->registrationEmail;
    }
    public function getPhone()
    {
        return $this->registrationPhone;
    }

    /* ******** Get registration id ******** */
    private function getRegistrationId($criteria)
    {
        $connection = $this->DbConnect();

        // Use a SELECT query to retrieve the userid based on the criteria.
        $query = "SELECT registration_id FROM `registrations` WHERE registrationname = ?";

        // Prepare the statement
        $stmt = mysqli_prepare($connection, $query);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, 's', $criteria);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Bind the result
        mysqli_stmt_bind_result($stmt, $registrationId);

        // Fetch the result
        mysqli_stmt_fetch($stmt);

        // Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        return $registrationId;
    }

    /* ******** Get event limit ******** */
    /**
     * Get the event limit for a specific event based on the provided event ID.
     * 
     * @return int|null The event limit, or null if no limit is found.
     */
    public function getEventLimit()
    {
        // Retrieve the event ID from the POST data
        $eventId = $_POST['event_id'];

        // SQL query to select the event limit for the given event ID
        $query = "SELECT eventlimit FROM `events` WHERE event_id = $eventId";

        // Execute the query and retrieve the result
        $result = $this->DbConnect()->query($query);

        // Close the database connection
        $this->DbConnect()->close();

        // Initialize the eventLimit variable
        $eventLimit = null;

        // Loop through the result set and extract the event limit
        while ($row = mysqli_fetch_assoc($result)) {
            $eventLimit = $row['eventlimit'];
        }

        // Return the event limit (or null if not found)
        return $eventLimit;
    }


    /* ******** Get all registrations ******** */
    public function getAllRegistrations()
    {
        // Initialize an empty array to store Event objects
        $return_array =  array();
        // SQL query to retrieve all events from the database, ordered by registration_id
        $query = "SELECT * FROM `registrations` ORDER BY registration_id";
        // Execute the query using the database connection
        $result = $this->DbConnect()->query($query);
        // Close the database connection
        $this->DbConnect()->close();
        // Iterate through the result set and create Registration objects
        foreach ($result as $obj => $array) {
            // Create a new Registration object
            $Registration = new Registration();

            // Set properties of the Registration object based on the database result
            $Registration->setId($array['registration_id']);
            $Registration->setName($array['registrationname']);
            $Registration->setEmail($array['registrationemail']);
            $Registration->setPhone($array['registrationphone']);
            // Add the Registration object to the return array
            $return_array[] = $Registration;
        }
        // Return the array containing Registration objects
        return $return_array;
    }

    /* ******** Get post values ******** */

    /**
     * Retrieve and sanitize POST values for various form inputs.
     * 
     * @return array Sanitized POST values.
     */
    public function getPostValues()
    {
        // Define an array with keys representing form input names
        // and values indicating the filter to be applied during sanitization.
        $post_check_array = array(
            'register'             => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'update'               => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'delete'               => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'registrationname'     => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'registrationemail'    => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'registrationphone'    => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'registration_id'      => array('filter' => FILTER_VALIDATE_INT)
        );

        // Use the filter_input_array function to apply the specified filters
        // to the corresponding POST values, sanitizing and validating them.
        $inputs = filter_input_array(INPUT_POST, $post_check_array);

        // Return the sanitized POST values.
        return $inputs;
    }

    /* ******** Get Get values ******** */

    /**
     * Retrieve and sanitize GET values for various parameters.
     * 
     * @return array Sanitized GET values.
     */
    public function getGetValues()
    {
        // Define an array with keys representing GET parameter names
        // and values indicating the filter to be applied during sanitization.
        $get_check_array = array(
            'action'           => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'registration_id'  => array('filter' => FILTER_VALIDATE_INT),
        );

        // Use the filter_input_array function to apply the specified filters
        // to the corresponding GET parameters, sanitizing and validating them.
        $inputs = filter_input_array(INPUT_GET, $get_check_array);

        // Return the sanitized GET values.
        return $inputs;
    }


    /* ******** Handles get action ******** */

    /**
     * Handle actions based on the provided GET parameters.
     * 
     * @param array $get_array The array of GET parameters.
     * @return string The action performed.
     */
    public function handleGetAction($get_array)
    {
        // Initialize action variable
        $action = '';

        // Switch statement to handle different actions based on 'action' parameter
        switch ($get_array['action']) {
            case 'delete':
                // Delete registration if 'registration_id' is provided
                if (!is_null($get_array['registration_id'])) {
                    $this->deleteRegistration($get_array);
                }
                // Set action to 'delete'
                $action = 'delete';
                break;

            default:
                // Handle unexpected or undefined actions
                // You might want to log or handle this case accordingly
                break;
        }

        // Return the action performed
        return $action;
    }

    /* ******** GET REGISTRATIONS PER EVENT ******** */
    /**
     * Display a table of registrations for a specific event.
     */
    public function getRegistrationsTable()
    {
        // Get the event ID from the GET parameters
        $eventId = $_GET['event_id'];

        // SQL query to fetch registrations and related details for the specified event
        $query = "SELECT r.registration_id, r.registrationname, r.registrationemail, r.registrationphone
            FROM `events-registrations` er
            JOIN `registrations` r ON er.registration_id = r.registration_id
            WHERE er.event_id = '$eventId'";

        // Execute the query
        $result = $this->DbConnect()->query($query);

        // Start the HTML table body
        echo "<tbody>";

        // Loop through the result set and display registration details in a table row
        while ($row = mysqli_fetch_assoc($result)) {
            $registrationId = $row['registration_id'];
            $registrationName = $row['registrationname'];
            $registrationEmail = $row['registrationemail'];
            $registrationPhone = $row['registrationphone'];

            // Define the delete link based on the server environment
            if ($_SERVER['HTTP_HOST'] == 'localhost:8888') {
                $del_link = 'http://localhost:8888/desoos/admin/registrations.php' . '?action=delete&registration_id=' . $registrationId;
            } else if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $del_link = 'http://localhost/desoos/admin/registrations.php' . '?action=delete&registration_id=' . $registrationId;
            } else {
                $del_link = '' . '?action=delete&registration_id=' . $registrationId;
            }

            // Display the registration details in a table row
            echo '
            <tr>
                <td>' . $registrationName . '</td>
                <td>' . $registrationEmail . '</td>
                <td>' . $registrationPhone . '</td>
                <td><a onclick="javascript: return confirm(\'Weet u zeker dat u deze aanmelding wilt verwijderen?\')" href="' . $del_link . '"><svg xmlns="http://www.w3.org/2000/svg" style="color:#232323;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></td>
            </tr>
        ';
        }

        // Close the HTML table body
        echo "</tbody>";

        // Close the database connection
        $this->DbConnect()->close();
    }


    /* ******** ADD REGISTRATION ******** */
/**
 * Add a new registration for an event.
 * 
 * @param array $input_array The array containing input values for the registration.
 * @return bool Whether the registration was successfully added or not.
 */
public function addRegistration($input_array)
{
    try {
        // Create a new Event instance
        $Event = new Event();

        // Get the event ID from the POST parameters
        $eventId = $_POST['event_id'];

        // Get the number of existing registrations for the event
        $nrOfRegistrations = $Event->getNrOfRegistrations($eventId);

        // Get the event limit
        $eventLimit = $this->getEventLimit();

        // Sanitize input values
        $registrationName = htmlspecialchars($input_array['registrationname']);
        $registrationEmail = htmlspecialchars($input_array['registrationemail']);
        $registrationPhone = htmlspecialchars($input_array['registrationphone']);

        // Check if the event has reached its registration limit
        if ($nrOfRegistrations >= $eventLimit) {
            // Display an error message if the limit is reached
            echo "<div class='alert alert-danger' style='text-align: center;'>Aanmelding mislukt, deze activiteit zit vol.</div>";
            return $result = FALSE;
        } else {
            // Insert the new registration into the 'registrations' table
            $sql = "INSERT INTO `registrations` SET registrationname='" . $registrationName . "', 
            registrationemail='" . $registrationEmail . "', registrationphone='" . $registrationPhone . "';";

            // Execute the SQL query
            if (!$this->DbConnect()->query($sql)) {
                // Return FALSE if the query execution fails
                return FALSE;
            } else {
                // Return TRUE if the registration is successfully added
                return TRUE;
            }

            // Close the database connection (Note: This part will not be executed due to the previous 'return' statements)
            $this->DbConnect()->close();
        }
    } catch (Exception $exc) {
        // Return FALSE if an exception occurs
        return FALSE;
    }

    // Return TRUE by default
    return TRUE;
}


    /* ******** INSERT JUNCTION TABLE ******** */
    public function insertRegistrationjunctiontable()
    {
        $connection = $this->DbConnect();

        // Check if the POST variables are set and not empty.
        if (isset($_POST['event_id']) && !empty($_POST['event_id'])) {
            // Retrieve the appropriate fileid from the 'gallery' table based on some criteria.
            $eventId = intval($_POST['event_id']);
            $registrationId = $this->getRegistrationId($_POST['registrationname']);

            if ($registrationId !== null) {
                $Event = new Event();
                $eventId = $_POST['event_id'];
                $nrOfRegistrations = $Event->getNrOfRegistrations($eventId);
                $eventLimit = $this->getEventLimit();
                if ($nrOfRegistrations >= $eventLimit) {
                   return $result = FALSE;
                } else {
                    // Use prepared statement to insert data into the junction table.
                    $sql = "INSERT INTO `events-registrations` (event_id, registration_id) VALUES (?, ?)";

                    // Prepare the statement
                    $stmt = mysqli_prepare($connection, $sql);

                    // Bind parameters (corrected line)
                    mysqli_stmt_bind_param($stmt, 'ii', $eventId, $registrationId);

                    // Execute the statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Insertion was successful
                    } else {
                        // Insertion failed
                        echo '
                        <div class="alert alert-danger" role="alert" style="text-align: center;">
                        Er is iets fout gegaan tijdens het aanmelden, neem a.u.b. contact op.
                        </div>
                        ';
                    }
                    // Close the statement and connection
                    mysqli_stmt_close($stmt);
                    $this->DbConnect()->close();
                }
            } else {
            }

            // Close the database connection
            mysqli_close($connection);
        } else {
            echo "event_id must be set and not empty.";
        }
    }

    /* ******** DELETE REGISTRATION ******** */
    /**
 * Delete a registration from the 'registrations' table.
 * 
 * @param array $input_array The array containing input values for the registration to be deleted.
 * @return bool Whether the registration was successfully deleted or not.
 */
public function deleteRegistration($input_array)
{
    try {
        // Check if the registration ID is provided
        if (!isset($input_array['registration_id'])) {
            // Throw an exception if the ID is not provided
            throw new Exception("Id is not provided");
        }

        // Get the registration ID from the input array
        $registrationId = $input_array['registration_id'];

        // SQL query to delete the registration
        $sql = "DELETE FROM registrations WHERE registration_id = ?";

        // Prepare the SQL statement
        $stmt = $this->DbConnect()->prepare($sql);

        // Bind the registration ID parameter
        $stmt->bind_param("i", $registrationId);

        // Execute the SQL query
        $stmt->execute();

        //Refresh the page after successful deletion based on the server environment
        if ($_SERVER['HTTP_HOST'] == 'localhost:8888') {
            header("Location: http://localhost:8888/desoos/admin/events.php");
        }
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            header("Location: http://localhost/desoos/admin/events.php");
        } else {
            header('Location: events.php');
        }

        // Exit to prevent further execution
        exit();
    } catch (Exception $e) {
        // Log the error message and return FALSE if an exception occurs
        error_log("Delete error: <h1 style='color=#fff;'>" . $e->getMessage() . "</h1>");
        return FALSE;
    }
}

}
