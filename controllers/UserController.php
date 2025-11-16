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

            if (!$user->hasErrors() && $user->save(false)) {
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

        foreach ($items as $item) {
            $data[] = [
                'nama_produk' => $item->produk->title,
                'qty' => $item->qty,
                'harga' => $item->harga,
                'subtotal' => $item->subtotal,
            ];
        }

        return $data;
    }
}
