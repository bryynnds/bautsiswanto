<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $password;
    public $confirmPassword;

    public $email;

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'confirmPassword'], 'required'],

            ['username', 'string', 'min' => 4, 'max' => 50],
            [
                'username',
                'unique',
                'targetClass' => User::class,
                'message' => 'Username sudah dipakai.'
            ],

            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => User::class,
                'message' => 'Email sudah terdaftar.'
            ],

            ['password', 'string', 'min' => 6],

            [
                'confirmPassword',
                'compare',
                'compareAttribute' => 'password',
                'message' => 'Password tidak cocok.'
            ],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();

        $user->username = $this->username;
        $user->email = $this->email;

        $user->setPassword($this->password);
        $user->generateAuthKey();

        $user->role = 'user';

        $user->is_verified = 0;

        $user->verification_token =
            Yii::$app->security->generateRandomString(64);

        if ($user->save()) {

            $this->sendVerificationEmail($user);

            return $user;
        }

        return null;
    }

    protected function sendVerificationEmail($user)
    {
        $link = Yii::$app->urlManager->createAbsoluteUrl([
            'site/verify-email',
            'token' => $user->verification_token,
        ]);

        return Yii::$app->mailer
            ->compose()
            ->setTo($user->email)
            ->setSubject('Verifikasi Akun')
            ->setHtmlBody("
            <h2>Verifikasi Akun</h2>

            <p>Halo {$user->username},</p>

            <p>Salin link berikut ke browser:</p>

            <p>{$link}</p>
            ")
            ->send();
    }
}
