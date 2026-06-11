<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\HomepageProduk;
use app\models\Order;
use app\models\User;
use app\models\ProductRequest;

class OwnerController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login'])->send();
        }

        if (!Yii::$app->user->identity->isOwner()) {
            throw new \yii\web\ForbiddenHttpException(
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        return parent::beforeAction($action);
    }

    public function actionDashboard()
    {
        $produkDataProvider = new ActiveDataProvider([
            'query' => HomepageProduk::find()->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 3,
                'pageParam' => 'produk-page'
            ],
        ]);

        $orderDataProvider = new ActiveDataProvider([
            'query' => Order::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 3,
                'pageParam' => 'order-page'
            ],

        ]);

        $jumlahCustomer = User::find()->where(['role' => 'user'])->count();
        $totalProduk = HomepageProduk::find()->count();
        $totalOrder = Order::find()->count();

        $totalPesananAktif = Order::find()
            ->where([
                'status' => [
                    'pending',
                    'paid',
                    'shipped'
                ]
            ])
            ->count();

        $totalPesananSelesai = Order::find()
            ->where(['status' => 'completed'])
            ->count();

        $totalRequestAktif = ProductRequest::find()
            ->where([
                'status' => [
                    ProductRequest::STATUS_PENDING,
                    ProductRequest::STATUS_DIPROSES
                ]
            ])
            ->count();

        $totalRequestSelesai = ProductRequest::find()
            ->where([
                'status' => [
                    ProductRequest::STATUS_TERSEDIA,
                    ProductRequest::STATUS_TIDAK_DITEMUKAN
                ]
            ])
            ->count();

        // Produk terlaris (berdasarkan qty di order_items)
        $produkTerlaris = (new \yii\db\Query())
            ->select(['p.id', 'p.title', 'p.harga_kg', 'p.harga_bijian', 'p.image', 'SUM(oi.qty) AS jumlah_terjual'])
            ->from(['oi' => 'order_items'])
            ->innerJoin(['p' => 'homepage_produk'], 'oi.produk_id = p.id')
            ->groupBy(['p.id', 'p.title', 'p.harga_kg', 'p.harga_bijian', 'p.image'])
            ->orderBy(['jumlah_terjual' => SORT_DESC])
            ->limit(3)
            ->all();

        $produkTerlarisGrafik = (new \yii\db\Query())
            ->select(['p.id', 'p.title', 'p.harga_kg', 'p.harga_bijian', 'p.image', 'SUM(oi.qty) AS jumlah_terjual'])
            ->from(['oi' => 'order_items'])
            ->innerJoin(['p' => 'homepage_produk'], 'oi.produk_id = p.id')
            ->groupBy(['p.id', 'p.title', 'p.harga_kg', 'p.harga_bijian', 'p.image'])
            ->orderBy(['jumlah_terjual' => SORT_DESC])
            ->limit(10)
            ->all();

        // Pie chart
        $pieLabels = array_column($produkTerlarisGrafik, 'title');
        $pieData = array_column($produkTerlarisGrafik, 'jumlah_terjual');

        // Line chart penjualan per bulan
        $bulanDataRaw = (new \yii\db\Query())
            ->select([
                "MONTH(created_at) as bulan",
                "SUM(total) as total"
            ])
            ->from('orders')
            ->where(['YEAR(created_at)' => date('Y')])
            ->groupBy(['bulan'])
            ->all();

        $dataBulanan = array_fill(1, 12, 0);

        foreach ($bulanDataRaw as $row) {
            $dataBulanan[(int) $row['bulan']] = (float) $row['total'];
        }

        $bulanLabels = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];

        $bulanData = array_values($dataBulanan);

        return $this->render('dashboard', compact(
            'produkDataProvider',
            'orderDataProvider',
            'jumlahCustomer',
            'totalProduk',
            'totalOrder',
            'totalPesananAktif',
            'totalPesananSelesai',
            'totalRequestAktif',
            'totalRequestSelesai',
            'produkTerlaris',
            'produkTerlarisGrafik',
            'pieLabels',
            'pieData',
            'bulanLabels',
            'bulanData'
        ));
    }
}