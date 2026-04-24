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
            [['title', 'brand_name', 'description', 'harga', 'stok', 'link'], 'required'],
            [['description'], 'string'],
            [['harga', 'stok'], 'integer', 'min' => 0],
            [['link'], 'url'],
            [['title', 'brand_name'], 'string', 'max' => 255],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 2 * 1024 * 1024],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Nama Produk',
            'description' => 'Deskripsi',
            'image' => 'Gambar Produk',
            'harga' => 'Harga',
            'stok' => 'Jumlah Stok',
            'link' => 'Link Produk',
            'brand_name' => 'Merek',
        ];
    }
}
