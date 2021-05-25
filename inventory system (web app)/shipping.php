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

if(!empty($_POST)) {
    if ($_POST["shipping_count"] !== '' && $_POST["shipping_date"] !== '') {
        $shipping = $db->prepare("INSERT INTO shipping SET item_code=?, out_count=?, out_date=?");
        $shipping->execute([
            $_POST["item_code"],
            $_POST["shipping_count"],
            $_POST["shipping_date"]
        ]);
        
        header("Location: shipping.php");
    }
}

$items = $db->query(
    "SELECT * FROM items"
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
        <h1>在庫管理システム</h1>
        <div class="header-info">
            <span><?php echo "ログイン名： " . h($member['name']); ?></span>
            <span><a href="logout.php">ログアウト</a></span>
        </div>
    </header>
    <div class="wrapper">
        <section class="main">
            <form class="storage" action="" method="post">
                <div>
                    製品名：<select name="item_code">
                        <?php while ($item = $items->fetch()): ?>
                            <?php if ($_POST["id"] === $item["id"]): ?>
                                <option value="<?php echo h($item["item_code"]) ?>" selected><?php echo h($item["item_code"]) ?></option>
                            <?php else: ?>
                                <option value="<?php echo h($item["item_code"]) ?>"><?php echo h($item["item_code"]) ?></option>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    出庫数：<input type="number" name="shipping_count">
                </div> 
                <div>
                    出庫日：<input type="date" name="shipping_date">
                </div>
                <input type="submit" value="送信">
            </form>
        </section>
        <section class="side">
            <span class="side_menu"><a href="index.php">製品一覧</a></span>
            <span class="side_menu"><a href="storing.php">入庫処理</a></span>
            <span class="side_menu active"><a href="shipping.php">出庫処理</a></span>
            <span class="side_menu"><a href="customerlist.php">顧客マスタ</a></span>
            <span class="side_menu"><a href="join.php">社員登録</a></span>
        </section>
    </div>
</body>
</html>