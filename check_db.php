    <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Diagnostic Tool</h2>";

$host = "localhost";
$user = "root";
$password = "";

// Step 1: Try to connect to MySQL
echo "<strong>Step 1:</strong> Connecting to MySQL... ";
$conn = mysqli_connect($host, $user, $password);

if (!$conn) {
    die("FAILED: " . mysqli_connect_error());
}
echo "✅ CONNECTED<br>";

// Step 2: Show all databases
echo "<strong>Step 2:</strong> Listing all databases...<br>";
$sql = "SHOW DATABASES";
$result = mysqli_query($conn, $sql);

$found = false;
echo "<ul>";
while ($row = mysqli_fetch_array($result)) {
    echo "<li>" . $row[0] . "</li>";
    if ($row[0] == "greenshop") {
        $found = true;
    }
}
echo "</ul>";

// Step 3: Check if greenshop exists
echo "<strong>Step 3:</strong> Looking for 'greenshop' database... ";
if ($found) {
    echo "✅ FOUND!<br>";
    
    // Step 4: Try to select it
    echo "<strong>Step 4:</strong> Selecting 'greenshop' database... ";
    if (mysqli_select_db($conn, "greenshop")) {
        echo "✅ SELECTED!<br>";
        echo "<strong style='color:green'>SUCCESS: Your connection should work now!</strong>";
    } else {
        echo "❌ FAILED: " . mysqli_error($conn);
    }
} else {
    echo "❌ NOT FOUND!<br>";
    echo "<strong style='color:red'>Database 'greenshop' does NOT exist in this MySQL server.</strong><br>";
    echo "Please create it in phpMyAdmin first.";
}

mysqli_close($conn);
?>