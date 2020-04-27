<?php
    reauire("dbconnect.php");

    $login_user_id = $_SESSION["id"];
    $comment = $_POST["write_comment"];
    $feed_id = $_POST["feed_id"];

    // コメント情報を登録
    $sql = "INSERT INTO `comments` SET `comment`=?, `user_id`=?, `feed_id`=?, `created`=now()";
    $data = array($login_user_id, $feed_id);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    // 表示されるコメント数をアップデート
    $update_sql = "UPDATE `feeds` SET `comment_count` = `comment_count`+1 WHERE `id`=?";
    $update_data = array($feed_id);
    $update_stmt = $dbh->prepare($update_sql);
    $update_stmt->execute($update_data);

    // コメント登録処理後にタイムライン画面へ戻る
    header("Location: timeline.php");
?>