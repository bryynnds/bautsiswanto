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
            [['title', 'subtitle'], 'required'],
            [['subtitle'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 255],
            [['background_image'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Judul Slogan',
            'subtitle' => 'Deskripsi',
            'background_image' => 'Gambar Latar Belakang',
        ];
    }
}
