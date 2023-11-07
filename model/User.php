<?php
include_once("Database.php");

define('hash1','$)(#KLASD@((UOJRC$2#24edA');
define('hash2','(#$#UIJD()PI#O$J#@(#$@PP$');

class User {

    private $id;
    private $username;
    private $password;
    private $name;
    private $token;
    protected $db;

    private function DbConnect() {
        $this->db = new Database();
        $this->db = $this->db->retObj();
        return $this->db;
    }

    /* ******** GETTERS EN SETTERS ******** */

    public function setId($id) {
        $this->id = $id;
    }
    public function setUsername($username) {
        $this->username = $username;
    }
    public function setPassword($password) {
        $this->password = $password;
    }
    public function setName($name) {
        $this->name = $name;
    }
    public function setToken($token) {
        $this->token = $token;
    }

    public function getId() {
        return $this->id;
    }
    public function getUsername() {
        return $this->username;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getName() {
        return $this->name;
    }
    public function getToken() {
        return $this->token;
    }

    public function getAllUsers() {

        $return_array =  array();

        $query = "SELECT * FROM `users` ORDER BY id";
        $result = $this->DbConnect()->query($query);
        $this->DbConnect()->close();
        foreach($result as $obj => $array){
            $user = new User();
            
            $user->setId($array['id']);
            $user->setUsername($array['username']);
            $user->setName($array['name']);
            $user->setToken($array['token']);

            $return_array[] = $user;
        }
        return $return_array;

    }

    /* ******** Fetches users by username ******** */

    public function getUserListByUsername($input_array){
        
        $return_array =  array();

        $query = "SELECT * FROM `users` WHERE `username` = '".$input_array['username']."' ORDER BY id";
        $result = $this->DbConnect()->query($query);
        $this->DbConnect()->close();
        foreach($result as $obj => $array){
            $user = new User();
            
            $user->setId($array['id']);
            $user->SetUsername($array['username']);
            $user->SetName($array['name']);
            $user->setToken($array['token']);

            $return_array[] = $user;
        }
        return $return_array;
    }

    /* ******** Get post values ******** */

    public function getPostValues() {

        $post_check_array = array (
            'update'        => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'delete'        => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'username'      => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'password'      => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'passwordCheck' => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'name'          => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'token'         => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'rol_id'        => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS ),
            'id'            => array('filter' => FILTER_VALIDATE_INT)
        );
        $inputs = filter_input_array( INPUT_POST, $post_check_array );
        return $inputs;

    }

    /* ******** Get Get values ******** */

    public function getGetValues() {

        $get_check_array = array(
            'action' => array('filter' => FILTER_SANITIZE_SPECIAL_CHARS),  
            'id' => array('filter' => FILTER_VALIDATE_INT), 
        );
        $inputs = filter_input_array(INPUT_GET, $get_check_array);
        return $inputs;

    }

    /* ******** Handles get action ******** */

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
                $this->delete($get_array);
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

        /* ******** Update users ******** */

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
            $input_array['password'] = $input_array['passwordCheck'];
        
            $sql = "UPDATE `users` SET `username`='$username',`name`='$name' WHERE `id`='$id'";
            $this->DbConnect()->query($sql);
            $this->DbConnect()->close();
        } catch (Exception $e) {
            echo $e->getMessage();
            return FALSE;
        }
        
        return TRUE;
        
    }

        /* ******** Delete Users ******** */

        public function delete($input_array) {
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
                    header("Location: http://localhost:8888/parfumerie-van-biemen/admin/gebruikers.php%22");
                } if($_SERVER['HTTP_HOST'] == 'localhost'){
                    header("Location: http://localhost/parfumerie-van-biemen/admin/gebruikers.php%22");
                } else {
                    header("Location: http://www.parfumerievanbiemen.nl/testomgeving/admin/gebruikers.php%22");
                }
    
                // Refresh the page after successful deletion
                // if($_SERVER['HTTP_HOST'] == 'localhost:8888'){
                //     header("Location:" . dirname(FILE) . "gebruikers.php");
                // } if($_SERVER['HTTP_HOST'] == 'localhost'){
                //     header("Location:" . dirname(FILE) . "gebruikers.php");
                // } else {
                //     header("Location:" . dirname(FILE) . "gebruikers.php");
                // }
                exit();
            } catch (Exception $e) {
                error_log("Delete error: <h1 style='color=#fff;'>" . $e->getMessage() . "</h1>");
                return FALSE;
            }
        }

    /* ******** Register Users ******** */

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
                $sql = "INSERT INTO `users` SET rol_id='".$input_array['rol_id']."', username='".$input_array['username']."', 
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

    /* ******** Checks login ******** */

    public function checkLogin($input_array) {

        $user_data = array();
        $password = md5(hash1.$input_array['password'].hash2);
        $username = $input_array['username'];
    
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

    /* ******** Get Session ******** */

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

    /* ******** Logout User ******** */

    public function userLogout() {
		$session = $this->getSession();
		$session = FALSE;
        unset($_SESSION);
        session_destroy();
    }


}

?>