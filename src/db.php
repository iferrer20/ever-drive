<?php

$dbpath = DRIVES_DIR . 'everdrive.db';
$dbexists = is_file($dbpath);
$db = new SQLite3($dbpath);
$db->enableExceptions(true);

if (!$dbexists) {
    $db->exec('
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    name VARCHAR(32) UNIQUE NOT NULL,
    email VARCHAR(128) NOT NULL,
    c_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    password VARCHAR(128) NOT NULL 
);
CREATE UNIQUE INDEX unique_users_name ON users (name);

CREATE TABLE drive (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    user INTEGER,
    name VARCHAR(32) NOT NULL, 
    c_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    password VARCHAR(128),
    FOREIGN KEY(user) REFERENCES user(id)
);
CREATE UNIQUE INDEX unique_drive_name ON drive (name);
');
    
}


?>