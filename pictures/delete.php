<?php
$page = "base";
include('../includes/init.php');
if (($_POST['user_id'] == $userid) and ($_POST['deletetag'] == 'deletetag')) {
    $id = filter_input(INPUT_POST, "picid", FILTER_SANITIZE_STRING);
    $tag = filter_input(INPUT_POST, "tag", FILTER_SANITIZE_STRING);
    $tagid = filter_input(INPUT_POST, "tagid", FILTER_SANITIZE_STRING);
    $title = filter_input(INPUT_POST, "pictitle", FILTER_SANITIZE_STRING);
    $sql = "DELETE FROM pictags WHERE tagid LIKE :tagid AND picid LIKE :id;";
    $params = array(':tagid' => $tagid,
        ':id' => $id);
    $sqltags_t = "SELECT DISTINCT id FROM tags;";
    $sqltags_pt = "SELECT DISTINCT tagid FROM pictags;";
    $eparams = array();
    exec_sql_query($db, $sql, $params);
    $tags_t = exec_sql_query($db, $sqltags_t, $eparams)->fetchAll();
    $tags_ta = array();
    foreach ($tags_t as $tagid) {
        array_push($tags_ta, $tagid[0]);
    }
    $tags_pt = exec_sql_query($db, $sqltags_pt, $eparams)->fetchAll();
    $tags_pta = array();
    foreach ($tags_pt as $tagid) {
        array_push($tags_pta, $tagid[0]);
    }
    $diff = array_diff($tags_ta, $tags_pta);
    $sql = "DELETE FROM tags WHERE id = :id;";
    if ($diff) {
        foreach ($diff as $d => $n) {
            print_r($n);
            $params = array(":id" => $n);
            exec_sql_query($db, $sql, $params);
        }
    }
    header("location: ../pictures?id=$id&title=$title");
} else if (($_POST['user_id'] == $userid) and ($_POST['deletepic'] == 'deletepic')) {
    $user = $_POST['user_id'];
    $id = $_POST['picid'];
    $title = $_POST['pictitle'];
    $picpath = $_POST['path'];
    $session = $_COOKIE["session"];
    $sql = "DELETE FROM pictures WHERE id LIKE :id AND title LIKE :title AND picpath LIKE :picpath AND user LIKE (SELECT userid FROM users WHERE session = :session);";
    $params = array(':id' => $id,
        ':title' => $title,
        ':picpath' => $picpath,
        ':session' => $session);
    exec_sql_query($db, $sql, $params);
    $sqlid = "SELECT DISTINCT id FROM pictures;";
    $sqlpicid = "SELECT DISTINCT picid FROM pictags;";
    $eparams = array();
    $id = exec_sql_query($db, $sqlid, $eparams)->fetchAll();
    $ids = array();
    foreach ($id as $i) {
        array_push($ids, $i[0]);
    }
    $picid = exec_sql_query($db, $sqlpicid, $eparams)->fetchAll();
    $picids = array();
    foreach ($picid as $id) {
        array_push($picids, $id[0]);
    }
    $diff = array_diff($picids, $ids);
    $sql = "DELETE FROM pictags WHERE picid = :id;";
    if ($diff) {
        foreach ($diff as $d => $n) {
            $params = array(":id" => $n);
            exec_sql_query($db, $sql, $params);
        }
    }
    $sqltags_t = "SELECT DISTINCT id FROM tags;";
    $sqltags_pt = "SELECT DISTINCT tagid FROM pictags;";
    $eparams = array();
    $tags_t = exec_sql_query($db, $sqltags_t, $eparams)->fetchAll();
    $tags_ta = array();
    foreach ($tags_t as $tagid) {
        array_push($tags_ta, $tagid[0]);
    }
    $tags_pt = exec_sql_query($db, $sqltags_pt, $eparams)->fetchAll();
    $tags_pta = array();
    foreach ($tags_pt as $tagid) {
        array_push($tags_pta, $tagid[0]);
    }
    $diff = array_diff($tags_ta, $tags_pta);
    $sql = "DELETE FROM tags WHERE id = :id;";
    if ($diff) {
        foreach ($diff as $d => $n) {
            print_r($n);
            $params = array(":id" => $n);
            exec_sql_query($db, $sql, $params);
        }
    }
    unlink("$picpath");
    header('location: ../mypictures');
} else {
    header('location: ../mypictures');
}
?>
