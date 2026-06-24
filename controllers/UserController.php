<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\Order;
use app\models\OrderItem;
use yii\web\Response;

class UserController extends Controller
{
    // Halaman profil
    public function actionProfile()
    {
        $user = Yii::$app->user->identity;

        // Ambil riwayat order user
        $orders = Order::find()
            ->where(['user_id' => $user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('profile', [
            'user' => $user,
            'orders' => $orders
        ]);
    }

    // Halaman ubah profil
    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        $oldHash = $user->password_hash;

        if ($user->load(Yii::$app->request->post())) {

            if (!empty($user->new_password)) {
                // user ingin ganti password → validate and hash
                if (!$user->hasErrors()) {
                    $user->password_hash = Yii::$app->security->generatePasswordHash($user->new_password);
                }
            } else {
                // tidak ganti password → pakai hash lama
                $user->password_hash = $oldHash;
            }

            if (!$user->hasErrors() && $user->save()) {
                Yii::$app->session->setFlash('success', 'Profil berhasil diperbarui.');
                return $this->redirect(['profile']);
            }
        }

        return $this->render('update', ['user' => $user]);
    }





    public function actionOrders()
    {
        $orders = Order::find()->where(['user_id' => Yii::$app->user->id])->all();
        return $this->render('orders', compact('orders'));
    }


    // Action AJAX untuk mengambil items order
    public function actionOrderItems($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;



        $items = OrderItem::find()->where(['order_id' => $id])->all();

        $data = [];

        $order = Order::findOne($id);

        foreach ($items as $item) {
            $data[] = [
                'nama_produk' => $item->produk->title,
                'qty' => $item->qty,
                'harga' => $item->harga,
                'subtotal' => $item->subtotal,
                'satuan' => $item->satuan,
            ];
        }

        return [
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'metode' => $order->metode_pembayaran,

                'shipping_cost' => $order->shipping_cost,
                'total' => $order->total,

                'courier' => $order->courier,
                'tracking_number' => $order->tracking_number,
                'subtotal_produk' => $order->total - $order->shipping_cost,
                'created_at' => date(
                    'd-m-Y H:i',
                    strtotime($order->created_at)
                ),
            ],
            'items' => $data,
        ];
    }

    public function actionPesananDiterima($id)
    {
        $order = Order::findOne($id);

        if (!$order) {
            throw new \yii\web\NotFoundHttpException();
        }

        if ($order->user_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException();
        }

        if ($order->status === 'shipped') {

            $order->status = 'completed';

            if ($order->save(false)) {

                $notification = new \app\models\Notification();

                $notification->user_id = $order->user_id;

                $notification->title =
                    'Pesanan Selesai';

                $notification->message =
                    'Pesanan #' . $order->id .
                    ' telah selesai dan diterima.';

                $notification->save();

                if (!empty($order->no_hp)) {

                    $nomor = preg_replace(
                        '/[^0-9]/',
                        '',
                        $order->no_hp
                    );

                    if (
                        substr($nomor, 0, 1) == '0'
                    ) {

                        $nomor =
                            '62' .
                            substr($nomor, 1);
                    }

                    $pesan =
                        "✅ PESANAN SELESAI\n\n" .

                        "Halo {$order->nama},\n\n" .

                        "Pesanan Anda telah berhasil diterima.\n\n" .

                        "Nomor Pesanan : #{$order->id}\n\n" .

                        "Terima kasih telah berbelanja di Baut Siswanto.\n\n" .

                        "Kami berharap dapat melayani kebutuhan baut dan mur Anda kembali 🔩";

                    Yii::$app->whatsapp->send(
                        $nomor,
                        $pesan
                    );
                }
            }
        }

        Yii::$app->session->setFlash(
            'success',
            'Pesanan berhasil diselesaikan.'
        );

        return $this->redirect(['profile']);
    }
}
