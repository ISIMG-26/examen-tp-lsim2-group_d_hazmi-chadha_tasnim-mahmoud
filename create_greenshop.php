<?php
$host = "localhost";
$user = "root";
$password = "";

// Connect to MySQL server (without selecting a database)
$conn = mysqli_connect($host, $user, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create the database
$sql = "CREATE DATABASE greenshop";
if (mysqli_query($conn, $sql)) {
    echo "✅ Database 'greenshop' created successfully!<br>";
    echo "Now you can use it in your project.";
} else {
    echo "❌ Error creating database: " . mysqli_error($conn);
}

mysqli_close($conn);
?>