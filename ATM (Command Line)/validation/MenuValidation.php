<?php
require_once('validation/BaseValidation.php');
require_once('index.php');  // Atm::INPUTEXISTSを使用する為の読み込み

class MenuValidation extends BaseValidation {
    // 処理選択の入力値をチェックするクラスメソッド
    public function check($input) {
        if (array_key_exists($input, Atm::INPUTEXISTS)) {
            return true;
        }
        
        $this->setErrorMessages("menu", "0～3の数字で入力してください。");
        return false;
    }

}

?>