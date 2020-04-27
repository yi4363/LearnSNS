<?php
    session_start();

    require("dbconnect.php");

    $feed_id = $_GET["feed_id"];

    // いいね取り消し処理
    $sql = "DELETE FROM `likes` WHERE `user_id`=? AND `feed_id`=?";
    $data = array($_SESSIOM["id"], $feed_id);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    header("Location: timeline.php");
?>