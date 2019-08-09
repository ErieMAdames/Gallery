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
if($current_user == NULL){
  header('location: main.php');
}
?>
<body id = "body">
<div id = "top">
<h1 class = "title">My Pictures</h1>
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
<div id = "page">
<?php
$sql = "SELECT title, picpath, id, credit FROM pictures INNER JOIN users ON pictures.user = users.userid WHERE username LIKE :username;";
$params = array(':username' =>$current_user);
$pictures = exec_sql_query($db,$sql,$params);
foreach($pictures as $picture){
  echo('<div class = "block">');
  $path = $picture['picpath'];
  $title = $picture['title'];$linktitle = explode(' ',$title);
  $linktitle = implode('%20', $linktitle);
  $link = "pictures.php?id=" . $picture['id'] . "&title=$linktitle";
  $credit = $picture['credit'];
  echo("<h4>$title</h4>");
  echo("<a href = $link><img src='$path' alt='$title'></a>");
  if ($credit) {
    echo("<!––credit $credit ––>");
  }
  echo('</div>');
}
?>
</div>
</body>
</html>
