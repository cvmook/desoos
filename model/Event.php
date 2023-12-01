<?php
include_once("Database.php");

class Event
{

    private $id;
    private $eventname;
    private $eventlocation;
    private $eventage;
    private $eventdate;
    private $eventdescription;
    private $eventlimit;
    protected $db;

    private function DbConnect()
    {
        $this->db = new Database();
        $this->db = $this->db->retObj();
        return $this->db;
    }

    /* ******** GETTERS EN SETTERS ******** */

    public function setId($id)
    {
        $this->id = $id;
    }
    public function setName($eventname)
    {
        $this->eventname = $eventname;
    }
    public function setLocation($eventlocation)
    {
        $this->eventlocation = $eventlocation;
    }
    public function setAge($eventage)
    {
        $this->eventage = $eventage;
    }
    public function setDate($eventdate)
    {
        $this->eventdate = $eventdate;
    }
    public function setDescription($eventdescription)
    {
        $this->eventdescription = $eventdescription;
    }
    public function setLimit($eventlimit)
    {
        $this->eventlimit = $eventlimit;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->eventname;
    }
    public function getLocation()
    {
        return $this->eventlocation;
    }
    public function getAge()
    {
        return $this->eventage;
    }
    public function getDate()
    {
        return $this->eventdate;
    }
    public function getDescription()
    {
        return $this->eventdescription;
    }
    public function getLimit()
    {
        return $this->eventlimit;
    }

    public function getAllEvents()
    {
        // Initialize an empty array to store Event objects
        $return_array = array();

        // SQL query to retrieve all events from the database, ordered by event_id
        $query = "SELECT * FROM `events` ORDER BY event_id";

        // Execute the query using the database connection
        $result = $this->DbConnect()->query($query);

        // Close the database connection
        $this->DbConnect()->close();

        // Iterate through the result set and create Event objects
        foreach ($result as $obj => $array) {
            // Create a new Event object
            $Event = new Event();

            // Set properties of the Event object based on the database result
            $Event->setId($array['event_id']);
            $Event->setName($array['eventname']);
            $Event->setLocation($array['eventlocation']);
            $Event->setAge($array['eventage']);
            $Event->setDate($array['eventdate']);
            $Event->setDescription($array['eventdescription']);
            $Event->setLimit($array['eventlimit']);

            // Add the Event object to the return array
            $return_array[] = $Event;
        }

        // Return the array containing Event objects
        return $return_array;
    }


    /* ******** Get post values ******** */

    public function getPostValues()
    {
        // Define an associative array specifying the expected POST values and their corresponding filters
        $post_check_array = array(
            'register'           => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'update'             => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'delete'             => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'eventname'          => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'eventlocation'      => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'eventage'           => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'eventdate'          => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'eventdescription'   => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'eventlimit'         => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'event_id'           => array('filter' => FILTER_VALIDATE_INT)
        );

        // Use the filter_input_array function to filter and sanitize the POST values based on the defined array
        $inputs = filter_input_array(INPUT_POST, $post_check_array);

        // Return the filtered and sanitized POST values
        return $inputs;
    }

    /* ******** Get Get values ******** */

    public function getGetValues()
    {

        // Define an associative array specifying the expected GET values and their corresponding filters
        $get_check_array = array(
            'action'   => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),
            'event_id' => array('filter' => FILTER_VALIDATE_INT),
        );

        // Use the filter_input_array function to filter and sanitize the GET values based on the defined array
        $inputs = filter_input_array(INPUT_GET, $get_check_array);

