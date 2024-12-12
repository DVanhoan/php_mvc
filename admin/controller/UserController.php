<?php

include __DIR__ . "/../../lib/database.php";
include __DIR__ . "/../../lib/session.php";
include __DIR__ . "/../../helper/format.php";


Session::checkLogin();


class UserController
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function login($email, $password)
    {

        $email = Format::validation($email);
        $password = Format::validation($password);

        $email = mysqli_real_escape_string($this->db->con, $email);
        $password = mysqli_real_escape_string($this->db->con, $password);

        if (empty($email) || empty($password)) {
            $alert = "Vui lòng nhập đầy đủ thông tin";
            return $alert;
        }

        $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = $this->db->select($query);

        if ($result != false) {
            $value = $result->fetch_assoc();
            $hashedPassword = $value['password'];


            if (password_verify($password, $hashedPassword)) {
                $role = $value['role'];
                $username = $value['name'];
                $email = $value['email'];
                $id = $value['id'];

                Session::init();

                Session::set("id", $id);
                Session::set("login", true);
                Session::set("role", $role);
                Session::set("email", $email);
                Session::set("username", $username);


                header("Location: index.php");
            } else {
                $alert = "Mật khẩu không đúng, vui lòng thử lại.";
                return $alert;
            }
        } else {
            $alert = "Email hoặc mật khác, vui lòng thử lại.";
            return $alert;
        }
    }



    public function register($email, $password, $username, $role)
    {
        $userEmail = Format::validation($email);
        $password = Format::validation($password);
        $username = Format::validation($username);
        $role = Format::validation($role);

        $userEmail = mysqli_real_escape_string($this->db->con, $userEmail);
        $password = mysqli_real_escape_string($this->db->con, $password);
        $username = mysqli_real_escape_string($this->db->con, $username);
        $role = mysqli_real_escape_string($this->db->con, $role);


        $checkQuery = "SELECT * FROM users WHERE email = '$userEmail'";
        $result1 = $this->db->select($checkQuery);

        if ($result1 != false) {
            $alert = "Email đã đăng ký. Vui lý thử lại.";
            return $alert;
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $insertQuery = "INSERT INTO users(email, name, password, role) VALUES('$userEmail', '$username', '$hashedPassword', '$role')";
            $result2 = $this->db->insert($insertQuery);

            if ($result2) {
                $alert = "Đăng ký thành công";
                return $alert;
            } else {
                $alert = "Có lỗi xảy ra. Vui lòng thử lại sau.";
                return  $alert;
            }
        }
    }


    public function logout()
    {
        Session::destroy();
    }
}
