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
            [
                [
                    'title',
                    'kategori_id',
                    'description',
                    'harga_kg',
                    'harga_bijian'
                ],
                'required'
            ],

            [['description'], 'string'],

            [['kategori_id'], 'integer'],

            [['harga_kg', 'harga_bijian'], 'integer', 'min' => 0],

            [['title'], 'string', 'max' => 255],

            [['image'], 'file', 'extensions' => 'png, jpg, jpeg'],

            [
                ['kategori_id'],
                'exist',
                'targetClass' => KategoriProduk::class,
                'targetAttribute' => 'id'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Nama Produk',
            'kategori_id' => 'Kategori Produk',
            'description' => 'Deskripsi',
            'harga_kg' => 'Harga Kiloan',
            'harga_bijian' => 'Harga Eceran',
            'image' => 'Gambar Produk',
        ];
    }

    public function getKategori()
    {
        return $this->hasOne(KategoriProduk::class, ['id' => 'kategori_id']);
    }
}