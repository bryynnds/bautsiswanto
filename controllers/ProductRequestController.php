<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\data\Pagination;

use app\models\ProductRequest;

class ProductRequestController extends Controller
{
    private function getAdminWhatsapp()
    {
        $admin = \app\models\User::find()
            ->where(['role' => 'admin'])
            ->one();

        if (!$admin || empty($admin->no_hp)) {
            return null;
        }

        $nomor = preg_replace('/[^0-9]/', '', $admin->no_hp);

        if (substr($nomor, 0, 1) == '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        return $nomor;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = new ProductRequest();

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = Yii::$app->user->id;
            $model->status = ProductRequest::STATUS_PENDING;

            $uploadedFile = UploadedFile::getInstance($model, 'foto');

            if ($uploadedFile) {

                $fileName = uniqid() . '.' . $uploadedFile->extension;

                $uploadPath = Yii::getAlias('@webroot/uploads/request-products/');

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $uploadedFile->saveAs($uploadPath . $fileName);

                $model->foto = $fileName;
            }

            if ($model->save()) {
                $admin = \app\models\User::find()
                    ->where(['role' => 'admin'])
                    ->one();

                if ($admin && !empty($admin->no_hp)) {

                    $nomor = preg_replace('/[^0-9]/', '', $admin->no_hp);

                    if (substr($nomor, 0, 1) == '0') {
                        $nomor = '62' . substr($nomor, 1);
                    }

                    $user = Yii::$app->user->identity;

                    $pesan =
                        "📦 *PERMINTAAN PRODUK BARU*\n\n" .

                        "Ada permintaan produk baru.\n\n" .

                        "Pengguna : {$user->username}\n" .
                        "Nama Produk : {$model->nama_produk}\n";

                    // Tambahkan jika memang ada field jumlah
                    if (isset($model->jumlah)) {
                        $pesan .= "Jumlah : {$model->jumlah}\n";
                    }

                    // Tambahkan jika memang ada field keterangan
                    if (!empty($model->keterangan)) {
                        $pesan .= "Keterangan : {$model->keterangan}\n";
                    }

                    $pesan .= "\nSilakan login ke dashboard admin untuk menindaklanjuti.";

                    try {

                        $response = Yii::$app->whatsapp->send($nomor, $pesan);

                        Yii::error('Nomor admin: ' . $nomor, 'whatsapp');
                        Yii::error('Response Fonnte: ' . $response, 'whatsapp');

                    } catch (\Exception $e) {

                        Yii::error(
                            'ERROR WA: ' . $e->getMessage(),
                            'whatsapp'
                        );

                    }
                }

                Yii::$app->session->setFlash(
                    'success',
                    'Request produk berhasil dikirim.'
                );

                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {
        $query = ProductRequest::find()
            ->where([
                'product_requests.user_id' => Yii::$app->user->id
            ])
            ->joinWith(['jenisProduk']);

        $search = Yii::$app->request->get('search');
        $status = Yii::$app->request->get('status');
        $sort = Yii::$app->request->get('sort');

        // Search
        if (!empty($search)) {

            $query->andWhere([
                'or',

                ['like', 'product_requests.nama_produk', $search],

                ['like', 'jenis_produk.nama_jenis', $search],

                ['like', 'product_requests.status', $search],
            ]);
        }

        // Filter status
        if (!empty($status)) {

            $query->andWhere([
                'product_requests.status' => $status
            ]);
        }

        // Sorting tanggal
        switch ($sort) {

            case 'oldest':
                $query->orderBy([
                    'product_requests.created_at' => SORT_ASC
                ]);
                break;

            default:
                $query->orderBy([
                    'product_requests.created_at' => SORT_DESC
                ]);
        }

        $countQuery = clone $query;

        $totalRequests = $countQuery->count();

        $pages = new Pagination([
            'totalCount' => $totalRequests,
            'pageSize' => 5,
        ]);

        $requests = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        if (Yii::$app->request->isAjax) {

            return $this->renderPartial('_table', [
                'requests' => $requests,
                'pages' => $pages,
            ]);
        }

        return $this->render('index', [
            'requests' => $requests,
            'pages' => $pages,
            'totalRequests' => $query->count(),
        ]);
    }
}