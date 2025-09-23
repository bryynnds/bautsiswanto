<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order_items".
 *
 * @property int $id
 * @property int $order_id
 * @property int $produk_id
 * @property int $qty
 * @property float $harga
 * @property float $subtotal
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Order $order
 * @property HomepageProduk $produk
 */
class OrderItem extends ActiveRecord
{
    public static function tableName()
    {
        return 'order_items';
    }

    public function rules()
    {
        return [
            [['order_id', 'produk_id', 'qty', 'harga', 'subtotal'], 'required'],
            [['order_id', 'produk_id', 'qty'], 'integer'],
            [['harga', 'subtotal'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'produk_id' => 'Produk ID',
            'qty' => 'Jumlah',
            'harga' => 'Harga',
            'subtotal' => 'Subtotal',
            'created_at' => 'Dibuat',
            'updated_at' => 'Diupdate',
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function getProduk()
    {
        return $this->hasOne(HomepageProduk::class, ['id' => 'produk_id']);
    }
}
