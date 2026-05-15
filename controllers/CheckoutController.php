<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Order;
use app\models\OrderItem;
use app\models\Keranjang;
use Midtrans\Snap;
use Midtrans\Config;

class CheckoutController extends Controller
{
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $keranjang = Keranjang::find()->where(['user_id' => $userId])->all();

        $total = 0;
        foreach ($keranjang as $item) {
            $harga = $item->satuan == 'kg'
                ? $item->produk->harga_kg
                : $item->produk->harga_bijian;

            $total += $harga * $item->jumlah;
        }

        return $this->render('checkout', [
            'keranjang' => $keranjang,
            'total' => $total,
        ]);
    }

    public function actionPaid($order_id)
    {
        $order = Order::findOne($order_id);
        if (!$order) {
            throw new \yii\web\NotFoundHttpException("Order not found");
        }

        $order->status = 'paid';
        $order->save(false);

        return json_encode(['success' => true]);
    }


    public function actionProcess()
    {
        Config::$serverKey = 'SB-Mid-server-IWLFN5qdGmuxRk-QkCSoaTot';
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $post = Yii::$app->request->post();
        $userId = Yii::$app->user->id;

        $keranjang = Keranjang::find()
            ->where(['user_id' => $userId])
            ->all();

        // 1. Simpan ke tabel orders
        $order = new Order();
        $order->user_id = $userId;
        $order->nama = $post['nama'];
        $order->no_hp = $post['no_hp'];
        $order->alamat = $post['alamat'];
        $order->metode_pembayaran = $post['metode_pembayaran'];
        $total = 0;

        foreach ($keranjang as $item) {

            $harga = $item->satuan == 'kg'
                ? $item->produk->harga_kg
                : $item->produk->harga_bijian;

            $total += $harga * $item->jumlah;
        }

        $order->total = $total;
        $order->status = ($post['metode_pembayaran'] == 'COD') ? 'COD' : 'Pending';

        if (!$order->save()) {
            return "Gagal menyimpan order";
        }

        // 2. Ambil keranjang user
        $keranjang = Keranjang::find()->where(['user_id' => $userId])->all();

        foreach ($keranjang as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->produk_id = $item->produk_id;
            $orderItem->qty = $item->jumlah;
            $orderItem->satuan = $item->satuan;
            $harga = $item->satuan == 'kg'
                ? $item->produk->harga_kg
                : $item->produk->harga_bijian;

            $orderItem->harga = $harga;
            $orderItem->subtotal = $harga * $item->jumlah;
            $orderItem->save();
        }

        // Jika COD → selesai
        if ($order->metode_pembayaran == 'COD') {
            Keranjang::deleteAll(['user_id' => $userId]);
            return $this->redirect(['/site/index']);
        }

        // 3. Jika Transfer Bank → Generate Snap Token
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $order->id,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->nama,
                'phone' => $order->no_hp,
            ],
        ];

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $order->id . '-' . time(),
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->nama,
                'phone' => $order->no_hp,
                'billing_address' => [
                    'address' => $order->alamat,
                ],
            ],
            'enabled_payments' => ['bank_transfer'],
        ];

        $snapToken = Snap::getSnapToken($params);

        $order->midtrans_order_id = $params['transaction_details']['order_id'];
        $order->save(false);

        Keranjang::deleteAll(['user_id' => $userId]);

        return $this->render('snap', [
            'snapToken' => $snapToken,
            'clientKey' => 'SB-Mid-client-b6veAVTs1n2MqX-T',
            'order' => $order,
        ]);
    }

    public function actionNotification()
    {
        \Midtrans\Config::$serverKey = 'SB-Mid-server-IWLFN5qdGmuxRk-QkCSoaTot';
        \Midtrans\Config::$isProduction = false;

        $notif = new \Midtrans\Notification();

        $orderId = explode('-', $notif->order_id)[1]; // karena formatmu ORDER-{id}-{time}
        $transaction = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        $order = Order::findOne($orderId);

        if (!$order) {
            Yii::error("Order tidak ditemukan: " . $orderId);
            return;
        }

        if ($transaction == 'capture') {
            if ($fraud == 'challenge') {
                $order->status = 'pending';
            } else {
                $order->status = 'paid';
            }
        } else if ($transaction == 'settlement') {
            $order->status = 'paid';
        } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            $order->status = 'pending';
        }

        $order->save(false);

        return "OK";
    }
}
