<?php

class UserModel {
    public $id;
    public $name;
    public $email;
    public $password;

    function fetchResult($result) {
        $result = $result->fetchArray();
        if (!$result) {
            return false;
        }

        $this->id = $result['id'];
        $this->name = $result['name'];
        $this->email = $result['email'];
        $this->password = $result['password'];
        return true;
    }

    function get($name) {
        global $db;
        $stmt = $db->prepare('SELECT * FROM users WHERE name = :name;');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        return $this->fetchResult($stmt->execute());
    }

    function get_byid($id) {
        global $db;
        $stmt = $db->prepare('SELECT * FROM users WHERE id = :id;');
        $stmt->bindValue(':id', $id, SQLITE3_NUM);
        return $this->fetchResult($stmt->execute());
    }

    function get_own_drives() {
        global $db;
        $stmt = $db->prepare('SELECT * FROM drives WHERE user = :id;');
        $stmt->bindValue(':id', $this->id, SQLITE3_NUM);
        $result = $stmt->execute();
        return $result;
    }

    function has_drives() {
        global $db;
        $stmt = $db->prepare('SELECT COUNT(*) > 0 FROM drives WHERE user = :id;');
        $stmt->bindValue(':id', $this->id, SQLITE3_NUM);
        $result = $stmt->execute()->fetchArray()[0];
        return $result;
    }

    function update() {
        global $db;
        $stmt = $db->prepare('UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id;');
        $stmt->bindValue(':name', $this->name, SQLITE3_TEXT);
        $stmt->bindValue(':email', $this->email, SQLITE3_TEXT);
        $stmt->bindValue(':password', $this->password, SQLITE3_TEXT);
        $stmt->bindValue(':id', $this->id, SQLITE3_NUM);
        $stmt->execute();
    }

    function create($name, $email, $password) {
        global $db;

        if (!preg_match('/^[A-Za-z0-9_]{0,32}$/', $name)) {
            throw new BadRequestException('Nombre de usuario inválido');
        }

        if (!preg_match('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/', $email)) {
            throw new BadRequestException('Email inválido');
        }

        if (!$password || strlen($password) > 100) {
            throw new BadRequestException('Contraseña inválida');
        }

        $this->set_password($password);

        // Create drive if not found
        $stmt = $db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password);');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':password', $this->password, SQLITE3_TEXT);
        try {
            $result = $stmt->execute();
        } catch(Exception) {
            throw new BadRequestException('Usuario ya existente');
        }
        
        
        $this->name = $name;
        $this->email = $email;
        $this->id = $db->lastInsertRowID();
    }

    function set_password($pw) {
        $this->password = password_hash($pw, PASSWORD_DEFAULT);
    }

    function check_password($pw) {
        return password_verify($pw, $this->password);
    }

    function has_pfp() {
        return is_file(PROFILES_DIR . $this->id);
    }
}

?>