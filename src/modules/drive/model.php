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

class FileModel {
    public $name;
    public $size;
    public $path;
    public $is_directory;
};

function get_directory_files($dir) {
    $files = [];
    foreach (array_diff(scandir($dir), array('.', '..')) as $name) {
        $file = new FileModel();
        $file->name = $name;
        $file->path = $dir . $name;
        $file->is_directory = is_dir($file->path);
        echo var_dump($file->is_directory);
        echo $file->path;
        $file->size = filesize($file->path);
        $files[] = $file;
    }
    return $files;
}


?>