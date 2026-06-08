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
            'pageSize' => 2,
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