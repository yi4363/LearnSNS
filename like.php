<?php
    session_start();

    require("dbconnect.php");

    $feed_id = $_GET["feed_id"];

    $sql = "INSERT INTO `likes` SET `user_id`=?, `feed_id`=?";
    // いいね者、いいね対象投稿を保存
    $data = array($_SESSION["id"], $feed_id);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    // タイムライン画面へ戻る
    header("Location: timeline.php");
?>