<?php

require 'head.php';

// Initialize the session
session_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}
else {
    echo "<h1>You have been logged in!</h1>";
    echo "<button onClick=\"javascript:window.location.href='/logout.php'\">Logout</button>";
}
?>
