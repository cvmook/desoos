<?php
include_once("Database.php");

define('hash1','$)(#KLASD@((UOJRC$2#24edA');
define('hash2','(#$#UIJD()PI#O$J#@(#$@PP$');

class User {
/* ******** ATTRIBUTES ******** */
    private $userId;
    private $userUsername;
    private $userPassword;
    private $userName;
    private $userToken;
    protected $db;

/* ******** CONNECTS TO THE DATABASE ******** */
    private function DbConnect() {
        $this->db = new Database();
        $this->db = $this->db->retObj();
        return $this->db;
    }

/* ******** GETTERS EN SETTERS ******** */
    public function setUserId($userId) {
        $this->userId = $userId;
    }
    public function setUserUsername($userUsername) {
        $this->userUsername = $userUsername;
    }
    public function setUserPassword($userPassword) {
        $this->userPassword = $userPassword;
    }
    public function setUserName($userName) {
        $this->userName = $userName;
    }
    public function setUserToken($userToken) {
        $this->userToken = $userToken;
    }
    public function getUserId() {
        return $this->userId;
    }
    public function getUserUsername() {
        return $this->userUsername;
    }
    public function getUserPassword() {
        return $this->userPassword;
    }
    public function getUserName() {
        return $this->userName;
    }
    public function getUserToken() {
        return $this->userToken;
    }

/* ******** FETCHES ALL USERS ******** */
    public function getAllUsers() {
        $return_array =  array();

        $query = "SELECT * FROM `users` ORDER BY id";
        $result = $this->DbConnect()->query($query);
        $this->DbConnect()->close();
        foreach($result as $obj => $array){
            $User = new User();
            
            $User->setUserId($array['id']);
            $User->setUserUsername($array['username']);    
            $User->setUserName($array['name']);
            $User->setUserToken($array['token']);

            $return_array[] = $User;
        }
        return $return_array;
    }

/* ******** FETCHES USERS BASED ON USERNAME ******** */
    public function getUserListByUsername($input_array){
        $return_array =  array();
        
        $query = "SELECT * FROM `users` WHERE `username` = '".$input_array['username']."' ORDER BY id";
        $result = $this->DbConnect()->query($query);
        $this->DbConnect()->close();
        foreach($result as $obj => $array){
            $User = new User();
            
            $User->setUserId($array['id']);
            $User->setUserUsername($array['username']);
            $User->setUserName($array['name']);
            $User->setUserToken($array['token']);

            $return_array[] = $User;
        }
        return $return_array;
    }

/* ******** GETS POST VALUES ******** */
    public function getPostValues() {

        $post_check_array = array (
            'update'        => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'delete'        => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'username'      => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'password'      => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'passwordCheck' => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'name'          => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'token'         => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'role_id'        => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'id'            => array('filter' => FILTER_VALIDATE_INT)
        );
        $inputs = filter_input_array( INPUT_POST, $post_check_array );
        return $inputs;

    }

/* ******** GETS GET VALUES ******** */
    public function getGetValues() {

        $get_check_array = array(
            'action' => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),  
            'id' => array('filter' => FILTER_VALIDATE_INT), 
        );
        $inputs = filter_input_array(INPUT_GET, $get_check_array);
        return $inputs;

    }

