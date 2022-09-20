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
        global $uri, $uri_arr, $action;
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
        
        if (!empty($this->drivemodel->password) && strcmp($_SESSION['drive'] ?? '', $this->drivename) && $action != 'auth') {
            require 'views/askpw.php';
            die();
        }

    }

    function list() {
        if (is_dir($this->path)) {
            $files = get_directory_files($this->path);
            require 'views/explorer.php';
        } else if (is_file($this->path)) {
            $mime_type = mime_content_type($this->path);
            header('Content-type: ' . $this->path);
            readfile($this->path);
        } else {
            require 'views/file404.php';
        }
    }

    function auth() {
        global $uri;
        if ($this->drivemodel->check_password($this->password)) {
            $_SESSION['drive'] = $this->drivename; // Set drivename to session
            header('Location: /' . $uri);
        } else {
            // Incorrect password
            http_response_code(403);
            $incorrect_pw = true;
            require 'views/askpw.php';
        }
    }

    function removefile() {

    }

    function submitfile() {
        global $uri;
        foreach ($_FILES as &$file) {
            $filepath = $this->path . '/' . htmlspecialchars($file['name']);
            move_uploaded_file($file['tmp_name'], $filepath);
        }
        header('Location: /' . $uri);
    }

    function newfolder() {
        global $uri;
        $folderpath = $this->path . '/' . htmlspecialchars($_POST['name']);
        if (!is_dir($folderpath)) {
            mkdir($folderpath);
        }
        header('Location: /' . $uri);
    }

};


?>