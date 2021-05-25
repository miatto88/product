<?php

// 変更 各バリデーションの基になるBaseクラスを作成
class BaseValidation {
    // 変更 エラーメッセージを変数に配列で格納
    private $errors = [];

    // 変更 セッター追加
    public function setErrorMessages($errorType,$errorMessage) {
        $this->errors[$errorType] = $errorMessage;
    }

    // 変更 ゲッター追加
    public function getErrorMessages($errorType) {
        return $this->errors[$errorType];
    }
}

?>