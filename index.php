<?php
$page = "base";
include('includes/init.php');
if ($redirect or $current_user) {
    header('location: /main');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" type="text/css" href="css/login.css" media="all"/>

    <title>Home</title>
</head>
<body>
<div class="login-page">
    <div class="form">
        <form class="login-form" action="index.php" method="post">
            <input type="text" placeholder="username" name="username" required/>
            <input type="password" placeholder="password" name="password" required/>
            <button name="login" type="submit">Log In</button>
            <a class="message" href="/main">Continue without signing in</a>
        </form>
    </div>
    <?php print_messages(); ?>
</div>
</body>
</html>
