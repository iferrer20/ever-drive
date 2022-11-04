<?php 

require 'model.php';

class DriveController {
    public $path;
    public $password;
    public $drive;
    public $user;

    private function grant_access() {
        session_set('drive', $this->drive->name);
    }

    private function check_owner() { // Midleware
        if (!$this->user || $this->user->id != $this->drive->author?->id) {
            die();
        }
    }

    function __construct() {
        global $uri, $uri_arr, $action;
        $drivename = $_POST['drivename'] ?? $uri_arr[1];
        $this->path = DRIVES_DIR . $uri;
        if (is_dir($this->path)) {
            $this->path .= '/';
        }
        $password = $_POST['password'] ?? 0;
        $this->drive = new DriveModel();

        if (!preg_match('/^[A-Za-z0-9_]{0,32}$/', $drivename)) {
            render('invalid', (object) array(
                'drive' => (object) array(
                    'name' => $drivename
                )
            ));
        }

        if ($id = session('user_id')) {
            $this->user = new UserModel();
            $this->user->get_byid($id);
        }

        if (!$this->drive->get($drivename)) {  // create drive if not exists 
            $this->drive->create($drivename, $password); 
            $this->grant_access();
        }

        if (
            empty($this->drive->password) || 
            $this->drive->author && $this->drive->author->id == $this->user?->id || 
            $this->drive->check_password($password)
        ) {
            $this->grant_access();
        } else if (strcmp(session('drive'), $this->drive->name)) {
            http_response_code(403);
            render('askpw', $this);
        }
    }

    function read() { // Read files from folder
        if (is_file($this->path)) {
            $mime_type = mime_content_type($this->path);
            header('Content-type: ' . $mime_type);
            readfile($this->path);
            return;
        } 

        if (!is_dir($this->path)) {
            render('file404', $this);
        }
        render('explorer', $this);
    }

    function submitfile() { // Submit files
        $total = count($_FILES['file']['name']);
        for( $i=0 ; $i < $total ; $i++ ) {
            $name = $_FILES['file']['name'][$i];
            $tmp_path = $_FILES['file']['tmp_name'][$i];
            $filepath = secure_path($this->drive->root, $this->path . $name);
            move_uploaded_file($tmp_path, $filepath);
        }
    }

    function newfolder() { // New Folder
        $folderpath = secure_path($this->drive->root, $this->path . $_POST['name']);
        if (is_dir($folderpath)) {
            return;
        }

        mkdir($folderpath);
    }

    function update() {
        $this->check_owner();
        $this->drive->name = post('name');
        $pw = post('password');
        if ($pw) {
            $this->drive->set_password($pw);
        } else if ($pw === '') {
            $this->drive->password = NULL;
        }
        $this->drive->update();
    }

    function del() { // Delete entry
        $folderpath = secure_path($this->drive->root, $this->path . $_POST['name']);
        shell_exec("rm -rf '$folderpath'"); // Remove 
    }

    function deldrive() {
        if ($this->user->id != $this->drive->author?->id) {
            return;
        }
        $this->drive->delete();
        $path = $this->drive->root;
        shell_exec("rm -rf '$path'"); // Remove
    }

    function move() { // Move entry
        $from_path = secure_path($this->drive->root, $this->path . $_POST['from']);
        $to_path = secure_path($this->drive->root, $this->path . $_POST['to']);
        
        shell_exec("mv '$from_path' '$to_path'"); // Move 
    }
}


?>