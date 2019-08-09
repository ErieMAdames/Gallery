<?php include('includes/init.php'); ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="style.css" media="all" />

  <title>Home</title>
</head>
<?php
if(isset($_POST['settags'])){
  $temp_tags = filter_input(INPUT_POST, "tags", FILTER_SANITIZE_STRING);
  $temp_tags = str_replace(array("\r\n", "\n", "\r"), ' ', $temp_tags);
  $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING);
  $tags = explode(',', $temp_tags);
  $sqltags = "SELECT tag FROM pictags JOIN tags ON tags.id = pictags.tagid WHERE picid = :picid;";
  $tagsparam = array(":picid" => $id);
  $sql = "INSERT INTO tags (tag) VALUES (:tag);";
  foreach($tags as $c_tag){
    $tags_assoc = exec_sql_query($db, $sqltags, $tagsparam)->fetchALL();
    $sqltagsarray = array();
    foreach($tags_assoc as $t){
      array_push($sqltagsarray, strtolower(trim($t[0])));
    }
    $tag = ucfirst(trim($c_tag));
    if(!in_array(strtolower($tag), $sqltagsarray) and $tag){
      $params = array(":tag" => $tag);
      exec_sql_query($db, $sql, $params);
      $sqlpictag = "INSERT INTO pictags (picid, tagid) VALUES (:picid, :tagid);";
      $tagid = $db->lastInsertId("id");
      $params = array(":picid" => $id,
      ":tagid" => $tagid);
      exec_sql_query($db, $sqlpictag, $params);
    }
  }
}
if(isset($_GET['id']) and isset($_GET['title'])){
  $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
  $title = filter_input(INPUT_GET, "title", FILTER_SANITIZE_STRING);
  $sql = "SELECT id, title, picpath, first_name, last_name, user, credit FROM pictures LEFT JOIN users ON pictures.user = users.userid WHERE id LIKE :picid AND title LIKE '%' || :pictitle || '%';";
  $params = array(':picid' => $id,
                  ':pictitle' => $title);
  $pictures = exec_sql_query($db,$sql,$params)->fetchAll();
  $picture = $pictures[0];
  $title = $picture['title'];
  $path = $picture['picpath'];
  $user = $picture['user'];
  $credit = $picture['credit'];
  $sql = "SELECT tag, tagid FROM pictags JOIN tags ON pictags.tagid = tags.id WHERE picid = :picid;";
  $params = array(':picid' => $id);
  $tagsearch = exec_sql_query($db,$sql,$params)->fetchAll();
  $tags = array();
  foreach($tagsearch as $tag){
    $tags[$tag['tag']] = $tag['tagid'];
  }
}
else {
  header('location: main.php');
}
if($picture['first_name']) {
  $author = $picture['first_name'] . " " . $picture['last_name'];
  $creds = "$author";
}
?>
<body id = "body">
<div id = "top">
<?php
  echo("<h1>$title</h1>");
?>
</div>
<div id = "sidebar">
  <!--https://www.onlinewebfonts.com/icon/103481-->
  <img src='loginpic.png' alt='login'>
  <?php
    if($current_user){
      echo("<h4>$first_n $last_n</h4>");
      ?>
        <a href="mypictures.php">My Pictures</a>
      <?php
    }else {
      echo("<h4>John Doe</h4>");
      ?>
        <a href="index.php">Sign In</a><?php
    }
  ?>
    <a href="main.php">All Pictures</a>
    <a href="alltags.php">All Tags</a>
  <?php
    if($current_user){?>
        <a href="addpictures.php">Add Pictures</a>
        <a href="logout.php">Log out</a>
      <?php } ?>
</div>
<div id ="page">
<?php
$name = "$first_n $last_n";
if ($name == $creds){
  echo("<h3>By Me</h3>");
}
else if ($creds){
  echo("<h3>By $creds</h3>");
}
else {
  echo("<h3>By Anonymous</h3>");
}
if ($credit) {
  echo("<!––credit $credit ––>");
}
echo("<div class = 'usertags'><div class = 'usertags_in'><img src='$path' alt='$title'></div><div class = 'usertags_in'>");
if(($userid == $user) and isset($userid) and isset($user)){
  foreach($tags as $tag=>$tagid) { ?>
    <form class = "dtag" action="delete.php" method="post">
    <input type="hidden" name="user_id" value="<?php echo($userid); ?>"/>
    <input type="hidden" name="picid" value="<?php echo($id); ?>"/>
    <input type="hidden" name="tag" value="<?php echo($tag); ?>"/>
    <input type="hidden" name="tagid" value="<?php echo($tagid); ?>"/>
    <input type="hidden" name="pictitle" value="<?php echo($title); ?>"/>
    <input type="hidden" name="deletetag" value="deletetag"/>
    <?php
    echo("<a href = 'alltags.php?tag=$tag'>$tag</a>");
    ?>
    <button>✕</button>
    </form>
    <?php
  }
  echo("</div>");
  echo("</div>");
}
else {
  foreach($tags as $tag=>$tagid) {
    echo("<a class = 'dtag' href = 'alltags.php?tag=$tag'>$tag</a>");
  }
  echo("</div>");
  echo("</div>");
}
  $title = $picture['title'];$linktitle = explode(' ',$title);
  $linktitle = implode('%20', $linktitle);
  echo("<div class = 'usertags'><form class = 'addtags' action = 'pictures.php?id=$id&title=$linktitle' method = 'post'>");
  ?>
  <div class = 'addlabel'>
    <label>Add Tags:</label>
  </div>
  <div class = 'addlabel'>
    <textarea placeholder = "Enter tags and separate them with a comma" cols = '45' rows = '23' name="tags"></textarea>
  </div>
  <input type="hidden" name="id" value= "<?php echo($id); ?>"/>
  <button name="settags" type="submit">Add</button>
</form>
</div>
<?php
if(($userid == $user) and isset($userid) and isset($user)){
  ?><div class = "deleteform">
      <form action="delete.php" method="post">
        <input type="hidden" name="user_id" value="<?php echo($userid); ?>"/>
        <input type="hidden" name="picid" value="<?php echo($id); ?>"/>
        <input type="hidden" name="pictitle" value="<?php echo($title); ?>"/>
        <input type="hidden" name="path" value="<?php echo($path); ?>"/>
        <input type="hidden" name="deletepic" value="deletepic"/>
        <button id = "hidebutton">Delete Picture</button>
      </form>
    </div><?php
}
?>
</div>
</body>
</html>
