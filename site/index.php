<html>
<head>
   <link rel="stylesheet" href="style.css" media="screen">
<head>

<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli('localhost', 'root', '', 'roklub', 3306);
$mysqli->set_charset('utf8mb4');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

// Provide a combo box with all boats in standby
$boats = "SELECT Id, Name, NumPassengers FROM boats WHERE Status = 'standby'";
$area = "SELECT Id, Name FROM areas";
$members = "SELECT Id, Name FROM members";
$trips = "SELECT trips.Id as Id, boats.Name as BoatName, areas.Name as AreaName FROM trips JOIN boats ON trips.BoatId = boats.Id JOIN areas ON areas.Id = trips.AreaId WHERE trips.Done = 0";

echo '<h1>Skriv Ud</h1>';

echo '<form action="skrivud.php" method="post">';

echo '<label for="boat">Boat:</label>';
echo '<select name="boat">';
$result = $mysqli->query($boats);
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['Id'] . '" data-num-passengers="' . $row['NumPassengers'] . '">' . $row['Name'] . '</option>';
}    
$result->free();
echo '</select>';

echo '<br>';

echo '<label for="area">Area:</label>';
echo '<select name="area">';
$result = $mysqli->query($area);
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['Id'] . '">' . $row['Name'] . '</option>';
}
$result->free();
echo '</select>';

echo '<br>';

echo '<label for="chief">Chief:</label>';
echo '<select name="chief">';
$result = $mysqli->query($members);
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['Id'] . '">' . $row['Name'] . '</option>';
}    
echo '</select>';

echo '<br>';

$size = $result->num_rows;
echo '<label for="crew">Crew:</label>';
echo '<select class="long-select" name="crew[]" size="' . $size . '" multiple>';
$result->data_seek(0);
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['Id'] . '">' . $row['Name'] . '</option>';
}
$result->free();
echo '</select>';
echo '<input type="submit" value="New Trip">';
echo '</form>';

// delimeter
echo '<hr>';

echo '<h1>Skriv Ind</h1>';

echo '<form action="skrivind.php" method="post">';
echo '<label for="trip">Trip:</label>';
$result = $mysqli->query($trips);
echo '<select class="long-select" size="' . $result->num_rows . '" name="trip">';
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['Id'] . '">' . $row['BoatName'] . ' - ' . $row['AreaName'] . '</option>';
}
$result->free();
echo '</select>';

echo '<label for="length">Length:</label>';
echo '<input type="number" name="length" min="0" max="100" step="1">';

echo '<br>';

echo '<input type="submit" value="End Trip">';
echo '</form>';

$mysqli->close();
?>