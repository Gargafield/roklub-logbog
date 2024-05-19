
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli('localhost', 'root', '', 'roklub', 3306);
$mysqli->set_charset('utf8mb4');

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $boatId = $_POST['boat'];
    $areaId = $_POST['area'];
    $chiefId = $_POST['chief'];
    $crewIds = $_POST['crew'];
    
    $crew = array((int)$chiefId);
    foreach ($crewIds as $crewId) {
        $crew[] = (int)$crewId;
    }
    $crewJson = json_encode($crew);
    
    // Insert the trip
    $sql = $mysqli->prepare("CALL BeginTrip(?, ?, ?)");
    $sql->bind_param('iis', $boatId, $areaId, $crewJson);
    $sql->execute();
    $sql->close();
}
$mysqli->close();

// Redirect to index.php
header('Location: index.php');
?>