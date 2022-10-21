<?php 

require 'db.php';
require 'model.php';

class DriveController {
    public $drivename;
    public $driveroot;
    public $path;
    public $password;
    public $drivemodel;

    private function grant_access() {
        $_SESSION['drive'] = $this->drivename;
    }

    function __construct() {
        global $uri, $uri_arr, $action;
        $this->drivename = $uri_arr[1];
        $this->driveroot = DRIVES_DIR . $this->drivename;
        $this->path = DRIVES_DIR . $uri . '/';
        $this->password = $_POST['password'] ?? 0;
        $this->drivemodel = new DriveModel();

        if (!preg_match('/^[A-Za-z0-9_]{0,32}$/', $this->drivename)) {
            render('invalid', $this);
        }

        if (!$this->drivemodel->get($this->drivename)) {  // create drive if not exists 
            $this->drivemodel->create($this->drivename, $this->password); 
            $this->grant_access();
        }
        
        if (empty($this->drivemodel->password)) { // If drive with no password, grant access
            $this->grant_access();
        }

        // Check Auth middleware controller
        if (strcmp($_SESSION['drive'] ?? '', $this->drivename) && $action != 'auth') {
            render('askpw', $this);
        }
    }

    function list() {
        if (is_file($this->path)) {
            $mime_type = mime_content_type($this->path);
            header('Content-type: ' . $this->path);
            readfile($this->path);
            return;
        } 

        if (!is_dir($this->path)) {
            render('file404', $this);
        }
        render('explorer', $this);
    }

    function auth() {
        global $uri;
        if ($this->drivemodel->check_password($this->password)) {
            $this->grant_access();
            header('Location: /' . $uri);
        } else {
            // Incorrect password
            http_response_code(403);
            $incorrect_pw = true;
            render('askpw', $this);
        }
    }

    function delfile() {
        $filepath = secure_path($this->driveroot, $this->path . $_POST['name']);
        
        if (!is_file($filepath)) {
            return;
        }

        unlink($filepath); // Remove file
    }

    function submitfile() {
        foreach ($_FILES as &$file) {
            $filepath = secure_path($this->driveroot, $this->path . $file['name']);
            move_uploaded_file($file['tmp_name'], $filepath);
        }
    }

    function newfolder() {
        $folderpath = secure_path($this->driveroot, $this->path . $_POST['name']);
        if (is_dir($folderpath)) {
            return;
        }

        mkdir($folderpath);
    }

    function delfolder() {
        $folderpath = secure_path($this->driveroot, $this->path . $_POST['name']);
        
        if (!is_dir($folderpath)) {
            return;
        }

        shell_exec("rm -rf '$folderpath'"); // Remove folder
    }

    function move() {
        $from_path = secure_path($this->driveroot, $this->path . $_POST['from']);
        $to_path = secure_path($this->driveroot, $this->path . $_POST['to']);
        
        shell_exec("mv '$from_path' '$to_path'"); // Move 
    }
};


?>