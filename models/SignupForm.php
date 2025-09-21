<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $password;
    public $confirmPassword;

    public function rules()
    {
        return [
            [['username', 'password', 'confirmPassword'], 'required'],
            ['username', 'string', 'min' => 4, 'max' => 50],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Username sudah dipakai.'],
            ['password', 'string', 'min' => 6],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Password tidak cocok.'],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->role = 'user';

        return $user->save() ? $user : null;
    }
}
