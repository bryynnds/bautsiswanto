<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Keranjang extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%keranjang}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'produk_id'], 'required'],

            [['user_id', 'produk_id', 'jumlah'], 'integer'],

            [['satuan'], 'string'],

            [['satuan'], 'in', 'range' => ['kg', 'bijian']],
        ];
    }

    public function getProduk()
    {
        return $this->hasOne(HomepageProduk::class, ['id' => 'produk_id']);
    }
}