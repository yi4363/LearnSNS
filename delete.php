<?php
    require("dbconnect.php");

    // feed_id取得
    $feed_id = $_GET["feed_id"];

    $sql = "DELETE FROM `feeds` WHERE `feeds` . `id` = ?";
    $data = array($feed_id);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    // 投稿削除後にタイムライン画面へ戻る
    header("Location: timeline.php");
?>