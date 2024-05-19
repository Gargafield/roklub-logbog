
<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli('localhost', 'root', '', 'roklub', 3306);
$mysqli->set_charset('utf8mb4');

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tripId = $_POST['trip'];
    $length = $_POST['length'];
    
    $sql = $mysqli->prepare("CALL EndTrip(?, ?)");
    $sql->bind_param('id', $tripId, $length);
    $sql->execute();
    $sql->close();
}

// Redirect to index.php
header('Location: index.php');
?>