<?php
require_once('dbconnect.php');

// $test = $db->query('SELECT * FROM items');
// while ($record = $test->fetch()) {
//     echo $record['item_code'] . PHP_EOL;
// }

if ($_POST['member_id'] !== '' && $_POST['password'] !== '') {
    if (!empty($_POST)) {
        $login = $db->prepare('SELECT * FROM members WHERE member_id=? AND password=?');
        $login->execute([$_POST['member_id'], $_POST['password']]);
        $member = $login->fetch();

        if ($member) {
            echo 'ログイン成功';
        }
    }
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="#">
    
    <title>在庫管理システム</title>
</head>
<body>
    <header>
        <h1>在庫管理システム</h1>
    </header>
    <div class="wrapper">
        <form action="" method="post">
            <div>
                <label for="login">アカウントID : <input type="text" name="member_id" maxlength="100" value=''></label>
                <label for="login">パスワード : <input type="password" name="password" maxlength="100" value=''></label>
            </div>
            <div>
                <input type="submit" value="ログイン">
            </div>
        </form>
    </div>
</body>
</html>