<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class HashController extends Controller
{
    /**
     * Generate password hash
     * 
     * Cara pakai:
     * php yii hash/generate admin
     */
    public function actionGenerate($password)
    {
        $hash = Yii::$app->security->generatePasswordHash($password);
        echo "Password: $password\n";
        echo "Hash: $hash\n";
    }
}
