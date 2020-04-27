<?php
    require("dbconnect.php");

    $feed_id = $_GET["feed_id"];

    // 更新ボタン押下時のアップデート処理
    // ?に対応する値をarrayにてセット
    // プリペアードステートメント
    if (!empty($_POST)) {
        $update_sql = "UPDATE `feeds` SET `feed`=? WHERE `feeds` . `id`=?";
        $data = array($_POST["feed"], $feed_id);
        $stmt = $dbh->prepare($sql_update);
        $stmt->execute($data);

        // 更新後はタイムラインに戻る
        header("Location: timeline.php");
    }

    // 編集したい投稿データ初期表示用
    $sql = "SELECT `feeds`.*, `users`.`name`, `users`.`img_name` FROM `feeds` LEFT JOIN `users` ON `feeds` . `user_id` = `users` . `id` WHERE `feeds`.`id` = $feed_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $feed = $stmt->fetch(PDO::FETCH_ASSOC);
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
<body style="margin-top: 60px;">
  <div class="container">
    <div class="row">
      <div class="col-xs-4 col-xs-offset-4">
        <form class="form-group" method="post">
          <img src="user_profile_img/<?php echo $feed['img_name']; ?>" width="60">
          <?php echo $feed["name"]; ?><br>
          <?php echo $feed["created"]; ?><br>
          <textarea name="feed" class="form-control"><?php echo $feed['feed']; ?></textarea>
          <input type="submit" value="更新" class="btn btn-warning btn-xs">
        </form>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>