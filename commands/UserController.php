<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

class UserController extends Controller
{
    /**
     * Tambah user baru
     * 
     * Cara pakai:
     * php yii user/create admin admin admin
     * php yii user/create demo demo123 user
     * 
     * @param string $username
     * @param string $password
     * @param string $role
     */
    public function actionCreate($username, $password, $role = 'user')
    {
        $user = new User();
        $user->username = $username;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->role = $role;

        if ($user->save()) {
            echo "✅ User berhasil dibuat:\n";
            echo "   Username: {$username}\n";
            echo "   Role: {$role}\n";
        } else {
            echo "❌ Gagal membuat user:\n";
            print_r($user->errors);
        }
    }
}
