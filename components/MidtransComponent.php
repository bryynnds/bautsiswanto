<?php
namespace app\components;

use yii\base\Component;

class MidtransComponent extends Component
{
    public $serverKey;
    public $clientKey;
    public $isProduction;

    public function init()
    {
        parent::init();
        \Midtrans\Config::$serverKey = $this->serverKey;
        \Midtrans\Config::$isProduction = $this->isProduction;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }
}
