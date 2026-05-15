<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class JenisProduk extends ActiveRecord
{
    public static function tableName()
    {
        return 'jenis_produk';
    }

    public function rules()
    {
        return [
            [['nama_jenis'], 'required'],

            [['nama_jenis'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nama_jenis' => 'Nama Jenis',
        ];
    }

    public function getKategoriProduks()
    {
        return $this->hasMany(KategoriProduk::class, ['jenis_id' => 'id']);
    }
}