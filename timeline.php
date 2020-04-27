<?php
    session_start();
    require("dbconnenct.php");

    $errors = array();

    // 投稿時の処理
    if (!empty($_POST)) {
        $feed = $_POST["feed"];

        if ($feed != "") {
            $sql = "INSERT INTO `feeds` SET `feed`=?, `user_id`=?, `created`=NOW()";
            $data = array($feed, $signin_user["id"]);
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);

            header("Location: timeline.php");
            exit();
        }else{
            $errors["feed"] = "blank";
        }
    }

    $sql = "SELECT * FROM `users` WHERE `id`=?";
    $data = array($_SESSION["id"]);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    $signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 表示用の配列用意
    $feeds = array();

    // テーブル結合
    $sql = "SELECT `f`.*, `u`.`name`, `u`.`img_name` FROM `feeds` AS `f` LEFT JOIN `users` AS `u` ON `f`.`user_id` = `u` . `id` ORDER BY `created` DESC";
    $data = array();
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    while (true) {
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($record == false) {
            break;
        }
        // SQLの実行結果を連想配列で取得
        $feeds[] = $record;
    }

    // 取得している1feedに対するコメント情報取得
    $comment_sql = "SELECT `c`.*, `u`.`name`, `u`.`img_name` FROM `comments` AS `c` LEFT JOIN `users` AS `u` ON `c`.`user_id` = `u`.`id` WHERE `feed_id`=?";
    $comment_data = array($feed["id"]);
    $comment_stmt = $dbh->prepare($comment_$sql);
    $comment_stmt->execute($comment_data);

    $comments_array = array();

    while (true) {
        $comment_record = $comment_stmt->fetch(PDO::FETCH_ASSOC);
        if ($comment_record == false) {
            break;
        }
        $commnets_array[] = $commnet_record;
    }

    $record["comments"] = $comments_array;


?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body style="margin-top: 60px; background: #E4E6EB;">
  <div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Learn SNS</a>
      </div>
      <div class="collapse navbar-collapse" id="navbar-collapse1">
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">タイムライン</a></li>
          <li><a href="#">ユーザー一覧</a></li>
        </ul>
        <form method="GET" action="" class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" name="search_word" class="form-control" placeholder="投稿を検索">
          </div>
          <button type="submit" class="btn btn-default">検索</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="user_profile_img/<?php echo $signin_user['img_name']; ?>" width="18" class="img-circle"><?php echo $signin_user["name"]; ?><span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">マイページ</a></li>
              <li><a href="signout.php">サインアウト</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-xs-3">
        <ul class="nav nav-pills nav-stacked">
          <li class="active"><a href="timeline.php?feed_select=news">新着順</a></li>
          <li><a href="timeline.php?feed_select=likes">いいね！済み</a></li>
          <!-- <li><a href="timeline.php?feed_select=follows">フォロー</a></li> -->
        </ul>
      </div>
      <div class="col-xs-9">
        <div class="feed_form thumbnail">
          <form method="POST" action="">
            <div class="form-group">
              <textarea name="feed" class="form-control" rows="3" placeholder="Happy Hacking!" style="font-size: 24px;"><?php if (isset($errors["feed"]) && $errors["feed"] == "blank"): ?>
                <p class="alert alert-danger">投稿データを入力してください</p>
              <?php endif; ?></textarea><br>
            </div>
            <input type="submit" value="投稿する" class="btn btn-primary">
          </form>
        </div>
        <?php foreach ($feeds as $feed): ?>
          <div class="thumbnail">
            <div class="row">
              <div class="col-xs-1">
                <img src="user_profile_img/<?php echo $feed['img_name']; ?>" width="40">
              </div>
              <div class="col-xs-11">
                <?php echo $feed["name"]; ?><br>
                <a href="#" style="color: #7F7F7F;"><?php echo $feed["created"]; ?></a>
              </div>
            </div>
            <div class="row feed_content">
              <div class="col-xs-12" >
                <span style="font-size: 24px;"><?php echo $feed["feed"]; ?></span>
              </div>
            </div>
            <div class="row feed_sub">
              <!-- いいね、Noいいねの判定をDBのフラグにて行う -->
              <div class="col-xs-12">
                <?php if ($feed["like_flag"] == 0): ?>
                  <a href="like.php?feed_id=<?php echo $feed['id']; ?>"><button class="btn btn-default btn-xs"><i class="fa fa-thumbs-up" aria-hidden="true"></i>いいね！</button></a>
                <?php else: ?>
                  <a href="unlike.php?feed_id=<?php echo $feed['id']; ?>"><button type="submit" class="btn btn-default btn-xs"><i class="fa fa-thumbs-up" aria-hidden="true"></i>いいね！を取り消す</button>
                <?php endif; ?>
                <?php if ($feed["like_count"] > 0): ?>
                  <span class="like_count">いいね数 : <?php echo $feed["like_count"]; ?></span>
                <?php endif; ?>

                <?php if ($feed["comment_count"] == 0): ?>
                  <span class="comment_count">コメント</span>
                <?php else: ?>
                  <span class="comment_count">コメント数 : <?php echo $feed["comment_count"]; ?></span>
                <?php endif; ?>

                <!-- 自身の投稿のみ編集と削除可能に条件分岐 -->
                <?php if ($feed["user_id"] == $_SESSION["id"]): ?>
                  <a href="edit.php?feed_id=<?php echo $feed['id']; ?>" class="btn btn-success btn-xs">編集</a>
                  <!-- onclickで関数呼び出し -->
                  <a onclick="return confirm('ほんとに消すの？')" href="delete.php?feed_id=<?php echo $feed['id']; ?>" class="btn btn-danger btn-xs">削除</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <div aria-label="Page navigation">
          <ul class="pager">
            <li class="previous disabled"><a href="#"><span aria-hidden="true">&larr;</span> Older</a></li>
            <li class="next"><a href="#">Newer <span aria-hidden="true">&rarr;</span></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>
