<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'valitis');
define('DB_NAME', 'ROBOTS');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

function config($key = '') {
    $config = [
        'docroot' => 'http://localhost/~justin/bwi_website/www/html/'
    ];

    return isset($config[$key]) ? $config[$key] : null;
}

?>
