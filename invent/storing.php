<?php
session_start();
require_once('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute([$_SESSION['id']]);
    $member = $members->fetch();
} else {
    header('Location: login.php');
    exit();
}

$itemData = $db->query(
    'SELECT * FROM storing LEFT JOIN shipping ON storing.item_code=shipping.item_code LEFT JOIN items ON storing.item_code=items.item_code'
);

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
        <div>
            <h1>在庫管理システム</h1>
            <div class="header-info">
                <?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?>
            </div>
        </div>
    </header>
    <div class="wrapper">
        <section class="main">
            <div>
                <form action="">
                    <div>
                        製品名<input type="text">
                        数量<input type="text">
                        
                    </div>
                </form>
            </div>
            <p>main</p>
            <p>main</p>

        </section>
        <section class="side">
            <span class="side_menu"><a href="index.php">製品一覧</a></span>
            <span class="side_menu"><a href="storing.php">入庫処理</a></span>
            <span class="side_menu"><a href="shipping.php">出庫処理</a></span>
            <span class="side_menu"><a href="customerlist.php">顧客マスタ</a></span>
        </section>
    </div>
</body>
</html>