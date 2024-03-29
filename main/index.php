<?php
$page = NULL;
include('../includes/init.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://radiant-citadel-46031.herokuapp.com/css/style.css" media="all"/>

    <title>Home</title>
</head>
<body>
<header id="header">
    <nav id="mobilenav">
        <?php
        if ($current_user) {
        echo("<h1>$first_n $last_n</h1>");
        ?>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
            <i class="fa fa-bars"></i>
        </a>
        <div id="myLinks">
            <a href="https://radiant-citadel-46031.herokuapp.com/mypictures">My Pictures</a>
            <?php
            } else { ?>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
            <h1>John Doe</h1>
            <div id="myLinks">
                <?php
                }
                ?>
                <a href="https://radiant-citadel-46031.herokuapp.com/main">All Pictures</a>
                <a href="https://radiant-citadel-46031.herokuapp.com/alltags">All Tags</a>
                <?php
                if ($current_user) {
                    ?>
                    <a href="https://radiant-citadel-46031.herokuapp.com/addpictures">Add Pictures</a>
                    <a href="https://radiant-citadel-46031.herokuapp.com/logout.php">Log out</a>
                <?php } else { ?>
                    <a href="https://radiant-citadel-46031.herokuapp.com/">Sign In</a><?php } ?>
            </div>
            <script>
                function myFunction() {
                    var x = document.getElementById("myLinks");
                    if (x.style.display === "block") {
                        x.style.display = "none";
                    } else {
                        x.style.display = "block";
                    }
                }
            </script>
    </nav>
    <nav id="nav">
        <?php
        if ($current_user) {
        echo("<h1>$first_n $last_n</h1>");
        ?>
        <ul>
            <li>
                <a href="https://radiant-citadel-46031.herokuapp.com/mypictures">My Pictures</a>
            </li>
            <?php
            } else {
            echo("<h1>John Doe</h1>");
            ?>
            <ul>
                <?php
                }
                ?>
                <li>
                    <a href="https://radiant-citadel-46031.herokuapp.com/main">All Pictures</a>
                </li>
                <li>
                    <a href="https://radiant-citadel-46031.herokuapp.com/alltags">All Tags</a>
                </li>
                <?php
                if ($current_user) {
                    ?>
                    <li>
                        <a href="https://radiant-citadel-46031.herokuapp.com/addpictures">Add Pictures</a>
                    </li>
                    <li>
                        <a href="https://radiant-citadel-46031.herokuapp.com/logout.php">Log out</a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="https://radiant-citadel-46031.herokuapp.com/">Sign In</a>
                    </li> <?php } ?>
            </ul>
    </nav>
</header>
<div id="body">
    <?php
    $search = filter_input(INPUT_GET, "search", FILTER_SANITIZE_STRING);
    if ($search) {
        echo("<div id='top'><h1>Search: $search<h1></div>");
    } ?>
    <h1>All Pictures</h1>
    <div id="search">
        <form action="https://radiant-citadel-46031.herokuapp.com/main" method="get">
            <label>Search by tags:</label>
            <input type="text" name="search"/>
            <button name="Search" type="submit">Search</button>
        </form>
    </div>
    <div id="page">
        <?php
        if ($search) {
            $sql = "SELECT picid, title, picpath, first_name, last_name, tag, credit FROM
        (SELECT picid, title, picpath, user, tag, credit FROM
            (SELECT * FROM pictags JOIN tags ON pictags.tagid = tags.id) JOIN pictures ON picid = pictures.id WHERE tag LIKE '%' || :tag || '%') LEFT JOIN users ON user = users.userid;";
            $params = array(":tag" => $search);
            $pictures = exec_sql_query($db, $sql, $params);
            foreach ($pictures as $picture) {
                $path = $picture['picpath'];
                $title = $picture['title'];
                $linktitle = explode(' ', $title);
                $linktitle = implode('%20', $linktitle);
                $tag = $picture['tag'];
                $link = "../pictures?id=" . $picture['picid'] . "&title=$linktitle";
                $credit = $picture['credit'];
                echo('<div class = "block">');
                echo("<h4>$title </h4>");
                if ($picture['first_name']) {
                    $uploader = $picture['first_name'] . " " . $picture['last_name'];
                    echo("<h5>Uploaded by $uploader</h5>");
                } else {
                    echo("<h5>Uploaded by Anonymous</h5>");
                }
                echo("<h5>Tag: $tag</h5>");
                echo('<div class = "blockcontainer">');
                echo("<a href = $link><img src='$path' alt='$title'></a>");
                if ($credit) {
                    echo("<!––credit $credit ––>");
                }
                echo("</div>");
                echo("</div>");
            }
        } else {
            $sql = "SELECT id, title, picpath, first_name, last_name, credit FROM pictures LEFT JOIN users ON pictures.user = users.userid;";
            $params = array();
            $pictures = exec_sql_query($db, $sql, $params);
            foreach ($pictures as $picture) {
                $path = $picture['picpath'];
                $title = $picture['title'];
                $linktitle = explode(' ', $title);
                $linktitle = implode('%20', $linktitle);
                $link = "../pictures?id=" . $picture['id'] . "&title=$linktitle";
                $credit = $picture['credit'];
                echo('<div class = "block">');
                echo("<h4>$title</h4>");
                if ($picture['first_name']) {
                    $uploader = $picture['first_name'] . " " . $picture['last_name'];
                    echo("<h5>Uploaded by $uploader</h5>");
                } else {
                    echo("<h5>Uploaded by Anonymous</h5>");
                }
                echo('<div class = "blockcontainer">');
                echo("<a href = $link><img src='$path' alt='$title'></a>");
                if ($credit) {
                    echo("<!––credit $credit ––>");
                }
                echo("</div>");
                echo("</div>");
            }
        }
        ?>
    </div>
</div>
</body>
</html>
