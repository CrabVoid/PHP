<?php
$servername = "LocalHost";
$username = "RalfsEgle";
$password = "password";
$dbname = "php27032025";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Savienojuma kļūda: " . $conn->connect_error);
}
