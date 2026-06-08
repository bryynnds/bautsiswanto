<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use app\models\Notification;
use app\models\ProductRequest;
use yii\data\Pagination;

class AdminProductRequestController extends Controller
{
    public function actionIndex()
    {
        $query = ProductRequest::find()
            ->joinWith(['user', 'jenisProduk']);

        $search = Yii::$app->request->get('search');
        $status = Yii::$app->request->get('status');
        $sort = Yii::$app->request->get('sort');

        // Search
        if (!empty($search)) {

            $query->andWhere([
                'or',

                ['like', 'product_requests.nama_produk', $search],

                ['like', 'user.username', $search],

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
            'totalCount' => $countQuery->count(),
            'pageSize' => 2,
        ]);

        $requests = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        if (Yii::$app->request->isAjax) {

            return $this->renderPartial(
                '/admin/product-request/_table',
                [
                    'requests' => $requests,
                    'pages' => $pages,
                ]
            );
        }

        return $this->render('/admin/product-request/index', [
            'requests' => $requests,
            'pages' => $pages,
            'totalRequests' => $totalRequests,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = ProductRequest::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Request tidak ditemukan.');
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {

                $title = 'Update Request Produk';

                $message = '';

                if ($model->status == 'diproses') {

                    $message = 'Request produk "' . $model->nama_produk . '" sedang diproses oleh admin.';
                } elseif ($model->status == 'tersedia') {

                    $message = 'Produk "' . $model->nama_produk . '" sekarang sudah tersedia.';
                } elseif ($model->status == 'tidak_ditemukan') {

                    $message = 'Produk "' . $model->nama_produk . '" tidak ditemukan di grosir.';
                } else {

                    $message = 'Status request produk diperbarui.';
                }

                $notification = new Notification();

                $notification->user_id = $model->user_id;
                $notification->title = $title;
                $notification->message = $message;

                $notification->save();

                Yii::$app->session->setFlash(
                    'success',
                    'Request berhasil diperbarui.'
                );

                return $this->redirect(['index']);
            }
        }

        return $this->render('/admin/product-request/update', [
            'model' => $model,
        ]);
    }
}