<?php 

require 'db.php';
require 'model.php';

class DriveController {
    private $drivename;
    private $driveroot;
    private $path;
    private $password;
    private $drivemodel;

    function __construct() {
        global $uri, $uri_arr;
        $this->drivename = $uri_arr[1];
        $this->driveroot = DRIVES_DIR . $this->drivename;
        $this->path = DRIVES_DIR . $uri;
        $this->password = $_POST['password'] ?? 0;
        $this->drivemodel = new DriveModel();

        if (!preg_match('/^[A-Za-z0-9_]{0,32}$/', $this->drivename)) {
            require 'views/invalid.php';
            die();
        }

        if (!$this->drivemodel->get($this->drivename)) { 
            $this->drivemodel->create($this->drivename, $this->password); // create drive if not exists 
        }
    }

    function list() {
        $askpw = !empty($this->drivemodel->password) && strcmp($_SESSION['drive'] ?? '', $this->drivename);
        if ($askpw) {
            require 'views/askpw.php';
            return;
        }
        
        if (is_dir($this->path)) {
            $files = get_directory_files($this->path);
            require 'views/explorer.php';
        } else if (is_file($this->path)) {
            readfile($this->path);
        } else {
            require 'views/file404.php';
        }
        
    }

    function auth() {
        global $uri;
        if ($this->drivemodel->check_password($this->password)) {
            $_SESSION['drive'] = $this->drivename; // Set drivename to session
            header('Location: ' . $uri);
        } else {
            // Incorrect password
            http_response_code(403);
            $incorrect_pw = true;
            require 'views/askpw.php';
        }
    }

    function removefile() {

    }

};


?>