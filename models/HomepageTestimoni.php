<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class HomepageTestimoni extends ActiveRecord
{
    public static function tableName()
    {
        return 'homepage_testimoni';
    }

    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
            [['author'], 'string', 'max' => 255],
        ];
    }
}
