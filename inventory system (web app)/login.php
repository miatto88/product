<?php
session_start();
require_once('dbconnect.php');

if (empty($_POST === 0)) {
    $error['login'] = "";
}

if (!empty($_POST)) {
    if ($_POST['member_id'] !== '' && $_POST['password'] !== '') {
        $login = $db->prepare('SELECT * FROM members WHERE member_id=? AND password=?');
        $login->execute([$_POST['member_id'], sha1($_POST['password'])]);
        $member = $login->fetch();

        if ($member) {
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();

            header('Location: index.php');
            exit;
        } else {
            $error['login'] = 'failed';
        }
    } else {
        $error['login'] = 'blank';
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    
    <title>在庫管理システム</title>
</head>
<body>
    <!-- <header> -->
        <h1 class="login-title">在庫管理システム</h1>
    <!-- </header> -->
    <div class="login-wrapper">
        <form class="login-form" action="" method="post">
            <div class="form-group">
                <input type="text" name="member_id" maxlength="100" placeholder="アカウント名" class="form-control" value='1111'>
            </div>
            <div class="form-group">
                <input type="password" name="password" maxlength="100" placeholder="パスワード" class="form-control" value='1111'>
            </div>
            <div class="form-group">
                <input class="btn btn-outline-primary my-1" type="submit" class="form-control" value="ログイン">
                <?php if ($error['login'] === 'blank'): ?>
                    <p class="error">* メールアドレスとパスワードをご記入ください</p>
                <?php endif; ?>
                <?php if ($error['login'] === 'failed'): ?>
                    <p class="error">* ログインに失敗しました。正しくご記入ください</p>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>
</html>