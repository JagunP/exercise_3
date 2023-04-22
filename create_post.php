<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the password is correct
    if ($_POST['password'] !== 'punpernickel') {
        die('Incorrect password');
    }

    // Sanitize the title and body inputs
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_STRING);

    // Connect to the database
    $db = new PDO('sqlite:db/weblog.sqlite3');

    // Prepare the insert statement
    $stmt = $db->prepare('INSERT INTO posts (title, body) VALUES (:title, :body)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':body', $body);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Get the id of the new post
        $post_id = $db->lastInsertId();

        // header('Location: /#post-' . $post_id);
        // header('Location: /weblog.php#post-' . $post_id);
        $referer = $_SERVER['HTTP_REFERER'];
        $redirect_path = str_replace('/create_post.php', '/weblog.php#post-', $referer) . $post_id;
        header("Location: $redirect_path");
      } else {
        echo 'Error creating post';
    }

    $db = null;
}
?>

<html>
<head>
  <title>Create a Post</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
  <h1>Precious' Web Journal</h1>
  <div class="create-post">
    <h2>Create a Post</h2>
    <form method="post">
      <label for="title">Title</label>
      <input name="title"></input>
      <label for="body">Post Body</label>
      <textarea name="body"></textarea>
      <label for="password">Secret Password</label>
      <input type="password" name="password"></input>
      <input type="submit" name="submit" value="Create Post"></input>
    </form>
  </div>
</body>
</html>
