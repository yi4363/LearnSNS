<?php
    // セッション使用宣言
    session_start();

    // タイムゾーン設定
    date_default_timezone_set("Asia/Tokyo");

    // エラー の種類を保存する配列
    $errors = array();

    if (!empty($_POST)) {
        $name = $_POST["input_name"];
        $email = $_POST["input_email"];
        $password = $_POST["input_password"];
        // null, "", 空以外なら通す

        if ($name == "") {
            $errors["name"] = "blank";
        }

        if ($email == "") {
            $errors["email"] = "blank";
        }

        $count = strlen($password);
        // string, length
        // 文字数チェック

        if ($password == "") {
            $errors["password"] = "blank";
        }elseif ($count < 4 || $count > 16) {
            $errors["password"] = "length";
        }

        // 画像名を代入
        $file_name = $_FILES["input_img_name"]["name"];
        if (!empty($file_name)) {
            $file_type = substr($file_name, -3);
            // substr関数で拡張子の文字列取得
            $file_type = strtolower($file_type);
            // 大文字を小文字に変換
            if ($file_type != "jpg" && $file_type != "png" && $file_type != "gif") {
                $errors["img_name"] = "type";
            }
        }else{
            $errors["img_name"] = "blank";
        }

        // エラーがなかったときの処理
        if (empty($errors)) {
            $date_str = date("YmdHis");
            $submit_file_name = $date_str.$file_name;

            move_uploaded_file($_FILES["input_img_name"]["tmp_name"],"../user_profile_img/".$submit_file_name);
            // 画像アップロード

            $_SESSION["register"]["name"] = $_POST["input_name"];
            $_SESSION["register"]["email"] = $_POST["input_email"];
            $_SESSION["register"]["password"] = $_POST["input_password"];
            $_SESSION["register"]["img_name"] = $submit_file_name;

            header("Location: check.php");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>LearnSNS</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body style="margin-top: 60px">
  <div class="container">
    <div class="row">
      <div class="col-xs-8 col-xs-offset-2 thumbnail">
        <h2 class="text-center content_header">アカウント作成</h2>
        <form action="signup.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="input_name" class="form-control" id="name" placeholder="山田 太郎">
            </div>
          <?php if (isset($errors["name"]) && $errors["name"] == "blank"): ?>
            <p class="text-danger">ユーザー名を入力してください</p>
          <?php endif; ?>
          <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
            </div>
          <?php if (isset($errors["email"]) && $errors["email"] == "blank"): ?>
            <p class="text-danger">メールアドレスを入力してください</p>
          <?php endif; ?>
          <div class="form-group"> 
            <label for="password">パスワード</label> 
            <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード"> 
          <?php if (isset($errors["password"]) && $errors["password"] == "blank"): ?>
            <p class="text-danger">パスワードを入力してください</p>
          <?php endif; ?>
          <?php if (isset($errors["password"]) && $errors["password"] == "length"): ?>
            <p class="text-danger">パスワードは４〜１６文字で入力してください</p>
          <?php endif; ?>
          </div> 
          <div class="form-group"> 
            <label for="img_name">プロフィール画像</label> 
            <input type="file" name="input_img_name" id="img_name" accept="image/*"> 
          <?php if (isset($errors["img_name"]) && $errors["img_name"] == "blank"): ?>
            <p class="text-danger">画像を選択してください</p>
          <?php endif; ?>
          <?php if (isset($errors["img_name"]) && $errors["img_name"] == "type"): ?>
            <p class="text-danger">拡張子が「jpg」「png」「gif」の画像を選択してください</p>
          <?php endif; ?>
          </div>
          <input type="submit" class="btn btn-default" value="確認">
          <a href="../signin.php" style="float: right; padding-top: 6px;" class="text-success">サインイン</a>
        </form>
      </div>
    </div>
  </div>
  <script src="../assets/js/jquery-3.1.1.js"></script>
  <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="../assets/js/bootstrap.js"></script>
</body>
</html>