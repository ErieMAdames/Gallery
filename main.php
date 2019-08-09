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
if($search){
  echo("<h1>Search: $search<h1>");
}
else {
?>
  <h1>All Pictures</h1>
<?php } ?>
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
<div id="search">
  <form action="main.php" method="get">
    <label>Search by tags:</label>
      <input type="text" name="search"/>
      <button name="Search" type="submit">Search</button>
  </form>
</div>
<div id = "page">
<?php
if ($search) {
  $sql = "SELECT picid, title, picpath, first_name, last_name, tag, credit FROM (SELECT picid, title, picpath, user, tag FROM (SELECT * FROM pictags JOIN tags ON pictags.tagid = tags.id) JOIN pictures ON picid = pictures.id WHERE tag LIKE '%' || :tag || '%') LEFT JOIN users ON user = users.userid;";
  $params = array(":tag" => $search);
  $pictures = exec_sql_query($db,$sql,$params);
  foreach($pictures as $picture){
    $path = $picture['picpath'];
    $title = $picture['title'];
    $linktitle = explode(' ',$title);
    $linktitle = implode('%20', $linktitle);
    $tag = $picture['tag'];
    $link = "pictures.php?id=" . $picture['picid'] . "&title=$linktitle";
    $credit = $picture['credit'];
    echo('<div class = "block">');
    echo("<h4>$title </h4>");
    echo("<h5>Tag: $tag</h5>");
    echo("<a href = $link><img src='$path' alt='$title'></a>");
    if ($picture['first_name']){
      $uploader = $picture['first_name'] . " " . $picture['last_name'];
      echo("<h5>Uploaded by $uploader</h5>");
    } else {
      echo("<h5>Uploaded by Anonymous</h5>");
    } if ($credit) {
      echo("<!––credit $credit ––>");
    }
    echo("</div>");
  }
} else {
  $sql = "SELECT id, title, picpath, first_name, last_name, credit FROM pictures LEFT JOIN users ON pictures.user = users.userid;";
  $params = array();
  $pictures = exec_sql_query($db,$sql,$params);
  foreach($pictures as $picture){
    $path = $picture['picpath'];
    $title = $picture['title'];
    $linktitle = explode(' ',$title);
    $linktitle = implode('%20', $linktitle);
    $link = "pictures.php?id=" . $picture['id'] . "&title=$linktitle";
    $credit = $picture['credit'];
    echo('<div class = "block">');
    echo("<h4>$title</h4>");
    echo("<a href = $link><img src='$path' alt='$title'></a>");
    if ($picture['first_name']){
      $uploader = $picture['first_name'] . " " . $picture['last_name'];
      echo("<h5>Uploaded by $uploader</h5>");
    } else {
      echo("<h5>Uploaded by Anonymous</h5>");
    } if ($credit) {
      echo("<!––credit $credit ––>");
    }
    echo("</div>");
  }
}
?>
</div>
</body>
</html>
