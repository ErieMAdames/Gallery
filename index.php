<?php include('includes/init.php');
if ($redirect or $current_user){
  header('location: main.php');
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="style.css" media="all" />

  <title>Home</title>
</head>
<body id = "log">
  <div class = "logincont">
    <div class = "login">
      <!-- <img src='loginpic.png' alt='login'> -->
      <form action="index.php" method="post">
        <ul>
          <li>
            <label>Username</label>
            <input type="text" name="username" required/>
          </li>
          <li>
            <label>Password</label>
            <input type="password" name="password" required/>
          </li>
          <li>
            <button name="login" type="submit">Log In</button>
          </li>
          <li>
            <a href="main.php">Continue without signing in</a>
          </li>
        </ul>
      </form>
      <?php print_messages();?>
    </div>
  </div>
</body>
</html>
