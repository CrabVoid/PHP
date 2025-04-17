<?php
$servername = "localhost";
$username = "RalfsEgle"; // Jūsu datubāzes lietotājvārds
$password = "password"; // Jūsu datubāzes parole
$dbname = "php27032025"; // Jūsu datubāzes nosaukums

// Izveido savienojumu
$conn = new mysqli($servername, $username, $password, $dbname);

// Pārbauda savienojumu
if ($conn->connect_error) {
    die("Savienojums neizdevās: " . $conn->connect_error);
}
