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

    // サインインしているユーザーの情報
    $signin_sql = "SELECT * FROM `users` WHERE `id`=?";
    $signin_data = array($_SESSION["id"]);
    $signin_stmt = $dbh->prepare($signin_sql);
    $signin_stmt->execute($signin_data);
    $signin_user = $signin_stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM `users` WHERE `id`=?";
    $data = array($user_id);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $profile_user = $stmt->fetch(PDO::FETCH_ASSOC);

    // フォロー一覧の取得
    // 表示されているプロフィール主がフォローしている人の一覧（ログイン者 or パラメータで指定したID主）
    // ?がフォローしている人を取り出す
    $following_sql = "SELECT `fw`.*, `u`.name, `u`.img_name, `u`.`created` FROM `followers` AS `fw` LEFT JOIN `users` AS `u` ON `fw`.`user_id` = `u`.`id` WHERE `follower_id`=?";
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

    // フォロワー一覧の取得
    // ?をフォローしている人を取得する
    $followers_sql = "SELECT `fw`.*, `u`.`name`, `u`.`img_name`, `u`.`created` FROM `followers` AS `fw` LEFT JOIN `users` AS `u` ON `fw`.`followers_id` = `u`.`id` WHERE `user_id`=?";
    $followers_data = array($user_id);
    $followers_stmt = $dbh->prepare($followers_sql);
    $followers_stmt->execute($followers_data);

    $followers = array();

    // フラグで判定
    // サインインユーザーが一覧表示されているユーザーをフォローしていたら１、していなければ０
    $follow_flag = 0;

    while (true) {
        $followers_record = $folloers_stmt->fetch(PDO::FETCH_ASSOC);

        if ($followers_record == false) {
            break;
        }

        // サインインユーザーがフォローしている人がいるかをチェック
        if ($followers_record["follower_id"] == $_SESSION["id"]) {
            $follower_flag = 1;
        }
        $followers[] = $followers_record;
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

  <!-- 使い回しのナビゲーションバー読み込み -->
  <?php include("navbar.php"); ?>

  <div class="container">
    <div class="row">
      <div class="col-xs-3 text-center">
        <img src="user_profile_img/<?php echo $profile_user['img_name']; ?>" class='img-thumbnail'>
        <h3><?php echo $profile_user["name"]; ?></h3>

        <?php if ($user_id != $_SESSION["id"]): ?>
          <?php if ($follow_flag == 0): ?>
            <a href="follow.php?user_id=<?php echo $profile_user['id']; ?>"><button class="btn btn-default btn-block">フォローする</button>></a>
          <?php else: ?>
            <a href="unfollow.php?user_id=<?php echo $profile_user["id"]; ?>"><button class="btn btn-default btn-block">フォロー解除する</button></a>
          <?php endif; ?>
        <?php endif; ?>
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

        <!-- followersの中身 -->
        <div class="tab-content">
          <div id="tab1" class="tab-pane fadein active">
            <?php foreach ($followers as $follower): ?>
              <div class="thumbnail">
                <div class="row">
                  <div class="col-xs-2">
                    <img src="user_profile_img/<?php echo $follower["img_name"]; ?>" width="80">
                  </div>
                  <div class="col-xs-10">
                    名前 <?php echo $follower["name"]; ?><br>
                    <a href="#" style="color: #7F7F7F;"><?php echo $follower["created"]; ?>からメンバー</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
                    
        <!-- followingの中身 -->
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


      <!-- followerの中身 -->
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>