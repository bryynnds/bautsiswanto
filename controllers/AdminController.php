<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\HomepageProduk;
use app\models\Order;
use app\models\OrderItems;
use app\models\User;

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
        ->select(['p.id','p.title','p.harga','p.image','SUM(oi.qty) AS jumlah_terjual'])
        ->from(['oi'=>'order_items'])
        ->innerJoin(['p'=>'homepage_produk'],'oi.produk_id = p.id')
        ->groupBy(['p.id','p.title','p.harga','p.image'])
        ->orderBy(['jumlah_terjual'=>SORT_DESC])
        ->limit(6)
        ->all();

    // Pie chart
    $pieLabels = array_column($produkTerlaris,'title');
    $pieData = array_column($produkTerlaris,'jumlah_terjual');

    // Line chart penjualan per bulan
    $bulanDataRaw = (new \yii\db\Query())
        ->select(["MONTH(created_at) as bulan","SUM(total) as total"])
        ->from('orders')
        ->groupBy(['bulan'])
        ->orderBy(['bulan'=>SORT_ASC])
        ->all();

    $bulanLabels = array_map(fn($row)=>date('F', mktime(0,0,0,$row['bulan'],1)),$bulanDataRaw);
    $bulanData = array_column($bulanDataRaw,'total');

    return $this->render('dashboard', compact(
        'produk','orderDataProvider','jumlahCustomer','totalProduk','totalOrder',
        'produkTerlaris','pieLabels','pieData','bulanLabels','bulanData'
    ));
}

}
