<?php
$page = "base";
include('https://radiant-citadel-46031.herokuapp.com/includes/init.php');
if ($redirect or $current_user) {
    header('location: https://radiant-citadel-46031.herokuapp.com/main');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" type="text/css" href="https://radiant-citadel-46031.herokuapp.com/css/login.css" media="all"/>

    <title>Home</title>
</head>
<body>
<div class="login-page">
    <div class="form">
        <form class="login-form" action="https://radiant-citadel-46031.herokuapp.com/index.php" method="post">
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
