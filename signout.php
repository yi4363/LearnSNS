<?php
    session_start();

    // セッション変数を空に
    $_SESSION = [];

    // サーバー内のセッションを破壊
    // サインアウト時はセッションを空にする
    session_destroy();

    header("Location: signin.php");
    exit();
?>