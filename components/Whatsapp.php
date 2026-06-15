<?php

namespace app\components;

use Yii;
use yii\base\Component;

class Whatsapp extends Component
{
    public $token;

    public function send($target, $message)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target' => $target,
                'message' => $message,
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->token
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}