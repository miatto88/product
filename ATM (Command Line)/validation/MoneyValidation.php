<?php
require_once('validation/BaseValidation.php');


class MoneyValidation extends BaseValidation {
    // 入出金の金額入力値をチェックするクラスメソッド
    public function check($money) {
        if (strcmp($money, intval($money)) !== 0) {
            $this->setErrorMessages("money", "金額は 0 以上の整数で入力してください。");
            return false;
        }
        
        return true;
    }

}

?>