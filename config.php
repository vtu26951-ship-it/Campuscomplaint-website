<?php
$host = "localhost";     // database server
$user = "root";          // default XAMPP username
$password = "";          // default password is empty
$database = "student_db"; // your database name

$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// echo "Database Connected Successfully";
?>