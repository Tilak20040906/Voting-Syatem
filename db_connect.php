<?php
$servername = "localhost"; // Hostname
$username = "root"; // MySQL username
$password = ""; // MySQL password (update if a password is set)
$dbname = "votingsystem"; // Database name
$port = 3307; // Port number used by MySQL

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Create connection using mysqli
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    } else {
        echo ".";
    }
} catch (Exception $e) {
    // Display a user-friendly error message
    echo "Error: " . $e->getMessage();
    exit; // Stop execution in case of an error
}
?>
