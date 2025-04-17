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

$sql_posts = "SELECT id, title, content FROM post";
$result_posts = $conn->query($sql_posts);

echo "<ul>";

if ($result_posts->num_rows > 0) {
    while ($row_post = $result_posts->fetch_assoc()) {
        echo "<li>";
        echo "<strong>" . htmlspecialchars($row_post['title']) . "</strong><br>";
        echo "<p>" . nl2br(htmlspecialchars($row_post['content'])) . "</p>";

        $post_id = $row_post['id'];
        $sql_comments = "SELECT comment_id, content FROM comments WHERE post_id = $post_id";
        $result_comments = $conn->query($sql_comments);

        if ($result_comments->num_rows > 0) {
            echo "<ul>"; // sākam komentāru sarakstu
            while ($row_comment = $result_comments->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row_comment['content']) . "</li>";
            }
            echo "</ul>";
        }
    }
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