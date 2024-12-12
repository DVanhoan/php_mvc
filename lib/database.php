<?php

class Database
{
    public $host = "localhost";
    public $user = "root";
    public $pass = "";
    public $dbname = "php_mvc";

    public static $instance;


    public $con;
    public $error;


    public function __construct()
    {
        $this->connect();
    }


    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }



    public function connect()
    {
        $this->con = mysqli_connect($this->host, $this->user, $this->pass, $this->dbname);
        if (!$this->con) {
            $this->error = "Kết nối thất bại: " . mysqli_connect_error();
            return false;
        }
        return true;
    }

    public function select($query)
    {
        $result = $this->con->query($query) or die($this->con->error . __LINE__);
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function insert($query)
    {
        $insert_row = $this->con->query($query) or die($this->con->error . __LINE__);
        if ($insert_row) {
            return $insert_row;
        } else {
            return false;
        }
    }

    public function update($query)
    {
        $update_row = $this->con->query($query) or die($this->con->error . __LINE__);
        if ($update_row) {
            return $update_row;
        } else {
            return false;
        }
    }

    public function delete($query)
    {
        $delete_row = $this->con->query($query) or die($this->con->error . __LINE__);
        if ($delete_row) {
            return $delete_row;
        } else {
            return false;
        }
    }

    public function getLastId()
    {
        return $this->con->insert_id;
    }
}
