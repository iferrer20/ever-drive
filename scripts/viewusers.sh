#!/bin/bash

source=$(echo -ne '
$db = new SQLite3("/home/www-data/everdrive.db");
$result = $db->query("SELECT * FROM users;");
while ($row = $result->fetchArray()) echo $row["name"] . "\n";
')

docker exec -t everdrive-php /usr/local/bin/php -r "$source"