/* ******** HANDLES GET ACTION ******** */
    public function handleGetAction($get_array) {
        $action = '';
        switch ($get_array['action']) {
            case 'update':
            if (!is_null($get_array['id'])) {
                $action = $get_array['action'];
            }
            break; 
        
            case 'delete':
            // Delete current id if provided
            if (!is_null($get_array['id'])) {
                $this->deleteUser($get_array);
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

/* ******** FETCHES USER ID ******** */
    private function getUserId1($criteria) {
        $connection = $this->DbConnect();
    
        // Use a SELECT query to retrieve the userid based on the criteria.
        $query = "SELECT id FROM `users` WHERE name = ?";
        
        // Prepare the statement
        $stmt = mysqli_prepare($connection, $query);
    
        // Bind parameters
        mysqli_stmt_bind_param($stmt, 's', $criteria);
    
        // Execute the statement
        mysqli_stmt_execute($stmt);
    
        // Bind the result
        mysqli_stmt_bind_result($stmt, $userId);
    
        // Fetch the result
        mysqli_stmt_fetch($stmt);
    
        // Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($connection);
    
        return $userId;
    }

/* ******** FETCHES ROLE ID BASED ON THE USER ******** */
    public function getRoleIdByUserId() {
        $userId = $_SESSION['id'];
        $query = "SELECT users.id AS user_id, roles.id AS role_id FROM `users`
        LEFT JOIN `users-roles` ON users.id = `users-roles`.users_id
        LEFT JOIN `roles` ON `users-roles`.role_id = roles.id
        WHERE users.id = $userId;";
    
        $result = $this->DbConnect()->query($query);
        $row = $result->fetch_assoc();
        $roleId = $row['role_id'];
        return $roleId;
        $this->DbConnect()->close();
    }

/* ******** GETS USER TABLE WITH DATABASE INFORMATION ******** */
    public function getUsersTable() {
        $query = "SELECT users.id, users.username, users.name, roles.rolename
                  FROM `users`
                  LEFT JOIN `users-roles` ON users.id = `users-roles`.users_id
                  LEFT JOIN `roles` ON `users-roles`.role_id = roles.id";
    
        $result = $this->DbConnect()->query($query);
    
        while ($row = mysqli_fetch_assoc($result)) {
            $userId = $row['id'];
            $username = $row['username'];
            $name = $row['name'];
            $rolename = $row['rolename'];
    
            if($_SERVER['HTTP_HOST'] == 'localhost:8888'){
                // Define local test values if IOS
                $upd_link = 'http://localhost:8888/desoos/admin/users.php' . '?action=update&id=' . $userId;
                $del_link = 'http://localhost:8888/desoos/admin/users.php' . '?action=delete&id=' . $userId;
            }
            else if($_SERVER['HTTP_HOST'] == 'localhost'){
                // Define local test values
                $upd_link = 'http://localhost/desoos/admin/users.php' . '?action=update&id=' . $userId;
                $del_link = 'http://localhost/desoos/admin/users.php' . '?action=delete&id=' . $userId;
            }
            else{
                // Define live values
                $upd_link = '' . '?action=update&id=' . $userId;
                $del_link = '' . '?action=delete&id=' . $userId;
            }

            // Display or process the data
            echo '
            <tbody>
                    <tr>
                        <td>'. $username .'</td>
                        <td>'. $name .'</td>
                        <td>'. $rolename .'</td>
                        <td><a href="'.$upd_link.'"><svg xmlns="http://www.w3.org/2000/svg" style="color:#232323;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><br>
                        <a onclick="javascript: return confirm(\'weet u zeker dat u deze gebruiker wilt verwijderen?\')" href="'.$del_link.'" ><svg xmlns="http://www.w3.org/2000/svg" style="color:#232323;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></td>
                    </tr>
                </tbody>
            ';
        }
    
        $this->DbConnect()->close();
    }

/* ******** INSERTS INTO THE USERS-ROLES JUNCTION TABLE ******** */
    public function insertRolesjunctiontable() {
        $connection = $this->DbConnect();
    
        // Check if the POST variables are set and not empty.
        if (isset($_POST['role_id']) && !empty($_POST['role_id'])) {
            // Retrieve the appropriate fileid from the 'gallery' table based on some criteria.
            $roleId = $_POST['role_id'];
            $userId = $this->getUserId1($_POST['name']);
    
            if ($userId !== null) {
                // Use prepared statement to insert data into the junction table.
                $sql = "INSERT INTO `users-roles` (role_id, users_id) VALUES (?, ?)";
    
                // Prepare the statement
                $stmt = mysqli_prepare($connection, $sql);
    
                // Bind parameters
                mysqli_stmt_bind_param($stmt, 'ii', $roleId, $userId);
    
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
                
            }
    
            // Close the database connection
            mysqli_close($connection);
        } else {
            echo "locatieid must be set and not empty.";
        }
    }

/* ******** ADDS USERS TO USERS TABLE ******** */
    public function addUser($input_array) {
        $input_array['password'] = md5(hash1 . $input_array['password'] . hash2);
        $input_array['token'] = '';
        
        try {
            $sql = "SELECT COUNT(`username`) as nr FROM `users` WHERE `username` = '".$input_array['username']."';";
            $result = $this->DbConnect()->query($sql);
            $row = $result->fetch_assoc();
            $nr = $row['nr'];
            
            if ($nr < 1) {
                // echo $input_array['username']. $input_array['password']. $input_array['name'];
                $sql = "INSERT INTO `users` SET username='".$input_array['username']."', 
                password='".$input_array['password']."', name='".$input_array['name']."';";
                    
                if (!$this->DbConnect()->query($sql)) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            } else {
                return FALSE;
            }
            $this->DbConnect()->close();
        } catch (Exception $exc) {
            return FALSE;
        }
        return TRUE;
    }

/* ******** UPDATES THE USERS TABLE ******** */
    public function update($input_array) {
        try {
            $array_fields = array('id', 'username', 'name');
            $data_array = array();

            foreach ($array_fields as $field) {
                if (!isset($input_array[$field])) {
                    throw new Exception($field. " must be filled for update.");
                }
                $data_array[] = $input_array[$field];
            }
                
            $id = $input_array['id'];
            $username = $input_array['username'];
            $name = $input_array['name'];
        
            $sql = "UPDATE `users` SET `username`='$username',`name`='$name' WHERE `id`='$id'";
            $this->DbConnect()->query($sql);
            $this->DbConnect()->close();
        } catch (Exception $e) {
            echo $e->getMessage();
            return FALSE;
        }
        
        return TRUE;
        
    }

/* ******** UPDATES THE USERS-ROLES JUNCTION TABLE ******** */
    public function updateJunctionTable($input_array) {
        try {
            $id = $input_array['id'];
            $roleId = $input_array['role_id'];
        
            $sql = "UPDATE `users-roles` SET `role_id`='$roleId' WHERE `users_id`='$id'"; 
            $this->DbConnect()->query($sql);
            $this->DbConnect()->close();
        } catch (Exception $e) {
            echo $e->getMessage();
            return FALSE;
        }
        
        return TRUE;
    }

/* ******** DELETES USERS FROM USERS DATABASE ******** */
    public function deleteUser($input_array) {
        try {
            if (!isset($input_array['id'])) {
                throw new Exception("Id is not provided");
            }

            $id = $input_array['id'];

            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $this->DbConnect()->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            // Refresh the page after successful deletion
            if($_SERVER['HTTP_HOST'] == 'localhost:8888'){
                header("Location: http://localhost:8888/desoos/admin/users.php");
            } if($_SERVER['HTTP_HOST'] == 'localhost'){
                header("Location: http://localhost/desoos/admin/users.php");
            } else {
                header("Location: users.php");
            }

            exit();
        } catch (Exception $e) {
            error_log("Delete error: <h1 style='color=#fff;'>" . $e->getMessage() . "</h1>");
            return FALSE;
        }
    }

/* ******** PUTS ROLES FROM DATABASE IN A DROPDOWN ******** */
    public function getRolesDropdown() {
        $sql = "SELECT * FROM `roles`";
        $result = mysqli_query($this->DbConnect(), $sql);
        echo '<br/><select id="role-dropdown" class="form-control" name="role_id">';
            while($row = mysqli_fetch_array($result)) { 
                echo '<option id="role-dropdown-content" value="'.$row['id'].'">'.$row['rolename'].'</option>';
            }
        echo '</select>';
    }

/* ******** CHECKS THE LOGIN AND SETS TOKEN ******** */
    public function checkLogin($input_array) {

        $user_data = array();
        $password = htmlspecialchars(md5(hash1.$input_array['password'].hash2));
        $username = htmlspecialchars($input_array['username']);
    
        $query = "SELECT id from `users` WHERE username='$username' AND password='$password'";

        $result = $this->DbConnect()->query($query) or die($this->DbConnect()->error);
        $this->DbConnect()->close();
        $user_data = $result->fetch_All(MYSQLI_ASSOC);
        $count_row = $result->num_rows;
        if ($count_row == 1) {
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $token =  substr(str_shuffle($permitted_chars), 0, 250);
            $sql = "UPDATE `users` SET token = '$token' WHERE id = ".$user_data[0]['id'].";";
            $result = $this->DbConnect()->query($sql);
            $this->DbConnect()->close();
            $_SESSION['id'] = $user_data[0]['id'];
            $session = $this->getSession();
            $session = TRUE;
            return TRUE;
        }

        else{return false;}
    }

/* ******** GETS SESSION, IF NOT REDIRECT TO LOGIN ******** */
    public function getSession()
    {
        $userId = $_SESSION['id'];

        if ($_SESSION['id'] == null) {
            header('location:../login.php');
        }
        
        $sql = "SELECT `token` FROM `users` WHERE id = $userId;";
        $connection = $this->DbConnect();
        $result = $connection->query($sql) or die($connection->error);
        $user_data = $result->fetch_assoc();
        $result->close();
        $connection->close();
    
        if ($user_data) {
            $token = $user_data['token'];
            $_SESSION['token'] = $token;
            return $token;
        } else {
            return null; // Handle the case where no user data is found
        }
    }

/* ******** LOGOUT USER ******** */
    public function userLogout() {
		$session = $this->getSession();
		$session = FALSE;
        unset($_SESSION);
        session_destroy();
    }

}

?>