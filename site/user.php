
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli('localhost', 'root', '', 'roklub', 3306);
$mysqli->set_charset('utf8mb4');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

$userId = $_GET['id'];

$user = "SELECT * FROM members WHERE Id = $userId";
$stats = "SELECT * FROM user_stats WHERE UserId = $userId";
$boats = "SELECT boats.Name as BoatName, boats.Id as BoatId, ubs.TotalLength as Length FROM user_boat_stats as ubs JOIN boats ON ubs.BoatId = boats.Id WHERE ubs.UserId = ?";
$trips = "SELECT * FROM trip_display WHERE TripId IN (SELECT TripId FROM trip_rower_map WHERE RowerId = ?)";

$result = $mysqli->query($user);
$row = $result->fetch_assoc();
echo '<h1>User ' . $row['Name'] . '</h1>';
echo '<p>Phone: ' . $row['Phonenumber'] . '</p>';
echo '<p>Email: ' . $row['Email'] . '</p>';
$result->free();

echo '<hr>';

$result = $mysqli->query($stats);
$row = $result->fetch_assoc();
echo '<p>Number of trips: ' . $row['NumberOfTrips'] . '</p>';
echo '<p>Total distance: ' . $row['TotalLength'] . ' km</p>';
echo '<p>Average distance: ' . $row['AverageLength'] . ' km</p>';

$result->free();

$stmt = $mysqli->prepare($boats);
$stmt->bind_param('i', $userId);
$stmt->execute();
$boats = $stmt->get_result();

echo '<hr><h2>Boats</h2>';
while ($row = $boats->fetch_assoc()) {
    echo '<div>';
    echo '<h2>' . $row['BoatName'] . '</h2>';
    echo '<p>Total distance: ' . $row['Length'] . ' km</p>';
    echo '</div>';
}

$stmt->close();

$stmt = $mysqli->prepare($trips);
$stmt->bind_param('i', $userId);
$stmt->execute();
$trips = $stmt->get_result();

echo '<hr><h2>Trips</h2>';
while ($row = $trips->fetch_assoc()) {
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
$stmt->close();

$mysqli->close();
?>