<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\JenisProduk;

class ProductRequest extends ActiveRecord
{
    public static function tableName()
    {
        return 'product_requests';
    }

    const STATUS_PENDING = 'pending';
    const STATUS_DIPROSES = 'diproses';
    const STATUS_TERSEDIA = 'tersedia';
    const STATUS_TIDAK_DITEMUKAN = 'tidak_ditemukan';

    public function rules()
    {
        return [
            [['user_id', 'nama_produk', 'jenis_produk_id'], 'required'],

            [['user_id', 'jenis_produk_id'], 'integer'],

            [['keterangan', 'admin_note'], 'string'],

            [['nama_produk', 'foto'], 'string', 'max' => 255],

            [['material'], 'string', 'max' => 100],

            [['status'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nama_produk' => 'Nama Produk',
            'jenis_produk_id' => 'Jenis Produk',
            'material' => 'Material',
            'keterangan' => 'Keterangan',
            'foto' => 'Foto',
            'status' => 'Status',
            'admin_note' => 'Catatan Admin',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getJenisProduk()
    {
        return $this->hasOne(JenisProduk::class, ['id' => 'jenis_produk_id']);
    }
}