<?php 

class DriveModel {
    public $name;
    public $password;

    function get($name) {
        global $db;
        $result = $db->query("SELECT * FROM drive WHERE name = '$name';")->fetchArray();
        if ($result) {
            $this->name = $result['name'];
            $this->password = $result['password'];
            return true;
        } else {
            return false;
        }
    }

    function create($name, $password) {
        global $db;
        // Create drive if not found
        $stmt = $db->prepare('INSERT INTO drive (name, password) VALUES (:name, :password);');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        if ($password) {
            $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), SQLITE3_TEXT);
        } else {
            $stmt->bindValue(':password', NULL, SQLITE3_NULL);
        }
        $result = $stmt->execute();
        mkdir(DRIVES_DIR . $name);

        $this->name = $name;
        $this->password = $password;
    }

    function check_password($pw) {
        return password_verify($pw, $this->password);
    }
};

class EntryModel {
    public $name;
    public $size;
    public $path;
    public $is_directory;
};

function get_folders($dir) {
    $folders = [];
    foreach (array_diff(scandir($dir), array('.', '..')) as $name) {
        $folder = new EntryModel();
        $folder->name = $name;
        $folder->path = $dir . '/' . $name;
        if (!is_dir($folder->path)) continue;
        $folder->is_directory = true;
        $folder->size = 0;
        $folders[] = $folder;
    }
    return $folders;
}

function get_files($dir) {
    $files = [];
    foreach (array_diff(scandir($dir), array('.', '..')) as $name) {
        $file = new EntryModel();
        $file->name = $name;
        $file->path = $dir . '/' . $name;
        if (!is_file($file->path)) continue;
        $file->is_directory = false;
        $file->size = filesize($file->path);
        $files[] = $file;
    }
    return $files;
}


?>