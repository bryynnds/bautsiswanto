<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Notification extends ActiveRecord
{
    public static function tableName()
    {
        return 'notifications';
    }

    public function rules()
    {
        return [
            [['user_id', 'title', 'message'], 'required'],

            [['user_id', 'is_read'], 'integer'],

            [['message'], 'string'],

            [['title'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Judul',
            'message' => 'Pesan',
            'is_read' => 'Sudah Dibaca',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}