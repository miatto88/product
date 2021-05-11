<?php
session_start();
require_once('dbconnect.php');
require_once('functions.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute([$_SESSION['id']]);
    $member = $members->fetch();
} else {
    header('Location: login.php');
    exit();
}


if (empty($_GET)) {
    $_GET['error'] = "";
}

if(!empty($_POST)) {
    $members_id = $db->prepare('SELECT * FROM members WHERE member_id=?');
    $members_id->execute(array($_POST["member_id"]));
    $member_id = $members_id->fetch();

    if ($member_id > 0) {
        header("Location: join.php?error=1");
        exit();
    } elseif ($_POST["member_id"] !== '' && $_POST["member_pass"] !== '') {
        $join_member = $db->prepare("INSERT INTO members SET member_id=?, password=?, name=?");
        $join_member->execute([
            $_POST["member_id"],
            sha1($_POST["member_pass"]),
            $_POST["member_name1"] . " " . $_POST["member_name2"]
        ]);
        
        header("Location: index.php");
        exit();
        $join_Message = $_POST["member_name1"] . $_POST["member_name2"] . "さんを登録しました";
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
        <div class="header-info">
            <span><?php echo "ログイン名： " . h($member['name']); ?></span>
            <span><a href="logout.php">ログアウト</a></span>
        </div>
    </header>
    <div class="wrapper">
        <section class="main">
            <form class="join-form" action="" method="post">
                <div>
                    社員番号　　　<input type="number" name="member_id">
                </div>
                <div>   
                    パスワード　　<input type="text" name="member_pass">
                </div>
                <div>   
                    氏名（姓）　　<input type="text" name="member_name1">
                </div>
                <div>
                    氏名（名）　　<input type="text" name="member_name2">
                </div>
                <input type="submit" value="登録">
            </form>
            <?php if ($_GET['error'] === "1"): ?>
                <p class="error">* 指定の社員番号は既に登録されています</p>
            <?php endif; ?>
        </section>
        <section class="side">
            <span class="side_menu"><a href="index.php">製品一覧</a></span>
            <span class="side_menu"><a href="storing.php">入庫処理</a></span>
            <span class="side_menu"><a href="shipping.php">出庫処理</a></span>
            <span class="side_menu"><a href="customerlist.php">顧客マスタ</a></span>
            <span class="side_menu"><a href="join.php">社員登録</a></span>
        </section>
    </div>
</body>
</html>