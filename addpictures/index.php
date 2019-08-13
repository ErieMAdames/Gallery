<?php
$page = NULL;
include('../includes/init.php');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="all"/>

    <title>Home</title>
</head>
<?php
if ($current_user == NULL) {
    header('location: ../main');
}
const BOX_UPLOADS_PATH = "../uploads/pictures/";
if (isset($_POST["upload"]) and $current_user) {
    $bfile_info = $_FILES["upic"];
    $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
    $temp_tags = filter_input(INPUT_POST, "tags", FILTER_SANITIZE_STRING);
    if ($bfile_info["error"] == 0) {
        $filename = basename($bfile_info["name"]);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $picpath = "placeholder";
        $sql = "INSERT INTO pictures (title, picpath, ext, user)
                          VALUES (:title, :picpath, :ext, (SELECT userid FROM users WHERE session = :session))";
        $params = array(":title" => $title,
            ":picpath" => $picpath,
            ":ext" => $ext,
            ":session" => $session);
        exec_sql_query($db, $sql, $params);
        $id = $db->lastInsertId("id");
        $picpath = BOX_UPLOADS_PATH . $id . "." . $ext;
        $sql = "UPDATE pictures SET picpath = :picpath WHERE id = :id;";
        $params = array(":picpath" => $picpath,
            ":id" => $id);
        exec_sql_query($db, $sql, $params);
        move_uploaded_file($bfile_info["tmp_name"], $picpath);
        $uploadsuccess = TRUE;
        if ($temp_tags) {
            $temp_tags = str_replace(array("\r\n", "\n", "\r"), ' ', $temp_tags);
            $tags = explode(',', $temp_tags);
            $sql = "INSERT INTO tags (tag) VALUES (:tag);";
            $sqltag = "INSERT INTO pictags (picid, tagid) VALUES (:picid, :tagid);";
            $tags = array_unique($tags);
            foreach ($tags as $c_tag) {
                $tag = ucfirst(trim($c_tag));
                if ($tag) {
                    $params = array(":tag" => $tag);
                    exec_sql_query($db, $sql, $params);
                    $tagid = $db->lastInsertId("id");
                    $params = array(":picid" => $id,
                        ":tagid" => $tagid);
                    exec_sql_query($db, $sqltag, $params);
                }
            }
        }
    }
}
?>
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
                    <a href="">Add Pictures</a>
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
                    <a href="../alltags">All Tags</a>
                </li>
                <?php
                if ($current_user) {
                    ?>
                    <li>
                        <a href="">Add Pictures</a>
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
        <h1 class="title">Add Picture</h1>
    </div>
    <div id="page">
        <?php
        if ($uploadsuccess){
        $sql = "SELECT title, picpath, first_name, last_name, id FROM pictures LEFT JOIN users ON pictures.user = users.userid WHERE id LIKE :picid";
        $params = array(':picid' => $id);
        $pictures = exec_sql_query($db, $sql, $params)->fetchAll();
        $picture = $pictures[0];
        $title = $picture['title'];
        $path = $picture['picpath'];
        $link = "../pictures?id=" . $picture['id'] . "&title=$title";
        echo("<div class = 'uploadsuccess'>");
        echo("<div class = 'addblock'><h4>$title</h4>");
        echo("<a href = $link><img src='$path' alt='$title'></a>");
        ?>
    </div>
    <h3>Upload Successful!</h3>
    <h3>Click <a href="">here</a> to add more.</h3>
</div>
<?php
}
elseif ($bfile_info["error"] == 1) {
    echo("<h3>File Size too large, click <a href = ''>here</a> to try again.</h3>");
}
else{ ?>
<form id="addpicture" action="" method="post" enctype="multipart/form-data">
    <ul>
        <li>
            <label>Title:</label>
            <input type="text" name="title" required>
        </li>
        <li>
            <label>Tags:</label>
            <textarea placeholder="Enter tags and separate them with a comma" cols='45' rows='10'
                      name="tags"></textarea>
        </li>
        <li>
            <label>Upload file size limit: 2Mb</label>
        </li>
        <li>
            <label>Upload Picture:</label>
            <input type="file" name="upic" required>
        </li>
        <li>
            <button id = "buttonbutton" name="upload" type="submit">Upload</button>
        </li>
    </ul>
    <?php } ?>
</form>
</div>
</div>

</body>
</html>
