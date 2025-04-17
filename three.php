<?php
$servername = "localhost";
$username = "RalfsEgle"; // Jūsu datubāzes lietotājvārds
$password = "password"; // Jūsu datubāzes parole
$dbname = "php17042025"; // Jūsu datubāzes nosaukums

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Savienojums neizdevās: " . $conn->connect_error);
}

function getPosts($conn)
{
    $sql = "SELECT * FROM posts";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
        return $posts;
    } else {
        return [];
    }
}

$posts = getPosts($conn);

function getPostsWithComments($conn)
{
    $sql = "
        SELECT p.id as post_id, p.title, p.content as post_content, 
               c.id as comment_id, c.content as comment_content, c.parent_comment_id
        FROM posts p
        LEFT JOIN comments c ON p.id = c.post_id
        ORDER BY p.id, c.created_at";
    $result = $conn->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $post_id = $row['post_id'];
        if (!isset($data[$post_id])) {
            $data[$post_id] = [
                'title' => $row['title'],
                'content' => $row['post_content'],
                'comments' => []
            ];
        }

        if ($row['comment_id']) {
            $data[$post_id]['comments'][] = [
                'id' => $row['comment_id'],
                'content' => $row['comment_content'],
                'parent_comment_id' => $row['parent_comment_id']
            ];
        }
    }

    return $data;
}

$posts_with_comments = getPostsWithComments($conn);
