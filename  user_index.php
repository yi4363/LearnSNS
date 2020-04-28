<?php
    // 各ユーザーのツイート数を表示する画面

    require("dbconnect.php");

    // ユーザー一覧表示用のデータ取得
    $sql = "SELECT * FROM `users`";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    while (ture) {
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($record == false) {
            break;
        }

        // 各ユーザーのツイート数を取得
        // COUNT(*)でレコード数を取得
        // 集計関数の一つ
        $feed_sql = "SELECT COUNT(*) AS feed_count FROM `feeds` WHERE `user_id`=?";
        $feed_data = array($record["id"]);
        $feed_stmt = $dbh->prepare($feed_sql);
        $feed_stmt->execute($feed_data);

        $feed = $feed_stmt->fetch(PDO::FETCH_ASSOC);
        // $feed = array("feed_count=>8")など

        $record["feed_count"] = $feed["feed_count"];

        // 配列の追加代入
        $users[] = $record;
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>LearnSNS</title>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body style="margin-top: 60px; background: #E4E6EB">
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">LearnSNS</a>
      </div>
      <div class="collapse navbar-collapse" id="navbar-collapse1">
        <ul class="nav navbar-nav">
          <li><a href="timeline.php">タイムライン</a></li>
          <li class="active"><a href="#">ユーザー一覧</a></li>
        </ul>
          <form action="" method="get" class="navbar-form navbar-left" role="search">
            <div class="form-group">
              <input type="text" name="search_word" class="form-control" placeholder="投稿を検索">
            </div>
            <button type="submit" class="btn btn-default">検索</button>
          </form>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="" width="18" class="img-circle">test <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#"><マイページ</a></li>
                <li><a href="signout.php">サインアウト</a></li>
              </ul>
            </li>
          </ul>
      </div>
    </div>
  </nav>
  <div class="container">
    <?php foreach ($users as $user): ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="thmbnail">
            <div class="row">
              <div class="col-xs-1">
                <img src="user_profile_img/<?php echo $user['img_name']; ?>" width="80">
              </div>
              <div class="col-xs-11">
                名前 <?php echo $user["name"]; ?><br>
                <a href="" style="color: #7F7F7F;"><?php echo $user['created']; ?>からメンバー</a>
              </div>
            </div>
            <div class="row feed_sub">
              <div class="col-xs-12">
                <span class="comment_count">つぶやき数: <?php echo $user['feed_count']; ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>