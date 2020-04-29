<?php
    session_start();
    require("dbconnect.php");

    if (isset($_GET["user_id"])) {
        $user_id = $_GET["user_id"];
        // http://localhost/LearnSNS/profile.php?user_id=8 のようにアクセスされた時
    }else{
        // パラメータなしでアクセスされた時
        $user_id = $_SESSION["id"];
    }

    $sql = "SELECT * FROM `users` WHERE `id`=?";
    $data = array($user_id);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $profile_user = $stmt->fetch(PDO::FETCH_ASSOC);

    // フォロー一覧の取得
    // 表示されているプロフィール主がフォローしている人の一覧（ログイン者 or パラメータで指定したID主）
    $following_sql = "SELECT `fw`.*, `u`.name, `u`.img_name, `u`.`created` FROM `followers` AS `fw` LEFT JOIN `users` AS `u` ON `fw`.`follower_id` = `u`.`id` WHERE `user_id`=?";
    $following_data = array($user_id);
    $following_stmt = $dbh->prepare($following_sql);
    $following_stmt->execute($following_data);
    $following = array();

    while (true) {
        $following_record = $following_stmt->fetch(PDO::FETCH_ASSOC);
        if ($following_record == false) {
            break;
        }
        $following[] = $following_record;
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
<body style="margin-top: 60px; background: #E4E6EB; ">
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="" href="/">LearnSNS</a>
      </div>
      <div class="collapse navbar-collapse" id="navbar-collapse1">
        <ul class="nav navbar-nav">
          <li><a href="timeline.php">タイムライン</a></li>
          <li class="active"><a href="#">ユーザー一覧</a></li>
        </ul>
        <form action="" method="get" class="navbar-form navbar-left" role="search">
          <div type="text" name="search_word" class="form-control" placeholder="投稿を検索">
          </div>
          <button type="submit" class="btn btn-default">検索</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="" width="18" class="img-circle">test <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">マイページ</a></li>
              <li><a href="signout.php">サインアウト</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-xs-3 text-center">
        <img src="user_profile_img/<?php echo $profile_user['img_name']; ?>" class='img-thumbnail'>
        <h2><?php echo $profile_user["name"]; ?></h2>
        <button class="btn btn-default btn-block">フォローする</button>
      </div>
      <div class="col-xs-9">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#tab1" data-toggle="tab">Followers</a>
          </li>
          <li>
            <a href="#tab2" data-toggle=tab>Following</a>
          </li>
        </ul>
        <?php foreach ($following as $following_user): ?>
          <div class="thumbnail">
            <div class="row">
              <div class="col-xs-2">
                <img src="user_profile_img/<?php echo $following_user['img_name']; ?>" width='80'>
              </div>
              <div class="col-xs-10">
                名前 <?php echo $following_user['name']; ?><br>
                <a href="#" style="color: #7F7F7F;"><?php echo $following_user['created']; ?>からメンバー</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>