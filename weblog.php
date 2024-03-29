<html>
<head>
  <title>Exercise 3 - A Web Journal</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
  <div class="compose-button">
    <a href="create_post.php" title="create post">
      <i class="material-icons">create</i>
    </a>
  </div>

  <h1>Precius' Web Journal</h1>

  <?php
  // Connect to the database
  $db = new PDO('sqlite:db/weblog.sqlite3');

  // Query the database for posts and comments
  $stmt = $db->prepare('SELECT posts.*, comments.id AS comment_id, comments.author AS comment_author, comments.body AS comment_body
                        FROM posts
                        LEFT JOIN comments ON posts.id = comments.post_id
                        ORDER BY posts.id DESC, comments.id ASC');
  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Initialize variables to keep track of the current post and comments
  $current_post_id = null;
  $current_title = null;
  $current_body = null;
  $current_comments = array();

  // Loop through the results and display each post and its comments
  foreach ($results as $result) {
      // Check if this is a new post
      if ($result['id'] !== $current_post_id) {
          // Display the current post and its comments
          if ($current_post_id !== null) {
              echo '<h2><a href="#post-' . $current_post_id . '">' . htmlspecialchars($current_title) . '</a></h2>';
              echo '<p>' . nl2br(htmlspecialchars($current_body)) . '</p>';
              echo '<ul>';
              foreach ($current_comments as $comment) {
                  echo '<li><p><strong>' . htmlspecialchars($comment['comment_author']) . ':</strong> ' . htmlspecialchars($comment['comment_body']) . '</p></li>';
              }
              echo '</ul>';
          }

          // Initialize the current post and comments
          $current_post_id = $result['id'];
          $current_title = $result['title'];
          $current_body = $result['body'];
          $current_comments = array();
      }

      // Add the comment to the current comments array
      if ($result['comment_id'] !== null) {
          $current_comments[] = array(
              'comment_id' => $result['comment_id'],
              'comment_author' => $result['comment_author'],
              'comment_body' => $result['comment_body'],
          );
      }
  }

  // Display the last post and its comments
  if ($current_post_id !== null) {
    // Display the current post and its comments
    echo '<h2><a href="#post-' . $current_post_id . '">' . htmlspecialchars($current_title) . '</a></h2>';
    echo '<p>' . nl2br(htmlspecialchars($current_body)) . '</p>';
    echo '<ul>';
    foreach ($current_comments as $comment) {
        echo '<li><p><strong>' . htmlspecialchars($comment['author']) . ':</strong> ' . htmlspecialchars($comment['comment_body']) . '</p></li>';
    }
    echo '</ul>';
  }

  // Close the database connection
  $db = null;
  ?>

  <!-- <div id="posts" style="visibility: hidden;">
    <post style="visibility: hidden;" class="post" id="3">
      <h2 class=post-title id="a_post_title">
        A Post Title
        <a href="#a_post_title">
          <i class="material-icons">link</i>
        </a>
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
        <comment>
          <div class="comment-body">
            Yeah Izzy!
          </div>
          <div class="comment-author">
            Sydney Carton
          </div>
        </comment>
        <comment>
          <div class="comment-body">
            off to a great start!
          </div>
          <div class="comment-author">
            nick_carraway
          </div>
        </comment>

        <a href="leave_comment.php?post_id=3">
          <i class="material-icons">create</i>
          Leave a comment
        </a>
      </div>
    </post>

    <post id="2" style="visibility: hidden;">
      <h2 class=post-title id="this_is_just_to_say">
        This Is Just To Say
        <a href="#this_is_just_to_say">
          <i class="material-icons">link</i>
        </a>
      </h2>
      <div class="post-body">
I have eaten
the plums
that were in
the icebox

and which
you were probably
saving
for breakfast

Forgive me
they were delicious
so sweet
and so cold
      </div>

      <h3>0 Comments</h3>
      <div class="comment-block">
        <a href="leave_comment.php?post_id=2">
          <i class="material-icons">create</i>
          Leave a comment
        </a>
      </div>
    </post>

    <post id="1" style="visibility: hidden;">
      <h2 class=post-title id="sonnet_2">
        Sonnet 2
        <a href="#sonnet_2">
          <i class="material-icons">link</i>
        </a>
      </h2>
      <div class="post-body">
When forty winters shall besiege thy brow
And dig deep trenches in thy beauty’s field,
Thy youth’s proud livery, so gazed on now,
Will be a tattered weed, of small worth held.
Then being asked where all thy beauty lies—
Where all the treasure of thy lusty days—
To say within thine own deep-sunken eyes
Were an all-eating shame and thriftless praise.
How much more praise deserved thy beauty’s use
If thou couldst answer "This fair child of mine
Shall sum my count and make my old excuse",
Proving his beauty by succession thine.
    This were to be new made when thou art old,
    And see thy blood warm when thou feel’st it cold.
      </div>

      <h3>0 Comments</h3>
      <div class="comment-block">
        <a href="leave_comment.php?post_id=1">
          <i class="material-icons">create</i>
          Leave a comment
        </a>
      </div>
    </post>

    <post id="0" style="visibility: hidden;">
      <h2 class=post-title id="first_post">
        First Post
        <a href="#first_post">
          <i class="material-icons">link</i>
        </a>
      </h2>
      <div class="post-body">
Hello World!
      </div>

      <h3>0 Comments</h3>
      <div class="comment-block">
        <a href="leave_comment.php?post_id=0">
          <i class="material-icons">create</i>
          Leave a comment
        </a>
      </div>
    </post>

  </div> end of posts block
</body> -->
