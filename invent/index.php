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

$items = $db->query(
    'SELECT * FROM storing LEFT JOIN shipping ON storing.item_code=shipping.item_code LEFT JOIN items ON storing.item_code=items.item_code'
);

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
            <?php while ($item = $itemData->fetch()): ?>
                <div class="item_name">
                    <a href="itemview.php?id=<?php echo $item['id']; ?>"><?php echo $item['item_code'] ?></a>
                </div>
                <span class="item_property">在庫：<?php echo $item['in_count'] - $item['out_count'] ?></span>
                <span class="item_property">リード：<?php echo $item['lead_time'] ?> 日</span>
                <span class="item_property">ロット：<?php echo $item['lot'] ?> </span>
                <div class="button">
                    <form method="post">
                        <input type="hidden" name="item_code" value="<?php echo $item['item_code'] ?>">
                        <button type="submit" formaction="storing.php">入庫</button>
                        <button type="submit" formaction="shipping.php">出庫</button>
                    </form>
                </div>
                <hr>
            <?php endwhile; ?>
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