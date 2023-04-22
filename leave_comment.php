<?php
    $db = new PDO('sqlite:db/weblog.sqlite3');
    $post_id = $_GET['post_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
        $body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING);

        $stmt = $db->prepare('INSERT INTO comments (post_id, author, body) VALUES (:post_id, :author, :body)');
        $stmt->execute(array(':post_id' => $post_id, ':author' => $author, ':body' => $body));

        //back to the post page
        $referer = $_SERVER['HTTP_REFERER'];
        $redirect_path = str_replace('/leave_comment.php', '/weblog.php#post-', $referer) . $post_id;
        header("Location: $redirect_path");        exit();
    }

    // Query the database for the post and its comments
    $stmt = $db->prepare('SELECT p.title, p.body, c.author, c.body as comment_body FROM posts p LEFT JOIN comments c ON p.id = c.post_id WHERE p.id = :post_id ORDER BY c.id ASC');
    $stmt->execute(array(':post_id' => $post_id));
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if the post exists
    if (count($results) === 0) {
        // The post doesn't exist, so display an error message
        echo '<p>Sorry, that post doesn\'t exist.</p>';
    } else {
        // The post exists, so display it and its comments
        $post = $results[0];
        echo '<h2 id="post-' . $post_id . '">' . htmlspecialchars($post['title']) . '</h2>';
        echo '<p>' . htmlspecialchars($post['body']) . '</p>';

        // If there are comments, display them
        if (count($results) > 1) {
            echo '<h3>Comments</h3>';
            echo '<ul>';
            foreach ($results as $result) {
                if ($result['author'] !== null) {
                    echo '<li><p><strong>' . htmlspecialchars($result['author']) . ':</strong> ' . htmlspecialchars($result['comment_body']) . '</p></li>';
                }
            }
            echo '</ul>';
        }
    }

    // Close the database connection
    $db = null;
?>

<html>
<head>
  <title>Leave a Comment</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
  <h1>Precious' Web Journal</h1>
  <div class="leave-comment">
    <h2>
      Leave a Comment on
      <a href="weblog.php#a_post_title">A Post Title</a>
    </h2>

    <div class="post-body">
Call me Ishmael. Some years ago&mdash;never mind how long precisely&mdash;having
little or no money in my purse, and nothing particular to interest me on shore,
I thought I would sail about a little and see the watery part of the world. It
is a way I have of driving off the spleen and regulating the circulation.
Whenever I find myself growing grim about the mouth; whenever it is a damp,
drizzly November in my soul; whenever I find myself involuntarily pausing before
coffin warehouses, and bringing up the rear of every funeral I meet; and
especially whenever my hypos get such an upper hand of me, that it requires a
strong moral principle to prevent me from deliberately stepping into the street,
and methodically knocking people's hats off&mdash;then, I account it high time
to get to sea as soon as I can. This is my substitute for pistol and ball. With
a philosophical flourish Cato throws himself upon his sword; I quietly take to
the ship. There is nothing surprising in this. If they but knew it, almost all
men in their degree, some time or other, cherish very nearly the same feelings
towards the ocean with me.
    </div>

    <h3>2 Comments</h3>
    <div class="comment-block">
      <div class="comment">
        <div class="comment-body">
          Yeah Izzy!
        </div>
        <div class="comment-author">
          Sydney Carton
        </div>
      </div>
      <div class="comment">
        <div class="comment-body">
          off to a great start!
        </div>
        <div class="comment-author">
          nick_carraway
        </div>
      </div>
    </div>

    <form method="post">
      <label for="body">Comment</label>
      <textarea name="body"></textarea>
      <label for="name">Your name</label>
      <input name="name"></input>
      <input type="hidden" name="post_id" value="1"></input>
      <input type="submit" name="submit" value="Leave Comment"></input>
    </form>
  </div>
</body>
</html>
