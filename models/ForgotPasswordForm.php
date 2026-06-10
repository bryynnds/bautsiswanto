<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ForgotPasswordForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
        ];
    }

    public function sendResetEmail()
    {
        $user = User::findOne(['email' => $this->email]);

        if (!$user) {
            return false;
        }

        $user->reset_token =
            Yii::$app->security->generateRandomString(64);

        $user->save(false);

        $link = Yii::$app->urlManager->createAbsoluteUrl([
            'site/reset-password',
            'token' => $user->reset_token,
        ]);

        return Yii::$app->mailer
            ->compose()
            ->setTo($user->email)
            ->setSubject('Reset Password')
            ->setHtmlBody("
            <h2>Reset Password</h2>

            <p>Halo {$user->username},</p>

            <p>Salin link berikut ke browser:</p>

            <p>{$link}</p>
        ")
            ->send();
    }
}