<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_id
 * @property string $nama
 * @property string $alamat
 * @property string $metode_pembayaran
 * @property float $total
 * @property string $created_at
 * @property string $updated_at
 *
 * @property OrderItem[] $items
 */
class Order extends ActiveRecord
{
    public static function tableName()
    {
        return 'orders';
    }

    public function rules()
    {
        return [
            [['user_id', 'nama', 'no_hp', 'alamat', 'metode_pembayaran', 'total'], 'required'],
            [['user_id'], 'integer'],
            [['alamat'], 'string'],
            [['total'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['nama'], 'string', 'max' => 255],
            [['no_hp'], 'string', 'max' => 20],
            [['metode_pembayaran'], 'in', 'range' => ['COD', 'Transfer Bank']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'nama' => 'Nama Lengkap',
            'no_hp' => 'No HP',
            'alamat' => 'Alamat',
            'metode_pembayaran' => 'Metode Pembayaran',
            'total' => 'Total',
            'created_at' => 'Dibuat',
            'updated_at' => 'Diupdate',
        ];
    }


    public function getItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }
}
