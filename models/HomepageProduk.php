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
            [['title', 'jenis', 'description', 'harga_kg', 'harga_bijian'], 'required'],
            [['description'], 'string'],
            [['harga_kg', 'harga_bijian'], 'integer', 'min' => 0],
            [['title', 'jenis'], 'string', 'max' => 255],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Nama Produk',
            'jenis' => 'Jenis Produk',
            'description' => 'Deskripsi',
            'harga_kg' => 'Harga Kiloan',
            'harga_bijian' => 'Harga Eceran',
            'image' => 'Gambar Produk',
        ];
    }
}