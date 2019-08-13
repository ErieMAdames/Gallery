<?php
$page = NULL;
include('../includes/init.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="all"/>

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
            <a href="../mypictures">My Pictures</a>
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
                <a href="../main">All Pictures</a>
                <a href="../alltags">All Tags</a>
                <?php
                if ($current_user) {
                    ?>
                    <a href="../addpictures">Add Pictures</a>
                    <a href="../logout.php">Log out</a>
                <?php } else { ?>
                    <a href="../">Sign In</a><?php } ?>
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
                <a href="../mypictures">My Pictures</a>
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
                    <a href="../main">All Pictures</a>
                </li>
                <li>
                    <a href="">All Tags</a>
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
                <?php } else { ?>
                    <li>
                        <a href="../">Sign In</a>
                    </li> <?php } ?>
            </ul>
    </nav>
</header>
<div id="body">
    <div id="top">
        <?php
        $search = filter_input(INPUT_GET, "search", FILTER_SANITIZE_STRING);
        $tag = filter_input(INPUT_GET, "tag", FILTER_SANITIZE_STRING);
        if ($search and !$tag) {
            echo("<h1>Search: $search<h1>");
        } else if (!$search and $tag) {
            echo("<h1>Tag: $tag<h1>");
        } else {
            ?>
            <h1>All Tags</h1>
        <?php } ?>
    </div>
    <div id="search">
        <form action="" method="get">
            <label>Search tags:<label>
                    <input type="text" name="search"/>
                    <button name="Search" type="submit">Search</button>
        </form>
    </div>
    <div id="page">
        <div id="tagcontainer">
            <?php
            if ($tag) {
                $sql = "SELECT picid, title, picpath, first_name, last_name, credit FROM (SELECT picid, title, picpath, user, credit FROM (SELECT * FROM pictags JOIN tags ON pictags.tagid = tags.id) JOIN pictures ON picid = pictures.id WHERE tag LIKE :tag) JOIN users ON user = users.userid;";
                $params = array(':tag' => $tag);
                $pictures = exec_sql_query($db, $sql, $params)->fetchAll();
                $ids = array();
                if ($pictures) {
                    foreach ($pictures as $picture) {
                        $title = $picture['title'];
                        $path = $picture['picpath'];
                        $credit = $picture['credit'];
                        array_push($ids, $picture['picid']);
                        $link = "../pictures?id=" . $picture['picid'] . "&title=$title";
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
                $pictures = exec_sql_query($db, $sql, $params)->fetchAll();
                foreach ($pictures as $picture) {
                    if (!in_array($picture['picid'], $ids)) {
                        $title = $picture['title'];
                        $path = $picture['picpath'];
                        $link = "../pictures?id=" . $picture['picid'] . "&title=$title";
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
                $tags = exec_sql_query($db, $sql, $params)->fetchAll();
                if ($tags) {
                    foreach ($tags as $tag) {
                        $t = $tag['tag'];
                        echo("<div class = 'tagblock'><h4><a id = 'tags' href = '?tag=$t'>$t</a></h4></div>");
                    }
                } else {
                    echo("<h4>No tags matching search word.<h4>");
                }
            } else {
                $sql = "SELECT DISTINCT tag FROM tags;";
                $params = array();
                $tags = exec_sql_query($db, $sql, $params);
                foreach ($tags as $tag) {
                    $t = $tag['tag'];
                    echo("<div class = 'tagblock'><h4><a id = 'tags' href = '?tag=$t'>$t</a></h4></div>");
                }
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
