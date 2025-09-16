<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class HomepageProduk extends ActiveRecord
{
    public static function tableName()
    {
        return 'homepage_produk';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'title' => 'Nama Produk',
            'description' => 'Deskripsi',
            'image' => 'Gambar Produk',
        ];
    }
}
