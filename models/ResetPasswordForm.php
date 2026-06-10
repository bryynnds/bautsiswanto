<?php

namespace app\models;

use yii\base\Model;

class ResetPasswordForm extends Model
{
    public $password;
    public $confirmPassword;

    public function rules()
    {
        return [
            [['password', 'confirmPassword'], 'required'],

            ['password', 'string', 'min' => 6],

            [
                'confirmPassword',
                'compare',
                'compareAttribute' => 'password',
                'message' => 'Password tidak cocok.'
            ],
        ];
    }
}