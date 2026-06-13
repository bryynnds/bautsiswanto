<?php

namespace app\models;

use yii\db\ActiveRecord;

class HomepageProdukTerlaris extends ActiveRecord
{
    public static function tableName()
    {
        return 'homepage_produk_terlaris';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['jumlah_tampil'], 'integer', 'min' => 1],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Judul Section',
            'jumlah_tampil' => 'Jumlah Produk Ditampilkan',
        ];
    }
}