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

function getPost($conn)
{
    $sql = "SELECT * FROM post";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $post = [];
        while ($row = $result->fetch_assoc()) {
            $post[] = $row;
        }
        return $post;
    } else {
        return [];
    }
}

$posts = getPost($conn);
