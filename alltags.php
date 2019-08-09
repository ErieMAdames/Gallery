<?php include('includes/init.php'); ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="style.css" media="all" />

  <title>Home</title>
</head>
<body id = "body">
<div id = "top">
<?php
$search = filter_input(INPUT_GET, "search", FILTER_SANITIZE_STRING);
$tag = filter_input(INPUT_GET, "tag", FILTER_SANITIZE_STRING);
if($search and !$tag){
  echo("<h1>Search: $search<h1>");
}
else if(!$search and $tag) {
  echo("<h1>Tag: $tag<h1>");
}
else {
?>
  <h1>All Tags</h1>
<?php } ?>
</div>
<div id = "sidebar">
  <!--https://www.onlinewebfonts.com/icon/103481-->ssss
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
<div id="search">
  <form action="alltags.php" method="get">
    <label>Search tags:<label>
      <input type="text" name="search"/>
      <button name="Search" type="submit">Search</button>
  </form>
</div>
<div id = "page">
    <?php
      if($tag){
        $sql = "SELECT picid, title, picpath, first_name, last_name, credit FROM (SELECT picid, title, picpath, user, credit FROM (SELECT * FROM pictags JOIN tags ON pictags.tagid = tags.id) JOIN pictures ON picid = pictures.id WHERE tag LIKE :tag) JOIN users ON user = users.userid;";
        $params = array(':tag' => $tag);
        $pictures = exec_sql_query($db,$sql,$params)->fetchAll();
        $ids = array();
        if($pictures) {
          foreach($pictures as $picture){
            $title = $picture['title'];
            $path = $picture['picpath'];
            $credit = $picture['credit'];
            array_push($ids, $picture['picid']);
            $link = "pictures.php?id=" . $picture['picid'] . "&title=$title";
            echo('<div class = "block">');
            echo("<h4>$title</h4>");
            echo("<a href = $link><img src='$path' alt='$title'></a>");
            $author = $picture['first_name'] . " " . $picture['last_name'];
            echo("<h5>By $author</h5>");
            if ($credit) {
              echo("<!––credit $credit ––>");
            }
            echo('</div>');
          }
        }
          $sql = "SELECT picid, title, picpath, credit FROM (SELECT * FROM pictags JOIN tags ON pictags.tagid = tags.id) JOIN pictures ON picid = pictures.id WHERE tag LIKE :tag;";
          $pictures = exec_sql_query($db,$sql,$params)->fetchAll();
          foreach($pictures as $picture){
            if(!in_array($picture['picid'], $ids)){
              $title = $picture['title'];
              $path = $picture['picpath'];
              $link = "pictures.php?id=" . $picture['picid'] . "&title=$title";
              $credit = $picture['credit'];
              echo('<div class = "block">');
              echo("<h4>$title</h4>");
              echo("<a href = $link><img src='$path' alt='$title'></a>");
              echo("<h5>By Anonymous</h5>");
              if ($credit) {
                echo("<!––credit $credit ––>");
              }
              echo('</div>');
            }
          }
      } else if ($search) {
        $sql = "SELECT DISTINCT tag FROM tags WHERE tag LIKE '%' || :tag || '%';";
        $params = array(":tag" => $search);
        $tags = exec_sql_query($db,$sql,$params)->fetchAll();
        if ($tags){
        foreach($tags as $tag){
          $t = $tag['tag'];
          echo("<div class = 'tagblock'><h4><a id = 'tags' href = 'alltags.php?tag=$t'>$t</a></h4></div>");
          }
        } else {
          echo("<h4>No tags matching search word.<h4>");
        }
      }
    else{
      $sql = "SELECT DISTINCT tag FROM tags;";
      $params = array();
      $tags = exec_sql_query($db,$sql,$params);
      foreach($tags as $tag){
        $t = $tag['tag'];
        echo("<div class = 'tagblock'><h4><a id = 'tags' href = 'alltags.php?tag=$t'>$t</a></h4></div>");
      }
    }
    ?>
</div>
</body>
</html>
