<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "greenshop";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>Checking Database: $database</h2>";

// Show all tables
$sql = "SHOW TABLES";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h3>Tables found:</h3>";
    echo "<ul>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<h3 style='color:red'>No tables found in database '$database'!</h3>";
}

mysqli_close($conn);
?>