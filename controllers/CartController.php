<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Keranjang;
use app\models\HomepageProduk;

class CartController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                // daftarkan semua action yang butuh proteksi @ (login)
                'only' => ['index', 'add', 'update', 'clear', 'delete', 'update-qty', 'update-satuan', 'checkout'],
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'add' => ['post'],
                    'update' => ['post'],
                    'clear' => ['post'],
                    'delete' => ['post'],
                    'update-qty' => ['post'],
                    'update-satuan' => ['post'],
                    // checkout biasanya GET (render form)
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $items = Keranjang::find()->where(['user_id' => $userId])->with('produk')->all();
        return $this->render('index', ['items' => $items]);
    }

    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'error' => 'not_logged_in'];
        }

        $produkId = Yii::$app->request->post('produk_id');
        $produk = HomepageProduk::findOne($produkId);
        if (!$produk) {
            return ['success' => false, 'error' => 'produk_not_found'];
        }

        $cart = Keranjang::findOne(['user_id' => Yii::$app->user->id, 'produk_id' => $produkId]);
        if ($cart) {
            $cart->jumlah += 1;
        } else {
            $cart = new Keranjang();
            $cart->user_id = Yii::$app->user->id;
            $cart->produk_id = $produkId;
            $cart->jumlah = 1;
            $cart->satuan = 'bijian';
        }

        if ($cart->save()) {
            $count = (int) Keranjang::find()->where(['user_id' => Yii::$app->user->id])->sum('jumlah');
            return ['success' => true, 'count' => $count];
        }

        return ['success' => false, 'errors' => $cart->getErrors()];
    }

    public function actionUpdate($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $cart = Keranjang::findOne($id);
        if (!$cart || $cart->user_id != Yii::$app->user->id)
            return ['success' => false];

        $jumlah = (int) Yii::$app->request->post('jumlah', 1);
        if ($jumlah < 1)
            $jumlah = 1;

        $cart->jumlah = $jumlah;
        if ($cart->save()) {
            $total = (int) Keranjang::find()->where(['user_id' => Yii::$app->user->id])->sum('jumlah');
            return ['success' => true, 'count' => $total];
        }

        return ['success' => false, 'errors' => $cart->getErrors()];
    }

    public function actionClear()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Keranjang::deleteAll(['user_id' => Yii::$app->user->id]);
        return ['success' => true, 'grandTotal' => 0];
    }

    // untuk update qty (dipakai oleh plus/minus)
    public function actionUpdateQty()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');       // id keranjang
        $qty = (int) Yii::$app->request->post('qty');     // jumlah baru

        $item = Keranjang::findOne($id);
        if ($item && $qty > 0 && $item->user_id == Yii::$app->user->id) {
            $item->jumlah = $qty;
            if ($item->save()) {
                $harga = $item->satuan == 'kg'
                    ? $item->produk->harga_kg
                    : $item->produk->harga_bijian;

                $subtotal = $harga * $qty;
                // hitung grand total
                $total = 0;
                $items = Keranjang::find()->where(['user_id' => Yii::$app->user->id])->all();
                foreach ($items as $i) {
                    $hargaItem = $i->satuan == 'kg'
                        ? $i->produk->harga_kg
                        : $i->produk->harga_bijian;

                    $total += $hargaItem * $i->jumlah;
                }

                return [
                    'success' => true,
                    'subtotal' => $subtotal,
                    'grandTotal' => $total
                ];
            }
        }
        return ['success' => false];
    }

    public function actionUpdateSatuan()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $satuan = Yii::$app->request->post('satuan');

        $item = Keranjang::findOne($id);

        if (
            $item &&
            $item->user_id == Yii::$app->user->id &&
            in_array($satuan, ['kg', 'bijian'])
        ) {

            $item->satuan = $satuan;

            if ($item->save()) {

                $harga = $satuan == 'kg'
                    ? $item->produk->harga_kg
                    : $item->produk->harga_bijian;

                $subtotal = $harga * $item->jumlah;

                // grand total
                $grandTotal = 0;

                $items = Keranjang::find()
                    ->where(['user_id' => Yii::$app->user->id])
                    ->all();

                foreach ($items as $i) {

                    $hargaItem = $i->satuan == 'kg'
                        ? $i->produk->harga_kg
                        : $i->produk->harga_bijian;

                    $grandTotal += $hargaItem * $i->jumlah;
                }

                return [
                    'success' => true,
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                    'grandTotal' => $grandTotal
                ];
            }
        }

        return ['success' => false];
    }

    // hapus 1 item
    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $item = Keranjang::findOne($id);

        if ($item && $item->user_id == Yii::$app->user->id) {
            $item->delete();

            // recalc grand total
            $total = 0;
            $items = Keranjang::find()->where(['user_id' => Yii::$app->user->id])->all();
            foreach ($items as $i) {
                $hargaItem = $i->satuan == 'kg'
                    ? $i->produk->harga_kg
                    : $i->produk->harga_bijian;

                $total += $hargaItem * $i->jumlah;
            }

            return ['success' => true, 'grandTotal' => $total];
        }

        return ['success' => false, 'error' => 'not_found'];
    }

    // halaman checkout (GET)
    public function actionCheckout()
    {
        $userId = Yii::$app->user->id;
        $cartItems = Keranjang::find()->where(['user_id' => $userId])->all();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            // hitung total
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->produk->harga * $item->jumlah;
            }

            // simpan orders
            $order = new \app\models\Order();
            $order->user_id = $userId;
            $order->nama = $post['nama'];
            $order->no_hp = $post['no_hp'];
            $order->alamat = $post['alamat'];
            $order->metode_pembayaran = $post['metode_pembayaran'];
            $order->total = $total;

            if ($order->save()) {
                foreach ($cartItems as $item) {
                    $orderItem = new \app\models\OrderItem();
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

                // kosongkan keranjang
                Keranjang::deleteAll(['user_id' => $userId]);

                return $this->redirect(['site/index']);
            }
        }

        return $this->render('checkout', [
            'items' => $cartItems,
        ]);
    }

}
