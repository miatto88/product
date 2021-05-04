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
    'SELECT * FROM items'
);

$itemData = $db->query(
    'SELECT * FROM storing LEFT JOIN shipping ON storing.item_code=shipping.item_code LEFT JOIN items ON storing.item_code=items.item_code'
);

$storings = $db->prepare(
    'SELECT * FROM storing WHERE item_code=?'
);

$shippings = $db->prepare(
    'SELECT * FROM shipping WHERE item_code=?'
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
            <span><?php echo "ログイン名： " . htmlspecialchars($member['name'], ENT_QUOTES); ?></span>
            <span><a href="logout.php">ログアウト</a></span>
        </div>
    </header>
    <div class="wrapper">
        <section class="main">
            <?php while ($item = $items->fetch()): ?>
                <?php 
                    $storings->execute(array($item["item_code"]));
                    $count = $storings->fetchall();
                    
                    $in_count = 0;
                    for ($i = 0; $i < count($count); $i++) {
                        $in_count += intval($count[$i]["in_count"]);
                    }
                    
                    $shippings->execute(array($item["item_code"]));
                    
                    $count = $shippings->fetchall();
                    
                    $out_count = 0;
                    for ($i = 0; $i < count($count); $i++) {
                        $out_count += intval($count[$i]["out_count"]);
                    }

                    $resultCount = $in_count - $out_count;
                ?>
                <div class="item_name">
                    <a href="itemview.php?id=<?php echo $item['id']; ?>"><?php echo $item['item_code'] ?></a>
                </div>
                <div class="item_property">
                    <span>在庫：<?php echo $resultCount; ?></span>
                    <span>リード：<?php echo $item['lead_time'] ?> 日</span>
                    <span>ロット：<?php echo $item['lot'] ?> </span>
                </div>
                <div class="button">
                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo $item['id'] ?>">
                        <input type="hidden" name="storing_count" value="">
                        <input type="hidden" name="storing_date" value="">
                        <input type="hidden" name="shipping_count" value="">
                        <input type="hidden" name="shipping_date" value="">
                        <button type="submit" formaction="storing.php">入庫</button>
                        <button type="submit" formaction="shipping.php">出庫</button>
                    </form>
                </div>
                <hr>
            <?php endwhile; ?>
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