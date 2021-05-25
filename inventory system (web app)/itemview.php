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


$itemData = $db->prepare(
    'SELECT * FROM items WHERE items.id=?'
);

$itemData->execute(array($_REQUEST['id']));
$item = $itemData->fetch();


$storings = $db->prepare(
    'SELECT * FROM storing WHERE item_code=?'
);
$storings->execute(array($item["item_code"]));

$count = $storings->fetchall();

$in_count = 0;
for ($i = 0; $i < count($count); $i++) {
    $in_count += intval($count[$i]["in_count"]);
}

// もう一度使う為、再度execute
$storings->execute(array($item["item_code"]));


$shippings = $db->prepare(
    'SELECT * FROM shipping WHERE item_code=?'
);
$shippings->execute(array($item["item_code"]));

$count = $shippings->fetchall();

$out_count = 0;
for ($i = 0; $i < count($count); $i++) {
    $out_count += intval($count[$i]["out_count"]);
}

// もう一度使う為、再度execute
$shippings->execute(array($item["item_code"]));

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
            <div class="item_profile">
                <div>
                    <?php echo "製品コード　　　：　" . h($item["item_code"]); ?>
                </div>
                <div>
                    <?php echo "製品説明　　　　：　" . h($item["item_name"]); ?>
                </div>
                <div>
                    <?php echo "設定価格　　　　：　" . h($item["price"]); ?>
                </div>
                <div>
                    <?php echo "製品寸法（横）　：　" . h($item["size_w"]) . " mm"; ?>
                </div>
                <div>
                    <?php echo "製品寸法（縦）　：　" . h($item["size_h"]) . " mm"; ?>
                </div>
                <div>
                    <?php echo "ロット数　　　　：　" . h($item["lot"]); ?>
                </div>
                <div>
                    <?php echo "リードタイム　　：　" . h($item["lead_time"]) . " 日"; ?>
                </div>
                <div>
                    <?php echo "在庫　　　　　　：　" . $in_count - $out_count; ?>
                </div>
            </div>
            <div class="count_history">
                <div>
                    <p>入庫履歴 (日付 / 数量)</p>
                    <?php while ($storing = $storings->fetch()): ?>
                        <div>
                            <span><?php echo h($storing["in_date"]) . "　/　"; ?></span>
                            <span><?php echo h($storing["in_count"]); ?></span>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div>
                    <p>出庫履歴 (日付 / 数量)</p>
                    <?php while ($shipping = $shippings->fetch()): ?>
                        <div>
                            <span><?php echo h($shipping["out_date"]) . "　/　"; ?></span>
                            <span><?php echo h($shipping["out_count"]); ?></span>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <section class="side">
            <span class="side_menu active"><a href="index.php">製品一覧</a></span>
            <span class="side_menu"><a href="storing.php">入庫処理</a></span>
            <span class="side_menu"><a href="shipping.php">出庫処理</a></span>
            <span class="side_menu"><a href="customerlist.php">顧客マスタ</a></span>
            <span class="side_menu"><a href="join.php">社員登録</a></span>
        </section>
    </div>
</body>
</html>