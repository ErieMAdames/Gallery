<?php
$page = NULL;
include('../includes/init.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" type="text/css" href="../style.css" media="all"/>

    <title>Home</title>
</head>
<?php
if ($current_user == NULL) {
    header('location: ../main');
}
?>
<body>
<header id="header">
    <nav id="nav">
        <?php
        if ($current_user) {
        echo("<h1>$first_n $last_n</h1>");
        ?>
        <ul>
            <li>
                <a href="">My Pictures</a>
            </li>
            <?php
            } else {
            echo("<h1>John Doe</h1>");
            ?>
            <ul>
                <li>
                    <a href="../">Sign In</a>
                </li>
                <?php
                }
                ?>
                <li>
                    <a href="../main">All Pictures</a>
                </li>
                <li>
                    <a href="../alltags">All Tags</a>
                </li>
                <?php
                if ($current_user) {
                    ?>
                    <li>
                        <a href="../addpictures">Add Pictures</a>
                    </li>
                    <li>
                        <a href="../logout.php">Log out</a>
                    </li>
                <?php } ?>
            </ul>
    </nav>
</header>
<div id="body">
    <div id="top">
        <h1 class="title">My Pictures</h1>
    </div>
    <div id="page">
        <?php
        $sql = "SELECT title, picpath, id, credit FROM pictures INNER JOIN users ON pictures.user = users.userid WHERE username LIKE :username;";
        $params = array(':username' => $current_user);
        $pictures = exec_sql_query($db, $sql, $params);
        foreach ($pictures as $picture) {
            echo('<div class = "block">');
            $path = $picture['picpath'];
            $title = $picture['title'];
            $linktitle = explode(' ', $title);
            $linktitle = implode('%20', $linktitle);
            $link = "../pictures?id=" . $picture['id'] . "&title=$linktitle";
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
</div>
</body>
</html>
