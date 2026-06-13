<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\HomepageProduk;
use app\models\Order;
use app\models\User;
use app\models\ProductRequest;
use Mpdf\Mpdf;

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

        $totalPendapatan = Order::find()
            ->sum('total');

        $pendapatanBulanIni = Order::find()
            ->where([
                'MONTH(created_at)' => date('m'),
                'YEAR(created_at)' => date('Y')
            ])
            ->sum('total');

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
            ->limit(5)
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
            'totalPendapatan',
            'pendapatanBulanIni',
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

    public function actionReport()
    {
        $startDate = Yii::$app->request->get('start_date');
        $endDate = Yii::$app->request->get('end_date');
        $status = Yii::$app->request->get('status');
        $keyword = Yii::$app->request->get('keyword');
        $sort = Yii::$app->request->get('sort');

        $query = Order::find();

        if (!empty($startDate)) {
            $query->andWhere(['>=', 'DATE(created_at)', $startDate]);
        }

        if (!empty($endDate)) {
            $query->andWhere(['<=', 'DATE(created_at)', $endDate]);
        }

        if (!empty($status)) {
            $query->andWhere(['status' => $status]);
        }

        if (!empty($keyword)) {

            $query->andWhere([
                'or',
                ['like', 'nama', $keyword],
                ['like', 'no_hp', $keyword]
            ]);
        }

        switch ($sort) {

            case 'oldest':
                $query->orderBy(['created_at' => SORT_ASC]);
                break;

            case 'highest':
                $query->orderBy(['total' => SORT_DESC]);
                break;

            case 'lowest':
                $query->orderBy(['total' => SORT_ASC]);
                break;

            case 'name_asc':
                $query->orderBy(['nama' => SORT_ASC]);
                break;

            case 'name_desc':
                $query->orderBy(['nama' => SORT_DESC]);
                break;

            default:
                $query->orderBy(['created_at' => SORT_DESC]);
                break;
        }

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
            ,

            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $totalPendapatan = clone $query;
        $totalPendapatan = $totalPendapatan->sum('total');

        $totalPesanan = clone $query;
        $totalPesanan = $totalPesanan->count();

        $totalPelanggan = User::find()
            ->where(['role' => 'user'])
            ->count();

        return $this->render('report', [
            'dataProvider' => $dataProvider,
            'sort' => $sort,
            'totalPendapatan' => $totalPendapatan,
            'totalPesanan' => $totalPesanan,
            'totalPelanggan' => $totalPelanggan,
            'status' => $status,
            'keyword' => $keyword,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function actionExportPdf()
    {
        $startDate = Yii::$app->request->get('start_date');
        $endDate = Yii::$app->request->get('end_date');
        $status = Yii::$app->request->get('status');
        $keyword = Yii::$app->request->get('keyword');

        $query = Order::find();

        if (!empty($startDate)) {
            $query->andWhere(['>=', 'DATE(created_at)', $startDate]);
        }

        if (!empty($endDate)) {
            $query->andWhere(['<=', 'DATE(created_at)', $endDate]);
        }

        if (!empty($status)) {
            $query->andWhere(['status' => $status]);
        }

        if (!empty($keyword)) {

            $query->andWhere([
                'or',
                ['like', 'nama', $keyword],
                ['like', 'no_hp', $keyword]
            ]);
        }

        $orders = $query
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $totalPendapatan = (clone $query)->sum('total');
        $totalPesanan = (clone $query)->count();

        $totalPelanggan = User::find()
            ->where(['role' => 'user'])
            ->count();

        $html = $this->renderPartial('report-pdf', [
            'orders' => $orders,
            'totalPendapatan' => $totalPendapatan,
            'totalPesanan' => $totalPesanan,
            'totalPelanggan' => $totalPelanggan,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        $mpdf = new \Mpdf\Mpdf();

        $mpdf->SetTitle('Laporan Penjualan');

        $mpdf->SetFooter(
            'Dicetak: ' . date('d-m-Y H:i') .
            '| |Halaman {PAGENO}'
        );

        $mpdf->WriteHTML($html);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $mpdf->Output(
            'laporan-penjualan.pdf',
            'D'
        );
        exit;
    }
}