<?php
    session_start();

    require("dbconnect.php");

    // フォローされる人のID取得
    $user_id = $_GET["user_id"];

    // フォローボタン押した人のID取得
    // サインインユーザー
    $follower_id = $_GET["user_id"];

    $sql = "INSERT INTO `followers` SET `id`=NULL, `user_id`=?, `follower_id`=?";
    $data = array($usr_id, $follower_id);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    // フォロー直前の画面に戻る
    header("Location: profile.php?user_id=" . $user_id);
?>