        // Return the filtered and sanitized GET values
        return $inputs;
    }

    /* ******** Handles get action ******** */

    public function handleGetAction($get_array)
    {
        // Initialize action variable
        $action = '';

        // Use a switch statement to handle different action cases
        switch ($get_array['action']) {
            case 'update':
                // Check if 'event_id' is provided for the 'update' action
                if (!is_null($get_array['event_id'])) {
                    // Set action to 'update'
                    $action = $get_array['action'];
                }
                break;

            case 'register':
                // Check if 'event_id' is provided for the 'register' action
                if (!is_null($get_array['event_id'])) {
                    // Set action to 'register'
                    $action = $get_array['action'];
                }
                break;

            case 'delete':
                // Delete current id if provided for the 'delete' action
                if (!is_null($get_array['event_id'])) {
                    $this->deleteEvent($get_array);
                }
                // Set action to 'delete'
                $action = 'delete';
                break;

            default:
                $action = '';
                break;
        }

        // Return the determined action
        return $action;
    }

    public function getNrOfRegistrations($eventId)
    {
        // SQL query to count registrations for a specific event
        $query = "SELECT COUNT(registrations.registration_id) AS aantal_aanmeldingen
    FROM `events`
    LEFT JOIN `events-registrations` ON events.event_id = `events-registrations`.event_id
    LEFT JOIN `registrations` ON `events-registrations`.registration_id = registrations.registration_id
    WHERE events.event_id = $eventId";

        // Execute the query
        $result = $this->DbConnect()->query($query);

        // Check if the query was successful
        if ($result) {
            // Fetch the result row
            $row = $result->fetch_assoc();

            // Return the number of registrations
            return $row['aantal_aanmeldingen'];
        } else {
            // Handle the query error
            echo "Query failed: " . $this->DbConnect()->error;

            // Return false on query failure
            return false;
        }
    }

    /* ******** Get events table ******** */
    public function getEventsTable()
    {
        // SQL query to select all events from the 'events' table, ordered by event_id
        $query = "SELECT * FROM `events` ORDER BY event_id";

        // Execute the query using the DbConnect method (assumed to be a method in your class)
        $result = $this->DbConnect()->query($query);

        // Loop through each row in the result set
        while ($row = mysqli_fetch_assoc($result)) {
            // Extract event details from the row
            $eventId = $row['event_id'];
            $eventName = $row['eventname'];
            $eventLocation = $row['eventlocation'];
            $eventAge = $row['eventage'];
            $eventDate = $row['eventdate'];
            $eventDescription = $row['eventdescription'];
            $eventLimit = $row['eventlimit'];

            // Define links based on the server environment (local or live)
            if ($_SERVER['HTTP_HOST'] == 'localhost:8888') {
                // Define local test values if IOS
                $upd_link = 'http://localhost:8888/desoos/admin/events.php' . '?action=update&event_id=' . $eventId;
                $reg_link = 'http://localhost:8888/desoos/admin/registrations.php' . '?action=registrations&event_id=' . $eventId;
                $del_link = 'http://localhost:8888/desoos/admin/events.php' . '?action=delete&event_id=' . $eventId;
            } else if ($_SERVER['HTTP_HOST'] == 'localhost') {
                // Define local test values
                $upd_link = 'http://localhost/desoos/admin/events.php' . '?action=update&event_id=' . $eventId;
                $reg_link = 'http://localhost:8888/desoos/admin/registrations.php' . '?action=registrations&event_id=' . $eventId;
                $del_link = 'http://localhost/desoos/admin/events.php' . '?action=delete&event_id=' . $eventId;
            } else {
                // Define live values
                $upd_link = '' . '?action=update&event_id=' . $eventId;
                $reg_link = 'registrations.php' . '?action=registrations&event_id=' . $eventId;
                $del_link = '' . '?action=delete&event_id=' . $eventId;
            }

            // Get the number of registrations for the current event
            $nrOfRegistrations = $this->getNrOfRegistrations($eventId);

            // Display or process the data in an HTML table row
            echo '
        <tbody>
                <tr>
                    <td>' . $eventName . '</td>
                    <td>' . $nrOfRegistrations . '</td>
                    <td>' . $eventLocation . '</td>
                    <td>' . $eventAge . '</td>
                    <td>' . $eventDate . '</td>
                    <td>' . $eventDescription . '</td>
                    <td>' . $eventLimit . '</td>
                    <td><a href="' . $upd_link . '"><svg xmlns="http://www.w3.org/2000/svg" style="color:#232323;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><br>
                    <a onclick="javascript: return confirm(\'weet u zeker dat u deze activiteit wilt verwijderen?\')" href="' . $del_link . '"><svg xmlns="http://www.w3.org/2000/svg" style="color:#232323;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a><br>
                    <a href="' . $reg_link . '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#232323" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg></a></td>
                </tr>
            </tbody>
        ';
        }

        // Close the database connection
        $this->DbConnect()->close();
    }


    // Function to retrieve events data from the database and display it in a simplified frontend table
    public function getFrontendEventsTable()
    {
        // SQL query to select all events from the 'events' table, ordered by event_id
        $query = "SELECT * FROM `events` order by event_id";

        // Execute the query using the DbConnect method (assumed to be a method in your class)
        $result = $this->DbConnect()->query($query);

        // Loop through each row in the result set
        while ($row = mysqli_fetch_assoc($result)) {
            // Extract event details from the row
            $eventId = $row['event_id'];
            $eventName = $row['eventname'];
            $eventLocation = $row['eventlocation'];
            $eventAge = $row['eventage'];
            $eventDate = $row['eventdate'];
            $eventDescription = $row['eventdescription'];
            $eventLimit = $row['eventlimit'];

            // Define registration link based on the server environment (local or live)
            if ($_SERVER['HTTP_HOST'] == 'localhost:8888') {
                // Define local test values if IOS
                $reg_link = 'http://localhost:8888/desoos/public/events.php' . '?action=register&event_id=' . $eventId . "#register";
            } else if ($_SERVER['HTTP_HOST'] == 'localhost') {
                // Define local test values
                $reg_link = 'http://localhost/desoos/public/events.php' . '?action=register&event_id=' . $eventId . "#register";
            } else {
                // Define live values
                $reg_link = '' . '?action=register&event_id=' . $eventId . "#register";
            }

            // Display or process the data in an HTML table row
            echo "
            <tbody class=eventsfrontend>
            <tr>
                <td class=eventrow>" . $eventName . "</b></td>
                <td class=eventrow><i class=fa-solid fa-location-dot></i>" . $eventLocation . "</td>
                <td class=eventrow>Leeftijd tot: " . $eventAge . "</td>
                <td class=eventrow><details><summary>
                <div class=buttonmodal>
                Meer info</div>
                <div class=details-modal-overlay></div></summary>

                <div class=details-modal>
                
                <div class=details-modal-title>
                <div class=details-container>
                <div class=col-sm-6>
                <p class=eventinfo> Activiteit: $eventName</p>
                <p class=eventinfo> Locatie: $eventLocation</p>
                <p class=eventinfo> Leeftijd: $eventAge </p>
                <p class=eventinfo> Datum en tijd: $eventDate </p>
                <p class=eventinfo> Max aantal deelnemers: $eventLimit</p>
                </div>
                <div class=col-sm-6>
                <p class=beschrijving> Beschrijving:<br> $eventDescription</p>
                <a class=eventinfo href='$reg_link'>Registreren</a>
                </div>
                </div>
                </div>
            
     </td>
                <div class=buttonmodal>
                <td class=eventrow><a href='$reg_link'>Registreren</a><br></td>
                </div>
            </tr>
        </tbody>
    ";
        }

        // Close the database connection
        $this->DbConnect()->close();
    }


    // Function to add a new event to the 'events' table in the database
    public function addEvent($input_array)
    {
        try {
            // SQL query to insert a new event into the 'events' table
            $sql = "INSERT INTO `events` SET eventname='" . $input_array['eventname'] . "', eventlocation='" . $input_array['eventlocation'] . "', 
        eventage='" . $input_array['eventage'] . "', eventdate='" . $input_array['eventdate'] . "', eventdescription='" . $input_array['eventdescription'] . "', eventlimit='" . $input_array['eventlimit'] . "';";

            // Execute the query using the DbConnect method (assumed to be a method in your class)
            if (!$this->DbConnect()->query($sql)) {
                // If the query fails, return FALSE
                return FALSE;
            } else {
                // If the query is successful, return TRUE
                return TRUE;
            }

            // Close the database connection (this part will not be reached because it comes after the return statements)
            $this->DbConnect()->close();
        } catch (Exception $exc) {
            // If an exception occurs, return FALSE
            return FALSE;
        }

        // This statement will never be reached due to the return statements above
        return TRUE;
    }


    /* ******** Update Events ******** */

    // Function to add a new registration to the 'registrations' table in the database
    public function addRegistration($input_array)
    {
        try {
            // Display the input array for debugging purposes
            var_dump($input_array);

            // SQL query to insert a new registration into the 'registrations' table
            $sql = "INSERT INTO `registrations` SET registrationname='" . $input_array['registrationname'] . "', 
        registrationemail='" . $input_array['registrationemail'] . "', registrationphone='" . $input_array['registrationphone'] . "';";

            // Execute the query using the DbConnect method (assumed to be a method in your class)
            if (!$this->DbConnect()->query($sql)) {
                // If the query fails, return FALSE
                return FALSE;
            } else {
                // If the query is successful, return TRUE
                return TRUE;
            }

            // Close the database connection (this part will not be reached because it comes after the return statements)
            $this->DbConnect()->close();
        } catch (Exception $exc) {
            // If an exception occurs, return FALSE
            return FALSE;
        }

        // This statement will never be reached due to the return statements above
        return TRUE;
    }


    /* ******** Update Events ******** */

    // Function to update an existing event in the 'events' table
    public function updateEvent($input_array)
    {
        try {
            // Define an array of fields that must be present in the input for updating
            $array_fields = array('event_id', 'eventname', 'eventlocation', 'eventage', 'eventdate', 'eventdescription', 'eventlimit');
            $data_array = array();

            // Loop through the fields and check if they are present in the input_array
            foreach ($array_fields as $field) {
                // If a required field is missing, throw an exception
                if (!isset($input_array[$field])) {
                    throw new Exception($field . " must be filled for update.");
                }
                // Add the field value to the data_array
                $data_array[] = $input_array[$field];
            }

            // Extract individual fields from the input_array for better readability
            $eventId = $input_array['event_id'];
            $eventName = $input_array['eventname'];
            $eventLocation = $input_array['eventlocation'];
            $eventAge = $input_array['eventage'];
            $eventDate = $input_array['eventdate'];
            $eventDescription = $input_array['eventdescription'];
            $eventLimit = $input_array['eventlimit'];

            // SQL query to update the event in the 'events' table
            $sql = "UPDATE `events` SET `eventname`='$eventName',`eventlocation`='$eventLocation',`eventage`='$eventAge',`eventdate`='$eventDate',`eventdescription`='$eventDescription', `eventlimit`='$eventLimit' WHERE `event_id`='$eventId'";

            // Execute the query using the DbConnect method (assumed to be a method in your class)
            $this->DbConnect()->query($sql);

            // Close the database connection
            $this->DbConnect()->close();
        } catch (Exception $e) {
            // If an exception occurs, echo the error message and return FALSE
            echo $e->getMessage();
            return FALSE;
        }

        // Return TRUE if the update is successful
        return TRUE;
    }


    // Function to delete an event from the 'events' table
    public function deleteEvent($input_array)
    {
        try {
            // Check if the 'event_id' is provided in the input_array
            if (!isset($input_array['event_id'])) {
                // If 'event_id' is not provided, throw an exception
                throw new Exception("Id is not provided");
            }

            // Extract the event_id from the input_array
            $eventId = $input_array['event_id'];

            // SQL query to delete the event from the 'events' table using prepared statements
            $sql = "DELETE FROM events WHERE event_id = ?";
            $stmt = $this->DbConnect()->prepare($sql);
            $stmt->bind_param("i", $eventId);
            $result = $stmt->execute();

            // Refresh the page after successful deletion based on the server environment
            if ($_SERVER['HTTP_HOST'] == 'localhost:8888') {
                header("Location: http://localhost:8888/desoos/admin/events.php");
            } else if ($_SERVER['HTTP_HOST'] == 'localhost') {
                header("Location: http://localhost/desoos/admin/events.php");
            } else {
                header("Location: events.php");
            }


            // Exit to prevent further execution after redirection
            exit();
        } catch (Exception $e) {
            // Log the error message and return FALSE if an exception occurs
            error_log("Delete error: <h1 style='color=#fff;'>" . $e->getMessage() . "</h1>");
            return FALSE;
        }
    }
}
