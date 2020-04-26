<?php
    $dsn = "mysql:dbname=LearnSNS;host=localhost";
    $user = "root";
    $password = "";
    $dbh = new PDO($dsn, $user, $password);

    // SQL文にエラーがある場合にその旨画面表示
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->query("SET NAMES utf8");
?>