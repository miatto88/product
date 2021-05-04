<?php
session_start();
require_once('dbconnect.php');

if (empty($_POST === 0)) {
    $error['login'] = "";
}

if (!empty($_POST)) {
    if ($_POST['member_id'] !== '' && $_POST['password'] !== '') {
        $login = $db->prepare('SELECT * FROM members WHERE member_id=? AND password=?');
        $login->execute([$_POST['member_id'], $_POST['password']]);
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

    <link rel="stylesheet" href="style.css">
    
    <title>在庫管理システム</title>
</head>
<body>
    <header>
        <h1>在庫管理システム</h1>
    </header>
    <div class="wrapper">
        <form class="login-form" action="" method="post">
            <div>
                <label for="login">アカウント名　:　<input type="text" name="member_id" maxlength="100" value=''></label>
            </div>
            <div>
                <label for="login">パスワード　　:　<input type="password" name="password" maxlength="100" value=''></label>
            </div>
            <div>
                <input type="submit" value="ログイン">
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