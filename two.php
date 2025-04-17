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

function getPostsWithComments($conn)
{
    $sql = "
        SELECT p.id as post_id, p.title, p.content as post_content, 
               c.comment_id as comment_id, c.content as comment_content
        FROM post p
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
                'content' => $row['comment_content']
            ];
        }
    }

    return $data;
}

$posts_with_comments = getPostsWithComments($conn);

function buildCommentHierarchy($comments)
{
    $hierarchy = [];
    $mappedComments = [];

    foreach ($comments as $comment) {
        $mappedComments[$comment['id']] = $comment;
        $mappedComments[$comment['id']]['children'] = [];
    }

    foreach ($comments as $comment) {
        if ($comment['parent_comment_id']) {
            // Piešķiram bērnu komentāru vecākam
            $mappedComments[$comment['parent_comment_id']]['children'][] = &$mappedComments[$comment['id']];
        } else {
            // Pievienojam komentāru augstākā līmeņa hierarhijai
            $hierarchy[] = &$mappedComments[$comment['id']];
        }
    }

    return $hierarchy;
}

foreach ($posts_with_comments as $post_id => $post) {
    $posts_with_comments[$post_id]['comments'] = buildCommentHierarchy($post['comments']);
}
