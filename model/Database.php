<?php

if($_SERVER['HTTP_HOST'] == 'localhost:8888'){
    // MacOS values
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_DATABASE', 'desoos');
}
else if($_SERVER['HTTP_HOST'] == 'localhost'){
    // Windows values
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_DATABASE', 'desoos');
}
else{
    // Live values
    define('DB_SERVER', '');
    define('DB_USERNAME', '');
    define('DB_PASSWORD', '');
    define('DB_DATABASE', '');
}
class Database  {
    public $connection;

    public function __construct() {
        $this->initializeConnection();
    }

    private function initializeConnection() {
        $this->connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

        if ($this->connection->connect_error) die('Database error -> ' . $this->connection->connect_error);

    }

    public function retObj() {
        return $this->connection;
    }

    public function DbConnect(){
        $this->connection = new Database();
        $this->connection = $this->connection->retObj();
        return $this->connection;
    }
}
?>
