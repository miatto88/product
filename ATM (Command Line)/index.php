<?php
require_once('validation/MoneyValidation.php');
require_once('validation/MenuValidation.php');

class User {
    // staticで宣言
    public static $user_list = array(
        1 => array(
            "id" => "1",
            "password" => "1111",
            "name" => "tanaka",
            "balance" => "500000"
        ),
        2 => array(
            "id" => "2",
            "password" => "3456",
            "name" => "suzuki",
            "balance" => "1000000"
        )
    ); 

    // 入力されたIDが存在する場合、trueを返すクラスメソッド
    public static function isExistById($input) {
        if (array_key_exists($input, self::$user_list)) {
            return true;
        }

        return false;
    }

    // 渡された$keyに該当するユーザーデータの連想配列を返すクラスメソッド 
    public static function getUserById($key) {
        return self::$user_list[$key];
    }
}

class Atm {
    
    // 処理の選択肢をクラス定数で宣言
    const SHOW = '1';
    const RECEIVE = '2';
    const DEPOSIT = '3';
    const END = '0';
    
    // 処理の選択肢を表示するクラス定数
    const CHOICE = '1:残高照会  2:入金処理  3:出金処理  0:終了する' . PHP_EOL;
    
    // 入力値をチェックする為の配列をクラス定数で宣言
    const INPUTEXISTS = ['1', '2', '3', '0'];
    
    
    // 口座残高を$creditとしてprivateで宣言　初期値100,000円
    private $credit = 100000;
    
    // 追加
    public $user;
    
    //  コンストラクタ追加
    public function __construct() {
        //ログイン
        $this->login();
    }

    // ログイン処理のメソッド追加
    public function login() {
        //id入力
        echo 'ログインIDを入力してください' . PHP_EOL;
        $input = trim(fgets(STDIN));
        
        // isExistBtId()を利用
        if (!User::isExistById($input)) { // IDが存在しない場合に再帰させる処理
            //なければエラー、再帰関数
            echo 'ログインIDが存在しません' . PHP_EOL;
            return $this->login();
        }

        // getUserById()を利用
        $userData = User::getUserById($input);

        //パスワード取得
        $userPassword = $userData['password'];
        echo 'ログインパスワードを入力してください' . PHP_EOL;
        $input = trim(fgets(STDIN));

        //取得したユーザーのパスワードと入力値が一致するかチェック
        if ($input !== $userPassword) {
            //なければエラー、再帰関数
            echo 'ログインパスワードが存在しません' . PHP_EOL;
            return $this->login();
        }

        //問題なければ、プロパティの$userにセット
        $this->user = $userData;
    }

    // 処理を実行する関数
    public function start() {
        // 変更 Menu用バリデーションをインスタンス化
        $menuvalid = new MenuValidation();

        echo '実行する処理を選択してください。' . PHP_EOL;
        echo self::CHOICE;
    
        $input = $this->input();
    
        // 入力値をチェック
        if ($menuvalid->check($input) === false) {
            echo $menuvalid->getErrorMessages('menu') . PHP_EOL;
            $this->start();
        }
        
        // 入力値毎の処理をswitch文で分岐
        switch ($input) {
            case self::END:
                echo 'プログラムを終了します。' . PHP_EOL;
                exit;

            case self::SHOW:
                $this->showCredit();
                break;
        
            case self::RECEIVE:
                $this->receiveMoney();
                break;
                    
            case self::DEPOSIT:
                $this->depositMoney();
                break;

        }
        
        // 続けて処理を選択 再帰処理を start() 内へ移動
        return $this->start();
    }
    
    // 標準入力
    public function input() {
        $input = trim(fgets(STDIN));
        
        return $input;
    }

    // 口座残高を出力する関数
    public function showCredit() {
        echo 'あなたの口座残高は ' . $this->credit . ' 円です。' . PHP_EOL;
    }
    
    // 入金処理の関数
    public function receiveMoney() {
        // 変更 Money用バリデーションをインスタンス化
        $moneyvalid = new MoneyValidation();

        echo '入金金額を入力してください。' . PHP_EOL;
    
        $money = $this->input();
        if ($moneyvalid->check($money) === false) {
            // バリデーションfalseの時の処理
            echo $moneyvalid->getErrorMessages('money') . PHP_EOL;
            return $this->receiveMoney();
        }
    
        $this->credit += intval($money);
        echo $money  . ' 円を入金しました。' . PHP_EOL;
    }
    
    // 出金処理の関数
    public function depositMoney() {
        // 変更 Money用バリデーションをインスタンス化
        $moneyvalid = new MoneyValidation();

        echo '出金金額を入力してください。' . PHP_EOL;
    
        $money = $this->input();
        if ($moneyvalid->check($money) === false) {
            // バリデーションfalseの時の処理
            echo $moneyvalid->getErrorMessages('money') . PHP_EOL;
            return $this->depositMoney();
        }
    
        // 金額がマイナスになる時の処理
        if ($this->credit - intval($money) < 0) {
            echo '残高が不足している為、処理を中止しました。' . PHP_EOL;
            return $this->start();
        }
        
        $this->credit -= intval($money);;
        echo $money  . ' 円を出金しました。' . PHP_EOL;
    }
}

$atm = new Atm();
$atm->start();

?>