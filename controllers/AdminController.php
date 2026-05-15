<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\HomepageProduk;
use app\models\Order;
use app\models\OrderItems;
use app\models\User;
use yii\web\Response;

class AdminController extends Controller
{
    public function actionDashboard()
    {
        $produk = HomepageProduk::find()->all();

        $orderDataProvider = new ActiveDataProvider([
            'query' => Order::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 5],
        ]);

        $jumlahCustomer = User::find()->where(['role' => 'user'])->count();
        $totalProduk = HomepageProduk::find()->count();
        $totalOrder = Order::find()->count();

        // Produk terlaris (berdasarkan qty di order_items)
        $produkTerlaris = (new \yii\db\Query())
            ->select(['p.id', 'p.title', 'p.harga_kg', 'p.harga_bijian', 'p.image', 'SUM(oi.qty) AS jumlah_terjual'])
            ->from(['oi' => 'order_items'])
            ->innerJoin(['p' => 'homepage_produk'], 'oi.produk_id = p.id')
            ->groupBy(['p.id', 'p.title', 'p.harga_kg', 'p.harga_bijian', 'p.image'])
            ->orderBy(['jumlah_terjual' => SORT_DESC])
            ->limit(6)
            ->all();

        // Pie chart
        $pieLabels = array_column($produkTerlaris, 'title');
        $pieData = array_column($produkTerlaris, 'jumlah_terjual');

        // Line chart penjualan per bulan
        $bulanDataRaw = (new \yii\db\Query())
            ->select(["MONTH(created_at) as bulan", "SUM(total) as total"])
            ->from('orders')
            ->groupBy(['bulan'])
            ->orderBy(['bulan' => SORT_ASC])
            ->all();

        $bulanLabels = array_map(fn($row) => date('F', mktime(0, 0, 0, $row['bulan'], 1)), $bulanDataRaw);
        $bulanData = array_column($bulanDataRaw, 'total');

        return $this->render('dashboard', compact(
            'produk',
            'orderDataProvider',
            'jumlahCustomer',
            'totalProduk',
            'totalOrder',
            'produkTerlaris',
            'pieLabels',
            'pieData',
            'bulanLabels',
            'bulanData'
        ));
    }

    public function actionCalculator()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            throw new \yii\web\ForbiddenHttpException('Anda tidak memiliki akses ke halaman ini.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => HomepageProduk::find(),
            'pagination' => false, // tidak perlu pagination karena dropdown harus menampilkan semua produk
        ]);

        return $this->render('calculator', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionHistory()
    {
        // ambil list user role user
        $users = \app\models\User::find()
            ->where(['role' => 'user'])
            ->all();

        return $this->render('history', [
            'users' => $users,
        ]);
    }

    public function actionGetHistory($user_id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            // proteksi role admin
            if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
                throw new \yii\web\ForbiddenHttpException('Anda tidak memiliki akses.');
            }

            if ($user_id === null || !is_numeric($user_id)) {
                return ['success' => false, 'message' => 'Parameter user_id tidak valid', 'data' => []];
            }

            // cari class model Order (beberapa project pakai Order atau Orders)
            if (class_exists('\app\models\Orders')) {
                $orderClass = '\app\models\Orders';
            } elseif (class_exists('\app\models\Order')) {
                $orderClass = '\app\models\Order';
            } else {
                throw new \Exception('Model Order/Orders tidak ditemukan di app\\models.');
            }

            // lakukan query aman, pakai with() bila relasi benar
            $orders = $orderClass::find()
                ->where(['user_id' => (int) $user_id])
                ->with(['items.produk', 'user']) // pastikan relasi ini ada: getOrderItems(), getProduk(), getUser()
                ->orderBy(['created_at' => SORT_DESC])
                ->all();

            $result = [];
            foreach ($orders as $order) {
                // fallback: ambil user jika relasi user kosong
                $theUser = $order->user ?? User::findOne($order->user_id);

                foreach ($order->items as $item) {
                    $result[] = [
                        'username' => $theUser ? $theUser->username : null,
                        'nama' => $order->nama,
                        'produk' => $item->produk ? $item->produk->title : ($item->produk_id ?? '-'),
                        'harga' => number_format($item->harga, 0, ',', '.'),
                        'satuan' => ucfirst($item->satuan),
                        'qty' => (int) $item->qty,
                        'subtotal' => number_format($item->subtotal, 0, ',', '.'),
                        'tanggal' => Yii::$app->formatter->asDatetime($item->created_at, 'php:d-m-Y'), // format tanggal
                    ];
                }
            }

            return ['success' => true, 'data' => $result];
        } catch (\Throwable $e) {
            // log lengkap supaya bisa dilacak di runtime/logs/app.log
            Yii::error("actionGetHistory error: " . $e->getMessage() . "\n" . $e->getTraceAsString(), __METHOD__);

            // kembalikan pesan singkat (jangan expose stack trace di production)
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
}
