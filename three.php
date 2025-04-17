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

function renderComments($comments)
{
    $html = "<ul>";
    foreach ($comments as $comment) {
        $html .= "<li>";
        $html .= htmlspecialchars($comment['content']);

        if (!empty($comment['children'])) {
            $html .= renderComments($comment['children']); // Rekursīvi izvada bērnu komentārus
        }

        $html .= "</li>";
    }
    $html .= "</ul>";
    return $html;
}

foreach ($posts_with_comments as $post) {
    echo "<h2>" . htmlspecialchars($post['title']) . "</h2>";
    echo "<p>" . htmlspecialchars($post['content']) . "</p>";
    echo "<h3>Komentāri:</h3>";
    echo renderComments($post['comments']);
}

class Post
{
    public $id;
    public $title;
    public $content;
    public $comments = [];

    public function __construct($id, $title, $content)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
    }

    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
    }
}

class Comment
{
    public $id;
    public $content;
    public $parent_comment_id;
    public $children = [];

    public function __construct($id, $content, $parent_comment_id)
    {
        $this->id = $id;
        $this->content = $content;
        $this->parent_comment_id = $parent_comment_id;
    }

    public function addChild(Comment $child)
    {
        $this->children[] = $child;
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