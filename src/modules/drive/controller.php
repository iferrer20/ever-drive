<?php 

require 'db.php';
require 'model.php';

class DriveController {
    public $drivename;
    public $driveroot;
    public $path;
    public $password;
    public $drive;
    static public $default_action = "read";

    private function grant_access() {
        session_set('drive', $this->drivename);
    }

    function __construct() {
        global $uri, $uri_arr, $action;
        $this->drivename = $uri_arr[1];
        $this->driveroot = DRIVES_DIR . $this->drivename;
        $this->path = DRIVES_DIR . $uri;
        if (is_dir($this->path)) {
            $this->path .= '/';
        }
        $this->password = $_POST['password'] ?? 0;
        $this->drive = new DriveModel();

        if (!preg_match('/^[A-Za-z0-9_]{0,32}$/', $this->drivename)) {
            render('invalid', $this);
        }

        if (!$this->drive->get($this->drivename)) {  // create drive if not exists 
            $this->drive->create($this->drivename, $this->password); 
            $this->grant_access();
        }
        
        if (empty($this->drive->password)) { // If drive with no password, grant access
            $this->grant_access();
        }

        // Check Auth middleware controller
        if (strcmp(session('drive'), $this->drivename) && $action != 'auth') {
            render('askpw', $this);
        }

        if ($id = session('user_id')) {
            //$this->usermodel->get_byid($id);
        }
    }

    function read() {
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
        if ($this->drive->check_password($this->password)) {
            $this->grant_access();
        } else {
            // Incorrect password
            http_response_code(403);
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
        $total = count($_FILES['file']['name']);
        for( $i=0 ; $i < $total ; $i++ ) {
            $name = $_FILES['file']['name'][$i];
            $tmp_path = $_FILES['file']['tmp_name'][$i];
            $filepath = secure_path($this->driveroot, $this->path . $name);
            move_uploaded_file($tmp_path, $filepath);
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
}


?>