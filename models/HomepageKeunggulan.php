<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class HomepageKeunggulan extends ActiveRecord
{
    public static function tableName()
    {
        return 'homepage_keunggulan';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['subtitle'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 255],
        ];
    }
}
