<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class HomepageHero extends ActiveRecord
{
    public static function tableName()
    {
        return 'homepage_hero';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['subtitle'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 255],
            [['background_image'], 'string', 'max' => 255],
        ];
    }
}
