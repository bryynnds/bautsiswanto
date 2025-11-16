<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }

    // di models/User.php
    public $old_password;
    public $new_password;


    public function rules()
    {
        return [
            [['username'], 'required'],

            // password lama wajib jika password baru diisi
            ['old_password', 'required', 'when' => function ($model) {
                return !empty($model->new_password);
            }, 'whenClient' => "function(){ return $('#user-new_password').val() !== ''; }"],

            // Validasi password lama
            ['old_password', 'validateOldPassword'],

            // password baru minimal 6 karakter
            ['new_password', 'string', 'min' => 6],
        ];
    }

    public function validateOldPassword($attribute, $params)
    {
        if (!empty($this->new_password)) {
            if (!\Yii::$app->security->validatePassword($this->old_password, $this->getOldAttribute('password_hash'))) {
                $this->addError($attribute, 'Password lama salah.');
            }
        }
    }




    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // belum dipakai
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key ?? null;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getRole()
    {
        return $this->role ?? 'user';
    }

    public function isAdmin()
    {
        return $this->getRole() === 'admin';
    }
}
