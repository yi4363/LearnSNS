<?php
    session_start();

    echo $_SESSION["register"]["name"] . "<br>";
    echo $_SESSION["register"]["email"] . "<br>";
    echo $_SESSION["register"]["password"] . "<br>";
    echo $_SESSION["register"]["img_name"] . "<br>";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title></title>
</head>
<body>
  <img src="../user_profile_img/<?php echo $_SESSION["register"]["img_name"]; ?>" width="60">
</body>
</html>