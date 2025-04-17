<?php
$servername = "LocalHost";
$username = "RalfsEgle";
$password = "password";
$dbname = "php27032025";

// Izveido savienojumu
$conn = new mysqli($servername, $username, $password, $dbname);

// Pārbaudām savienojumu
if ($conn->connect_error) {
    die("Savienojuma kļūda: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>