
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli('localhost', 'root', '', 'roklub', 3306);
$mysqli->set_charset('utf8mb4');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

// Provide a list of all trips that are done ordered by date and time
$trips = "SELECT * FROM trip_display ORDER BY TripStartTime DESC, TripDate";

echo '<h1>Trips</h1>';
$result = $mysqli->query($trips);
// List all trips, dont use table
while ($row = $result->fetch_assoc()) {
    echo '<div>';
    echo '<h2>' . $row['BoatName'] . ' - ' . $row['AreaName'] . '</h2>';
    echo '<p>' . $row['TripDate'] . ' ' . $row['TripStartTime'] . ' - ' . $row['TripEndTime'] . '</p>';
    echo '<p>Chief: <a href="user.php?id=' . $row['ChiefId'] . '">' . $row['ChiefName'] . '</a></p>';
    
    $crew = explode(',', $row['RowerMap']);
    echo '<p>Crew: ';
    foreach ($crew as $crewId) {
        $rower = explode(':', $crewId);
        $crewId = $rower[0];
        $crewName = $rower[1];
        
        if ($crewId == $row['ChiefId']) {
            continue;
        }
        
        echo '<a href="user.php?id=' . $crewId . '">' . $crewName . '</a> ';
    }
    
    echo '<p>Length: ' . $row['TripLength'] . ' km</p>';
    echo '</div>';
}
$result->free();
$mysqli->close();

?>