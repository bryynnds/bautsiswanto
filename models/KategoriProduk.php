<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class KategoriProduk extends ActiveRecord
{
    public static function tableName()
    {
        return 'kategori_produk';
    }

    public function rules()
    {
        return [
            [['jenis_id', 'nama_kategori'], 'required'],

            [['jenis_id'], 'integer'],

            [['nama_kategori'], 'string', 'max' => 255],

            [
                ['jenis_id'],
                'exist',
                'targetClass' => JenisProduk::class,
                'targetAttribute' => 'id'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'jenis_id' => 'Jenis Produk',
            'nama_kategori' => 'Nama Kategori',
        ];
    }

    public function getJenis()
    {
        return $this->hasOne(JenisProduk::class, ['id' => 'jenis_id']);
    }

    public function getProduks()
    {
        return $this->hasMany(HomepageProduk::class, ['kategori_id' => 'id']);
    }
}