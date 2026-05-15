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
            [['title', 'jenis', 'description', 'harga', 'jumlah', 'satuan'], 'required'],
            [['description'], 'string'],
            [['harga'], 'integer', 'min' => 0],
            [['jumlah'], 'integer', 'min' => 0],
            [['title', 'jenis', 'satuan'], 'string', 'max' => 255],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Nama Produk',
            'jenis' => 'Jenis Produk',
            'description' => 'Deskripsi',
            'harga' => 'Harga',
            'satuan' => 'Satuan',
            'image' => 'Gambar Produk',
        ];
    }
}