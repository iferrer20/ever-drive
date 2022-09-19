<?php

$dbpath = DRIVES_DIR . 'everdrive.db';
$dbexists = is_file($dbpath);
$db = new SQLite3($dbpath);

if (!$dbexists) {
    $db->exec('
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    name VARCHAR(32) NOT NULL,
    email VARCHAR(128) NOT NULL,
    c_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    password VARCHAR(128) NOT NULL 
);
CREATE TABLE drive (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    name VARCHAR(32) NOT NULL, 
    c_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    password VARCHAR(128)
);
');
    
}


?>