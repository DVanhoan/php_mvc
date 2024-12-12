<?php
class Session {
    
    
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
    }

    public static function set($key, $val) {
        $_SESSION[$key] = $val; 
    }

    public static function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key]; 
        }
        return null; 
    }

    public static function checkSesstion() {
        self::init();
        if(self::get("login")==false){
            self::destroy();
            header("Location:login.php");
        }
    }

    public static function checkLogin(){
        self::init();
        if(self::get("login")==true){
            header("Location:index.php");
        }
    }

    public static function destroy() {
        session_destroy();
        header("Location: login.php");
    }
}

?>